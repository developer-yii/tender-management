<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TenderFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'tender_id',
        'type',
        'folder_name',
        'original_file_name',
        'file_path',
        'docx_preview'
    ];

    // protected $appends = ['docx_preview_url', 'pdf_preview_url'];

    public function tender()
    {
        return $this->belongsTo(Tender::class, 'tender_id');
    }

    public function getDocxPreviewUrl()
    {
        if ($this->docx_preview) {
            $subFolder = "tender" . $this->tender_id;
            $filePath = "public/tenders/{$subFolder}/{$this->docx_preview}";
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/tenders/' . $subFolder . '/' . $this->docx_preview);
            }
        }
        return '';
    }

    public function getFilePathUrl()
    {
        if ($this->file_path) {
            $subFolder = "tender" . $this->tender_id;
            $filePath = "public/tenders/{$subFolder}/{$this->file_path}";
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/tenders/' . $subFolder . '/' . $this->file_path);
            }

        }
        return '';
    }
}
