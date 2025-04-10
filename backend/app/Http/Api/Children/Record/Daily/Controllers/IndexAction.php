<?php

namespace App\Http\Api\Children\Record\Daily\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Api\Children\Record\Daily\service\DailyRecordService;
// use App\Services\DailyRecordService as ServicesDailyRecordService;

class IndexAction extends Controller
{
    /**
     * Handle the incoming request.
     */
    private $dailyRecordService;

    public function __construct(DailyRecordService $dailyRecordService)
    {
        $this->dailyRecordService = $dailyRecordService;
    }
    public function __invoke(Request $request)
    {
        $dailyRecord = $this->dailyRecordService->create($request->all(), $request->child_id);
        return response()->json([
            'dailyRecord' => $dailyRecord,
            'message' => '成長記録を追加しました',
        ], 200);
    }
}
