<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Reference extends Model
{
    use HasFactory;

    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
    }

    public function getStartDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d-m-Y') : '';
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
    }

    public function getEndDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d-m-Y') : '';
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'reference_tags');
    }

    public function getFileWordUrl()
    {
        if ($this->file_word) {
            $subFolder = "reference" . $this->id;  // Match the subfolder logic
            $filePath = "public/references/{$subFolder}/{$this->file_word}";  // Updated path
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/references/' . $subFolder . '/' . $this->file_word);  // Correct URL structure
            }
        }
    }
    public function getFilePdfUrl()
    {
        if ($this->file_pdf) {
            $subFolder = "reference" . $this->id;  // Match the subfolder logic
            $filePath = "public/references/{$subFolder}/{$this->file_pdf}";  // Updated path
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/references/' . $subFolder . '/' . $this->file_pdf);  // Correct URL structure
            }
        }
    }

    public function getDocxPreviewUrl()
    {
        if ($this->docx_preview) {
            $subFolder = "reference" . $this->id;  // Match the subfolder logic
            $filePath = "public/references/{$subFolder}/{$this->docx_preview}";  // Updated path
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/references/' . $subFolder . '/' . $this->docx_preview);  // Correct URL structure
            }
        }
        return '';
    }
}
