<?php

namespace App\Http\Api\Children\Record;

use App\Http\Controllers\Controller;
use App\Models\GrowthRecord;
use App\Http\Requests\StoreGrowthRecordRequest;
use App\Http\Requests\UpdateGrowthRecordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\GrowthRecordService;

class GrowthRecordController extends Controller
{
    private $growthRecordService;

    public function __construct(GrowthRecordService $growthRecordService)
    {
        $this->growthRecordService = $growthRecordService;
    }

    // 子供IDに基づく成長記録を取得
    public function getByChild($childId)
    {
        try {
            $records = $this->growthRecordService->getByChild($childId);
            return response()->json($records);
        } catch (\Exception $e) {
            return response()->json([
                'message' => '成長記録の取得に失敗しました'
            ], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update(Request $request, GrowthRecord $growthRecord)
    {
        try {
            $growthRecord = $this->growthRecordService->update($request, $growthRecord);
            return response()->json($growthRecord, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => '成長記録の更新に失敗しました'
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GrowthRecord $growthRecord)
    {
        try {
            $growthRecord->delete();
            return response()->json(['message' => '成長記録を削除しました']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => '成長記録の削除に失敗しました'
            ], 400);
        }
    }
}
