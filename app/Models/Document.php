<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory, SoftDeletes;

    const categories = ['Versicherung', 'Finanzamt'];

    protected $fillable = [
        'category_name',
        'title',
        'document_pdf',
        'docx_preview',
    ];

    public function parameters()
    {
        return $this->hasMany(DocumentParameter::class, 'document_id');
    }

    public function getDocumentPdfUrl()
    {
        if ($this->document_pdf) {
            $subFolder = "document" . $this->id;  // Match the subfolder logic
            $filePath = "public/documents/{$this->document_pdf}";  // Updated path
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/documents/' . $this->document_pdf);  // Correct URL structure
            }
        }
        return '';
    }

    // public function getDocxPreviewUrl()
    // {
    //     if ($this->docx_preview) {
    //         $subFolder = "document" . $this->id;  // Match the subfolder logic
    //         $filePath = "public/documents/{$subFolder}/{$this->docx_preview}";  // Updated path
    //         if (Storage::disk('local')->exists($filePath)) {
    //             return asset('storage/documents/' . $subFolder . '/' . $this->docx_preview);  // Correct URL structure
    //         }
    //     }
    //     return '';
    // }

}
