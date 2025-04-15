<?php

namespace App\Http\Api\Children\Record;

use App\Http\Controllers\Controller;
use App\Models\GrowthRecord;
use App\Http\Requests\StoreGrowthRecordRequest;
use App\Http\Requests\UpdateGrowthRecordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\GrowthRecordService;
use Illuminate\Support\Facades\Log;

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

    public function create(Request $request)
    {
        try {
            $growthRecord = $this->growthRecordService->create($request);
            return response()->json($growthRecord, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => '成長記録の作成に失敗しました'
            ], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function update(Request $request, $id)
    {
        try {
            // IDを使って明示的にレコードを取得
            $growthRecord = GrowthRecord::findOrFail($id);

            Log::info('更新リクエスト受信:', [
                'id' => $id,
                'record_id' => $growthRecord->id,
                'データ' => $request->all()
            ]);

            $growthRecord = $this->growthRecordService->update($request, $growthRecord);
            return response()->json($growthRecord, 200);
        } catch (\Exception $e) {
            Log::error('更新エラー: ' . $e->getMessage(), [
                'スタックトレース' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => '成長記録の更新に失敗しました: ' . $e->getMessage()
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
