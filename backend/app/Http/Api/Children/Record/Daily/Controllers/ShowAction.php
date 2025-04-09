<?php

namespace App\Http\Api\Children\Record\Daily\Controllers;

use App\Http\Controllers\Controller;
use App\Services\DailyRecordService;

class ShowAction extends Controller
{
    //
    private $dailyRecordService;

    public function __construct(DailyRecordService $dailyRecordService)
    {
        $this->dailyRecordService = $dailyRecordService;
    }

    public function __invoke($childId)
    {
        try {
            $dailyRecord = $this->dailyRecordService->getByDailyRecord($childId);
            return response()->json($dailyRecord, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => '発達記録の取得に失敗しました。'
            ], 400);
        }
    }
}
