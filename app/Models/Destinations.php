<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Destinations extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'name',
        'location_id',
        'description',
        'image',
    ];

    protected static function booted()
    {
        static::creating(fn($model) => $model->id = $model->id ?? (string) Str::uuid());
    }

    public function destinationCriterias()
    {
        return $this->hasMany(DestinationCriteria::class, 'destination_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function rankings()
    {
        return $this->hasMany(Rankings::class, 'destination_id');
    }
}
