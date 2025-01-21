<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Templete extends Model
{
    use HasFactory, SoftDeletes;

    public function getTempleteFileUrl()
    {
        if ($this->templete_file) {
            $filePath = "public/templetes/{$this->templete_file}";  // Updated path
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/templetes/' . $this->templete_file);  // Correct URL structure
            }
        }
        return '';
    }

    public function isPdf()
    {
        return strtolower(pathinfo($this->templete_file, PATHINFO_EXTENSION)) === 'pdf';
    }

    public function isImage()
    {
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        return in_array(strtolower(pathinfo($this->templete_file, PATHINFO_EXTENSION)), $imageExtensions);
    }
}
