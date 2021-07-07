<?php

namespace App\Models;


use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Storage;


/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $articul
 * @property string $name
 * @property string|null $description
 * @property int $price
 * @property string|null $image
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|Product newModelQuery()
 * @method static Builder|Product newQuery()
 * @method static Builder|Product query()
 * @method static Builder|Product whereArticul($value)
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereDescription($value)
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereImage($value)
 * @method static Builder|Product whereName($value)
 * @method static Builder|Product wherePrice($value)
 * @method static Builder|Product whereUpdatedAt($value)
 * @mixin Eloquent
 * @property-read Collection|\App\Models\Catalog[] $catalogs
 * @property-read int|null $catalogs_count
 */
class Product extends Model
{
    use HasFactory;
    protected $fillable = ["articul", "name", "description", "price"];

    public function uploadImage(UploadedFile $file){
        $this->attributes["image"] = $file != null ? $file->store('productImages', 'public') : null;
    }

    public function catalogs(): BelongsToMany
    {
        return $this->belongsToMany("App\Models\Catalog");
    }

    public function clearImage()
    {
        if(!is_null($this->attributes["image"])) {
            Storage::delete($this->attributes["image"]);
        }
    }
}
