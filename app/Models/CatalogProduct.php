<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\CatalogProduct
 *
 * @property int $id
 * @property int $catalog_id
 * @property int $product_id
 * @method static \Illuminate\Database\Eloquent\Builder|CatalogProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CatalogProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CatalogProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|CatalogProduct whereCatalogId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CatalogProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CatalogProduct whereProductId($value)
 * @mixin \Eloquent
 */
class CatalogProduct extends Model
{
    use HasFactory;
    protected $table = "catalog_product";
    protected $fillable = ["catalog_id", "product_id"];
    public $timestamps = false;
}
