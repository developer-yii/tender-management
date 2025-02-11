<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Status extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'icon'];

    public function tenders()
    {
        return $this->hasMany(Tender::class, 'status', 'id');
    }

    public function getIconUrl()
    {
        if($this->icon)
        {
            if(Storage::disk('local')->exists("public/status/" . $this->icon))
            {
                return asset('storage/status')."/".$this->icon;
            }
        }
        return asset('assest/images/checkdot.png');
    }
}
