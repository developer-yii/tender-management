<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tender extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function responsiblePerson()
    {
        return $this->belongsTo(User::class, 'responsible_person_id');
    }

    public function tenderUsers()
    {
        return $this->hasMany(TenderUser::class);
    }

    public function files()
    {
        return $this->morphMany(File::class, 'fileable');
    }

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }
}
