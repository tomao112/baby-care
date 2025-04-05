<?php

namespace App\Services;

use App\Repositories\GrowthRecordRepository;
use App\Models\GrowthRecord;
use Illuminate\Http\Request;
use Exception;

class GrowthRecordService
{
    private $growthRecordRepository;

    public function __construct(
        GrowthRecordRepository $growthRecordRepository,
    ) {
        $this->growthRecordRepository = $growthRecordRepository;
    }

    public function getByChild(string $childId)
    {
        try {
            $growthRecords = $this->growthRecordRepository->findByRecord($childId);
            return $growthRecords;
        } catch (\Exception $e) {
            throw new Exception('成長記録の取得に失敗しました。');
        }
    }

    public function update(Request $request, GrowthRecord $growthRecord)
    {
        try {
            $growthRecord->update($request->all());
            return $growthRecord;
        } catch (\Exception $e) {
            throw new Exception('成長記録の更新に失敗しました。');
        }
    }
}
