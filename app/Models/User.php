<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function getProfilePicUrl()
    {
        if ($this->profile_photo) {
            $subFolder = "employee" . $this->id;  // Match the subfolder logic
            $filePath = "public/employees/{$subFolder}/{$this->profile_photo}";  // Updated path
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/employees/' . $subFolder . '/' . $this->profile_photo);  // Correct URL structure
            }
        }
        return asset('assest/images/default-user.jpg');
    }

    public function getCvUrl()
    {
        if ($this->cv) {
            $subFolder = "employee" . $this->id;  // Match the subfolder logic
            $filePath = "public/employees/{$subFolder}/{$this->cv}";  // Updated path
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/employees/' . $subFolder . '/' . $this->cv);  // Correct URL structure
            }
        }
        return '';
    }

    public function getDocumentUrl()
    {
        if ($this->document) {
            $subFolder = "employee" . $this->id;  // Match the subfolder logic
            $filePath = "public/employees/{$subFolder}/{$this->document}";  // Updated path
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/employees/' . $subFolder . '/' . $this->document);  // Correct URL structure
            }
        }
        return '';
    }

    public function getDocxPreviewUrl()
    {
        if ($this->docx_preview) {
            $subFolder = "employee" . $this->id;  // Match the subfolder logic
            $filePath = "public/employees/{$subFolder}/{$this->docx_preview}";  // Updated path
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/employees/' . $subFolder . '/' . $this->docx_preview);  // Correct URL structure
            }
        }
        return '';
    }

    // public function tenderUsers()
    // {
    //     return $this->hasMany(TenderUser::class);
    // }

    public function tenders()
    {
        return $this->belongsToMany(Tender::class, 'tender_users', 'user_id', 'tender_id')
                    ->withTimestamps()
                    ->withTrashed(); // Use withTrashed() instead of withSoftDeletes
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_users');
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    // public function addresses()
    // {
    //     return $this->morphMany(Address::class, 'addressable');
    // }
}
