<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyRecord extends Model
{
    //
    protected $table = 'dev_records';

    protected $fillable = [
        'child_id',
        'daily_event_stamps_id',
        'record_date',
        'record_time',
        'comment'
    ];

    public function child()
    {
        return $this->belongsTo(Child::class);
    }

    public function eventStamp()
    {
        return $this->belongsTo(DailyEventStamp::class, 'daily_event_stamps_id');
    }
}
