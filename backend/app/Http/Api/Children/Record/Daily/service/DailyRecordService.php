<?php

namespace App\Http\Api\Children\Record\Daily\service;

use App\Models\DailyRecord;
use App\Http\Api\Children\Record\Daily\Repositories\DailyRecordRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class DailyRecordService
{
    private $DailyRecordRepository;

    public function __construct(
        DailyRecordRepository $DailyRecordRepository,
    ) {
        $this->DailyRecordRepository = $DailyRecordRepository;
    }

    public function getByDailyRecord(string $childId)
    {
        try {
            $DailyRecords = $this->DailyRecordRepository->findByRecord($childId);
            return $DailyRecords;
        } catch (\Exception $e) {
            throw new Exception('発達記録の取得に失敗しました。');
        }
    }

    public function create(array $data, int $childId)
    {
        $requestData = $data;
        $requestData['child_id'] = $childId;
        $DailyRecord = $this->DailyRecordRepository->create($requestData);
        Log::info($DailyRecord);
        return $DailyRecord;
        // try {
        // } catch (\Exception $e) {
        //     throw new \Exception('成長記録の追加に失敗しました。');
        // }
    }

    public function delete(int $id)
    {
        $this->DailyRecordRepository->delete($id);
    }
}
