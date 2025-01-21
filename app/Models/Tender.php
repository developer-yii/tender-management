<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tender extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $appends = ['status_icon', 'status_text'];
    const tenderStatus = [1 => 'in Bearbeitung', 2 => 'in Betracht', 3 => 'Erhalten'];
    const abgabeForms = [1 => 'Option 1', 2 => 'Option 2', 3 => 'Option 3'];
    const options = [0 => 'No', 1 => 'Yes'];


    // public function getMainImgUrl()
    // {
    //     if ($this->profile_photo) {
    //         $subFolder = "employee" . $this->id;  // Match the subfolder logic
    //         $filePath = "public/employees/{$subFolder}/{$this->profile_photo}";  // Updated path
    //         if (Storage::disk('local')->exists($filePath)) {
    //             return asset('storage/employees/' . $subFolder . '/' . $this->profile_photo);  // Correct URL structure
    //         }
    //     }
    //     return asset('assest/images/image_not_available.png');
    // }

    // public function tenderUsers()
    // {
    //     return $this->hasMany(TenderUser::class, 'tender_id');
    // }

    // public function files()
    // {
    //     return $this->morphMany(File::class, 'fileable');
    // }

    public function users()
    {
        return $this->belongsToMany(User::class, 'tender_users', 'tender_id', 'user_id')
                    ->withTimestamps()
                    ->withTrashed(); // Use withTrashed() instead of withSoftDeletes
    }

    public function files()
    {
        return $this->hasMany(TenderFile::class, 'tender_id');
    }

    // public function addresses()
    // {
    //     return $this->morphMany(Address::class, 'addressable');
    // }

    public function getStatusIconAttribute()
    {
        $statusIcons = [
            1 => 'Wait.png',
            2 => 'orange-dot.png',
            3 => 'green-dot.png',
        ];

        return $statusIcons[$this->status] ?? 'gray-dot.png';
    }

    public function getStatusTextAttribute()
    {
        return self::tenderStatus[$this->status] ?? 'Unknown status';
    }

    public function getAbgabeformTextAttribute()
    {
        return self::abgabeForms[$this->abgabeform] ?? 'Unknown Abgabeform';
    }
}
