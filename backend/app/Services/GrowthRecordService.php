<?php

namespace App\Services;

use App\Repositories\GrowthRecordRepository;
use App\Models\GrowthRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            Log::info('更新サービス呼び出し:', [
                'id' => $growthRecord->id,
                'データ' => $request->all()
            ]);

            // バリデーション
            $validated = $request->validate([
                'child_id' => 'required|exists:children,id',
                'record_date' => 'required|date',
                'height' => 'required|numeric',
                'weight' => 'required|numeric',
                'memo' => 'nullable|string'
            ]);

            // 月ごとに一度だけという制約をチェック
            // record_dateの年月が同じなら更新可能
            $recordDate = new \DateTime($validated['record_date']);
            $existingDate = new \DateTime($growthRecord->record_date);

            if ($recordDate->format('Y-m') !== $existingDate->format('Y-m')) {
                Log::warning('異なる月の記録を更新しようとしています', [
                    '既存' => $existingDate->format('Y-m'),
                    '新規' => $recordDate->format('Y-m')
                ]);
            }

            // レコードを更新
            $growthRecord->update($validated);
            return $growthRecord->fresh();
        } catch (\Exception $e) {
            Log::error('更新エラー詳細: ' . $e->getMessage(), [
                'スタックトレース' => $e->getTraceAsString()
            ]);
            throw new Exception('成長記録の更新に失敗しました: ' . $e->getMessage());
        }
    }

    public function create(Request $request)
    {
        try {
            $growthRecord = $this->growthRecordRepository->create($request->all());
            return $growthRecord;
        } catch (\Exception $e) {
            throw new Exception('成長記録の作成に失敗しました。');
        }
    }
}
