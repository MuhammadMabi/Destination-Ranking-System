<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Rankings extends Model
{
    use HasFactory;
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'destination_id',
        'rank',
        'score',
        'details',
    ];

    protected $casts = [
        'details' => 'array',
    ];

    protected static function booted()
    {
        static::creating(fn($model) => $model->id = $model->id ?? (string) Str::uuid());
    }

    public function destination()
    {
        return $this->belongsTo(Destinations::class, 'destination_id');
    }
}
