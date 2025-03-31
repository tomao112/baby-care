<?php

namespace App\Repositories;

use App\Models\Child;
use App\Models\GrowthRecord;
use Illuminate\Support\Facades\DB;

class GrowthRecordRepository
{
    public function findByRecord(string $childId): ?Child
    {
        return GrowthRecord::where('child_id', $childId)
        ->orderBy('record_date', 'desc')
        ->get();
    }

    public function create(array $validated): GrowthRecord
    {
        return GrowthRecord::create($validated);
    }

    public function update(int $id, array $validated): GrowthRecord
    {
        $record = GrowthRecord::findOrFail($id);
        $record->update($validated);
        return $record->fresh();
    }

    public function delete(int $id): void
    {
        $record = GrowthRecord::findOrFail($id);
        $record->delete();
    }
}
