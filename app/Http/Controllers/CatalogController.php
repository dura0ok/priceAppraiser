<?php

namespace App\Http\Controllers;

use App\Http\Requests\Catalog as CatalogRequest;
use App\Models\Catalog;
use App\Services\Response;
use App\Services\SpreadSheet as SpreadSheetHelper;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;

class CatalogController extends Controller
{

    /**
     * @var SpreadSheetHelper
     */
    private $spreadSheetHelper;

    public function __construct()
    {
        $this->spreadSheetHelper = new SpreadSheetHelper();
    }

    public function show()
    {
        $catalogs = Catalog::all();
        return view("pages.catalogs.index", ['catalogs' => $catalogs]);
    }

    public function getPrintPage()
    {
        $catalogs = Catalog::all();
        return view("pages.catalogs.print", ["catalogs" => $catalogs]);
    }

    public function store(CatalogRequest $request): JsonResponse
    {
        try {
            Catalog::create(["name" => $request->get("name")]);
            return Response::goodResponse(["message" => "Успешно"]);
        } catch (Exception $e) {
            return Response::badResponse($e->getMessage());
        }

    }


    public function update(CatalogRequest $request): JsonResponse
    {
        $catalog = Catalog::find($request->get("id"));
        if (!is_null($catalog)) {
            $catalog->name = $request->get("name");
            $catalog->save();
            return Response::goodResponse(["message" => "Успешно"]);
        }
        return Response::badResponse("Ошибка, такого ID не найдено");
    }

    public function destroy(CatalogRequest $request): JsonResponse
    {
        $count = Catalog::destroy($request->get("id"));
        if ($count != 0) {
            return Response::goodResponse(["message" => "Успешно"]);
        }
        return Response::badResponse("Ошибка, такого ID не найдено");
    }


    public function print(Request $request)
    {
        Storage::makeDirectory("prints");
        $request->validate([
            "percent" => "required|numeric",
            "catalog_id" => "required"
        ]);
        $catalogStyleArray = [
            "font" => [
                'bold' => true,
                'color' => array('rgb' => '0000FF'),
                "size" => 16
            ]
        ];

        $data = [];
        $styles = [];
        $catalog_id = $request->get("catalog_id");
        $catalogs = ($catalog_id == "all") ? Catalog::with("products")->get() : Catalog::where("id", $catalog_id)->with("products")->get();
        for ($catalog_index = 0; $catalog_index < count($catalogs); $catalog_index++){
            switch ($request->get("sort")){
                case "articul":
                    $catalogs[$catalog_index]["products"] = $catalogs[$catalog_index]["products"]->sortBy("articul");
                    break;
                case "name":
                    $catalogs[$catalog_index]["products"] = $catalogs[$catalog_index]["products"]->sortBy("name");
                    break;
            }
        }
        $strokeCounter = 1;
        foreach ($catalogs as $catalog) {
            $data[] = ["Каталог:" . $catalog->name];
            $styles["A" . $strokeCounter] = $catalogStyleArray;
            $strokeCounter++;
            $data[] = ["Артикул", "Наименование", "Описание", "Цена"];
            foreach (range("A", "D") as $word) {
                $styles[$word . $strokeCounter] = array_merge(SpreadSheetHelper::$defaultStyleArray, $catalogStyleArray);
            }
            $strokeCounter++;
            foreach ($catalog->products as $product) {
                $price = $product->price * (1 + $request->get("percent") / 100);
                $data[] = [$product->articul, $product->name, $product->description, $price];
                foreach (range("A", "D") as $item) {
                    $styles[$item . $strokeCounter] = SpreadSheetHelper::$defaultStyleArray;
                }
                $strokeCounter++;
            }
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $this->spreadSheetHelper->fillData($sheet, $data, $styles);
        try {
            $fpath = $this->spreadSheetHelper->save($spreadsheet, "prints");
            return response()->download($fpath);
        } catch (Exception $e) {
            return Redirect::back()->withErrors([$e->getMessage()]);
        }
    }

}
