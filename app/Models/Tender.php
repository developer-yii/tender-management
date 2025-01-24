<?php

namespace App\Models;

use Carbon\Carbon;
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

    public function setPeriodFromAttribute($value)
    {
        $this->attributes['period_from'] = Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
    }

    public function getPeriodFromAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d-m-Y') : '';
    }

    public function setPeriodToAttribute($value)
    {
        $this->attributes['period_to'] = Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
    }

    public function getPeriodToAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d-m-Y') : '';
    }

    public function setBindingPeriodAttribute($value)
    {
        $this->attributes['binding_period'] = Carbon::createFromFormat('d-m-Y', $value)->format('Y-m-d');
    }

    public function getBindingPeriodAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d-m-Y') : '';
    }

    public function setQuestionAskLastDateAttribute($value)
    {
        $this->attributes['question_ask_last_date'] = Carbon::createFromFormat('d-m-Y H:i', $value)->format('Y-m-d H:i:s');
    }

    public function getQuestionAskLastDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d-m-Y H:i') : '';
    }

    public function setOfferPeriodExpirationAttribute($value)
    {
        $this->attributes['offer_period_expiration'] = Carbon::createFromFormat('d-m-Y H:i', $value)->format('Y-m-d H:i:s');
    }

    public function getOfferPeriodExpirationAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('d-m-Y H:i') : '';
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'tender_users', 'tender_id', 'user_id')
                    ->withTimestamps()
                    ->withTrashed();
    }

    public function files()
    {
        return $this->hasMany(TenderFile::class, 'tender_id');
    }

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
