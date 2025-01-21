<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentParameter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'document_id',
        'param_name',
        'param_value',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

}
