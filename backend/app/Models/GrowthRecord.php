<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrowthRecord extends Model
{
    /** @use HasFactory<\Database\Factories\GrowthRecordFactory> */
    use HasFactory;

    protected $fillable = [
        'child_id',
        'record_date',
        'height',
        'weight',
        'memo',
    ];

    protected $casts = [
        'record_date' => 'date',
        'height' => 'float',
        'weight' => 'float',
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }
}
