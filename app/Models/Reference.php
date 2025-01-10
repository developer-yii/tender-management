<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Reference extends Model
{
    use HasFactory;

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
}
