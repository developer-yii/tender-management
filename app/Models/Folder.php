<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Folder extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function folderable()
    {
        return $this->morphTo();
    }
}
