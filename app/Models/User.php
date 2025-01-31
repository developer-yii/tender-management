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

    public function isAdmin()
    {
        return $this->role == '1';
    }

    private function getFileUrl($type, $fileName)
    {
        if ($fileName) {
            $subFolder = "{$type}" . $this->id;  // Subfolder based on type and user ID
            $filePath = "public/{$type}s/{$subFolder}/{$fileName}";  // Updated path based on type
            if (Storage::disk('local')->exists($filePath)) {
                return asset('storage/' . $type . 's/' . $subFolder . '/' . $fileName);  // Correct URL structure
            }
        }
        return '';  // Return an empty string if the file doesn't exist
    }

    public function getProfilePicUrl()
    {
        return $this->getFileUrl('employee', $this->profile_photo) ?: asset('assest/images/default-user.jpg');
    }

    public function getAdminProfilePicUrl()
    {
        return $this->getFileUrl('admin', $this->profile_photo) ?: asset('assest/images/default-user.jpg');
    }

    public function getCvUrl()
    {
        return $this->getFileUrl('employee', $this->cv);  // Will return an empty string if not found
    }

    public function getDocumentUrl()
    {
        return $this->getFileUrl('employee', $this->document);  // Will return an empty string if not found
    }

    public function getDocxPreviewUrl()
    {
        return $this->getFileUrl('employee', $this->docx_preview);  // Will return an empty string if not found
    }

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

}
