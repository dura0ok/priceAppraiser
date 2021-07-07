<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Appraising extends Model
{
    use HasFactory;
    protected $fillable = ["user_id", "file"];

    public function user(): BelongsTo
    {
        return $this->belongsTo("App\Models\User");
    }

    public function clearFile(){
        if(!is_null($this->attributes["file"])) {
            Storage::delete($this->attributes["file"]);
        }
    }
}
