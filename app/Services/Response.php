<?php


namespace App\Services;


use App\Models\Catalog;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class Response
{
    public static function goodResponse($data) : JsonResponse{
        $data["error"] = false;
        return response()->json($data);
    }

    public static function badResponse($message): JsonResponse
    {
        return response()->json(["error" => true, "message" => $message], 422);
    }

    public static function generatePagination($page, $size, $catalog_id = null): JsonResponse
    {
        if (!is_null($catalog_id)){
            $catalog = Catalog::with("products.catalogs")->where("id", $catalog_id)->first();
            $last_page = intdiv($catalog->products->count(), $size);
            $items = $catalog->products->skip($size*($page-1))->take($size);

            return response()->json(["last_page" => $last_page, "data" => $items->take($size)->toArray()]);
        }
        $last_page = intdiv(Product::count(), $size);
        $items = Product::offset($size*($page-1))->take(Product::count())->with("catalogs")->get();
        return response()->json(["last_page" => $last_page, "data" => $items->take($size)->toArray()]);

    }

    public static function getRoles(): array
    {
        return ["dealer", "client"];
    }
}
