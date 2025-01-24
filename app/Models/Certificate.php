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

    public function setValidFromDateAttribute($value)
    {
        $this->attributes['valid_from_date'] = Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
    }

    public function setValidToDateAttribute($value)
    {
        $this->attributes['valid_to_date'] = Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
    }

    public function getValidFromDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d-m-Y') : '';
    }

    public function getValidToDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d-m-Y') : '';
    }

    public function getLogoUrl()
    {
        if ($this->logo) {
            $subFolder = "certificate" . $this->id;  // Match the subfolder logic
            $filePath = "public/certificates/{$subFolder}/{$this->logo}";  // Updated path
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/certificates/' . $subFolder . '/' . $this->logo);  // Correct URL structure
            }
        }
        return asset('assest/images/image_not_available.png');
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

    public function getDocxPreviewUrl()
    {
        if ($this->docx_preview) {
            $subFolder = "certificate" . $this->id;  // Match the subfolder logic
            $filePath = "public/certificates/{$subFolder}/{$this->docx_preview}";  // Updated path
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/certificates/' . $subFolder . '/' . $this->docx_preview);  // Correct URL structure
            }
        }
        return '';
    }
}
