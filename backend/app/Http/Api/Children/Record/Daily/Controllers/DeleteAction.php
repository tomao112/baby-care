<?php

namespace App\Http\Api\Children\Record\Daily\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Api\Children\Record\Daily\service\DailyRecordService;

class DeleteAction extends Controller
{
    private $dailyRecordService;

    public function __construct(DailyRecordService $dailyRecordService)
    {
        $this->dailyRecordService = $dailyRecordService;
    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, $childId, $id)
    {
        // ルートパラメータから直接IDを取得
        $this->dailyRecordService->delete($id);
        return response()->json([
            'message' => '育児記録を削除しました',
        ], 200);
    }
}
