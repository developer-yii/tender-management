<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class, 'tag_users');
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_tags');
    }

    public function references()
    {
        return $this->belongsToMany(Reference::class, 'reference_tags');
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($tag) {
            $tag->users()->detach();
            $tag->projects()->detach();
        });
    }
}
