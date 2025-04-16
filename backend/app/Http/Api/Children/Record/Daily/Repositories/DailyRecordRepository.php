<?php

namespace App\Http\Api\Children\Record\Daily\Repositories;

use App\Models\Child;
use App\Models\DailyRecord;
use Illuminate\Support\Facades\DB;

class DailyRecordRepository
{
    public function findByRecord(string $childId): \Illuminate\Database\Eloquent\Collection
    {
        return DailyRecord::where('child_id', $childId)
            ->orderBy('record_date', 'desc')
            ->get();
    }

    public function create(array $validated): DailyRecord
    {
        return DailyRecord::create($validated);
    }

    public function delete(int $id): void
    {
        $record = DailyRecord::findOrFail($id);
        $record->delete();
    }
}
