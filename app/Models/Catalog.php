<?php

namespace App\Models;

use Eloquent;
use GeneaLabs\LaravelPivotEvents\Traits\PivotEventTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * App\Models\Catalog
 *
 * @property int $id
 * @property string $name
 * @method static Builder|Catalog newModelQuery()
 * @method static Builder|Catalog newQuery()
 * @method static Builder|Catalog query()
 * @method static Builder|Catalog whereId($value)
 * @method static Builder|Catalog whereName($value)
 * @mixin Eloquent
 * @property-read Collection|\App\Models\Product[] $products
 * @property-read int|null $products_count
 */
class Catalog extends Model
{
    use HasFactory;
    use PivotEventTrait;
    protected $fillable = ["name"];
    public $timestamps = false;

    public function products(): BelongsToMany
    {
        return $this->belongsToMany("App\Models\Product");
    }

}
