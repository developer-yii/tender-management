<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Certificate extends Model
{
    use HasFactory;

    const categories = ['Projektmanagement', 'Softwareentwicklung'];

    public function getLogoUrl()
    {
        if ($this->logo) {
            $subFolder = "certificate" . $this->id;  // Match the subfolder logic
            $filePath = "public/certificates/{$subFolder}/{$this->logo}";  // Updated path
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/certificates/' . $subFolder . '/' . $this->logo);  // Correct URL structure
            }
        }
        return asset('img/image_not_available.png');
    }

    public function getCertificateWordUrl()
    {
        if ($this->certificate_word) {
            $subFolder = "certificate" . $this->id;  // Match the subfolder logic
            $filePath = "public/certificates/{$subFolder}/{$this->certificate_word}";  // Updated path
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/certificates/' . $subFolder . '/' . $this->certificate_word);  // Correct URL structure
            }
        }
    }
    public function getCertificatePdfUrl()
    {
        if ($this->certificate_pdf) {
            $subFolder = "certificate" . $this->id;  // Match the subfolder logic
            $filePath = "public/certificates/{$subFolder}/{$this->certificate_pdf}";  // Updated path
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/certificates/' . $subFolder . '/' . $this->certificate_pdf);  // Correct URL structure
            }
        }
    }
}
