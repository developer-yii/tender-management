<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectTag extends Model
{
    use HasFactory, SoftDeletes;

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }
}
