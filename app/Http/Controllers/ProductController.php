<?php

namespace App\Http\Controllers;

use App\Http\Requests\MassUploadProductRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Appraising;
use App\Models\Catalog;
use App\Models\CatalogProduct;
use App\Models\Product;
use App\Services\RequestHelper;
use App\Services\Response;
use App\Services\SpreadSheet as SpreadSheetHelper;
use DB;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ProductController extends Controller
{


    private $spreadSheetHelper;
    private $requestHelper;

    public function __construct()
    {
        $this->requestHelper = new RequestHelper();
        $this->spreadSheetHelper = new SpreadSheetHelper();
    }

    public function index($catalog_id = null)
    {
        return view("pages.products.index", ["catalog" => Catalog::find($catalog_id)]);
    }

    public function getProducts(Request $request): JsonResponse
    {
        $catalog_id = ($request->get("catalog_id") != "undefined") ? $request->get("catalog_id") : null;
        $page = $request->get("page");
        $size = $request->get("size");
        return Response::generatePagination($page, $size, $catalog_id);
    }

    public function create()
    {
        $catalogs = Catalog::all();
        return view("pages.products.create", ["catalogs" => $catalogs]);
    }

    public function store(ProductRequest $request): RedirectResponse
    {
        try{
            $product = new Product();
            $product->fill($request->all());
            $product->uploadImage($request->file("file"));
            $product->save();
            foreach($request->get("catalogs") as $catalog_id){
                CatalogProduct::create(["catalog_id" => $catalog_id, "product_id" => $product->id]);
            }
            return Redirect::route("products.index");
        } catch (Exception $e){
            return Redirect::back()->withErrors($e->getMessage());
        }

    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $catalogs = Catalog::all();
        foreach ($catalogs as $catalog) {
            $catalog->own = false;
            if(CatalogProduct::where("catalog_id", $catalog->id)->where("product_id", $product->id)->count() > 0){
                $catalog->own = true;
            }
        }
        return view("pages.products.edit", ["catalogs" => $catalogs, "product" => $product]);
    }

    public function update(ProductRequest $request, $id)
    {
        $id = $id ?? $request->get("id");
        $product = Product::findOrFail($id);
        $product->fill($request->all());


        if($request->get("req") != "api"){
            if(!is_null($request->file("file"))){
                $product->clearImage();
                $product->uploadImage($request->file("file"));
            }
            CatalogProduct::where("product_id", $id)->delete();
            foreach($request->get("catalogs") as $catalog_id){
                CatalogProduct::create(["catalog_id" => $catalog_id, "product_id" => $product->id]);
            }
            $product->save();
            return Redirect::route("products.edit", $id);
        }
        $product->save();
        return Response::goodResponse(["message" => "Success"]);

    }

    public function destroy(Request $request, $id)
    {
        $id = $id ?? $request->get("id");
        try {
            $product = Product::findOrFail($id);
            $product->clearImage();
            Product::destroy($id);
            if($request->get("req") != "api"){
                Return Redirect::back();
            }
            return Response::goodResponse(['message' => "success"]);
        } catch (Exception $e) {
            return Response::badResponse($e->getMessage());
        }
    }

    public function getImportPage()
    {
        $catalogs = Catalog::all();
        return view("pages.products.import", ["catalogs" => $catalogs]);
    }


    public function import(MassUploadProductRequest $request): RedirectResponse
    {
        $duplicates = [];
        try {
            $catalog = Catalog::findOrFail($request->get("catalog_id"));
            $rows = $this->requestHelper->readFile($request->file("file"));
            $columns = $this->spreadSheetHelper->findColumnIndexes(array_map("strtolower", explode(";", $request->get("columns"))));
            foreach ($rows as $row) {
                $values = $this->spreadSheetHelper->generateRowValues($row, $columns);
                $products = Product::where("articul", $values["articul"])->first();
                if(empty($products)) {
                    $id = Product::create($values);
                    $catalog->products()->attach($id);
                    continue;
                }else{
                    if (DB::table("catalog_product")->where("catalog_id", $catalog->id)->where("product_id", $product->id)->count() == 0){
                        $catalog->products()->attach($product->id);
                        continue;
                    }
                    $duplicates[] = "Ошибка! Продукт с ".$values["articul"]." уже существует в данном каталоге";
                }
            }
        } catch (Exception $e) {
            return Redirect::back()->withErrors([$e->getMessage()]);
        }
        return Redirect::back()->withErrors($duplicates);
    }

    public function search(){
        return view("pages.products.search");
    }

    public function find(Request $request)
    {
        $request->validate(["field" => "required"]);
        $products = Product::where("articul", $request->get("field"))->orWhere("name", "like", "%".$request->get("field")."%")->get();
        if($products->isEmpty()){
            return Redirect::back();
        }
        return view("pages.products.show", ["products" => $products]);
    }


    public function getAppraisePage(){
        return view("pages.products.appraise");
    }

    public function appraise(Request $request)
    {
        Storage::makeDirectory("appraisings");
        $request->validate([
            "articulColumn" => "required|string|regex:/^[a-z]{1}$/i",
            "counterColumn" => "required|string|regex:/^[a-z]{1}$/i",
            "percent" => "numeric",
            'file' => 'file|mimes:xls,xlsx',
        ]);
        $alphabet = $this->spreadSheetHelper->getAlphabet();
        $columns = [
            "articul" => array_search(strtolower($request->get("articulColumn")), $alphabet),
            "counter" => array_search(strtolower($request->get("counterColumn")), $alphabet)
        ];
        try {
            $rows = $this->requestHelper->readFile($request->file("file"));
            $data = []; $styles = []; $strokeCounter = 1;
            $data[] = ["Артикул", "Наименование", "Описание", "Количество", "Сумма"];
            foreach (range("A", "E") as $word) {
                $styles[$word . $strokeCounter] = SpreadSheetHelper::$defaultStyleArray;
            }
            $strokeCounter++;
            $cost = 0; $percent = null;
            switch (\Auth::user()->role){
                case "admin":
                    $percent = $request->get("percent");
                    break;
                case "dealer":
                    $percent = \Auth::user()->percent;
                    break;
                case "client":
                    $percent = 20;
                    break;
                default:
                    $percent = 20;
                    break;
            }
            foreach ($rows as $row){
                if(is_null($row[$columns["articul"]]) || is_null($row[$columns["counter"]])){
                    $data[] = [];
                    $strokeCounter++;
                    continue;
                }
                $articul = str_replace(" ", "", $row[$columns["articul"]]);
                try{
                    $product = Product::where("articul", $articul)->firstOrFail();
                    $counter = (int)trim($row[$columns["counter"]]);
                    $price = ($product->price*(1+$percent/100))*$counter;
                    $data[] = [$product->articul, $product->name, $product->description, $counter, $price];
                    $cost+=$price;
                    foreach (range("A", "E") as $item) {
                        $styles[$item . $strokeCounter] = SpreadSheetHelper::$defaultStyleArray;
                    }
                    $strokeCounter++;
                }
                catch (ModelNotFoundException $e){
                    $data[] = [$articul. " - Артикул не найден"];
                    $strokeCounter++;
                    continue;
                }
                catch (\Exception $exception){
                    continue;
                }
            }
            $data[] = [null, null, null, null, "Итого: ".$cost];
            $styles["E" . $strokeCounter] = array(
                'borders' => array(
                    'outline' => array(
                        'borderStyle' => Border::BORDER_THICK,
                        'color' => array('argb' => '00000000'),
                    ),
                ),
                'fill' => array(
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => array('argb' => 'FF4F81BD')
                )
            );
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $this->spreadSheetHelper->fillData($sheet, $data, $styles);
            $fpath = $this->spreadSheetHelper->save($spreadsheet, "appraisings");
            Appraising::create(["user_id" => \Auth::user()->id, "file" => "appraisings/".basename($fpath)]);
            return response()->download($fpath);
        } catch (Exception $e) {
            return Redirect::back()->withErrors([$e->getMessage()]);
        }

    }

    public function showAppraisings(){
        $appraisings = Appraising::with("user")->get();
        return view("pages.products.showAppraisings", ["appraisings" => $appraisings]);
    }

    public function deleteAppraising($id): RedirectResponse
    {
        $appraising = Appraising::find($id);
        $appraising->delete();
        return Redirect::back();
    }
}
