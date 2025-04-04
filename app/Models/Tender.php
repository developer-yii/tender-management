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
    // const abgabeForms = [1 => 'Option 1', 2 => 'Option 2', 3 => 'Option 3'];
    const options = [0 => 'Nein', 1 => 'Ja'];

    public function tenderStatus()
    {
        return $this->belongsTo(Status::class, 'status', 'id');
    }

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

    public function abgabeformValue()
    {
        return $this->belongsTo(Abgabeform::class, 'abgabeform', 'id');
    }

    // public function getAbgabeformTextAttribute()
    // {
    //     return self::abgabeForms[$this->abgabeform] ?? 'Unknown Abgabeform';
    // }
}
