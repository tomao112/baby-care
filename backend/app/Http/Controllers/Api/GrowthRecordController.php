<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GrowthRecord;
use App\Http\Requests\StoreGrowthRecordRequest;
use App\Http\Requests\UpdateGrowthRecordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GrowthRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // $growthRecord = GrowthRecord::all();
        // return response()->json($growthRecord);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGrowthRecordRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    // public function show(Request $request, GrowthRecord $growthRecord)
    // {
    //     // 特定の成長記録1件を取得
    //     $record = GrowthRecord::with('child')->findOrFail($growthRecord->id);
    //     return response()->json($record);
    // }

    // 子供IDに基づく成長記録を取得
    public function getByChild($childId)
    {
        $records = GrowthRecord::where('child_id', $childId)
            ->orderBy('record_date', 'desc')
            ->get();
        return response()->json($records);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, GrowthRecord $growthRecord)
    {
        //バリデーション
        $validated = $request->validate([
            'child_id' => 'required|exists:children,id',
            'record_date' => 'required|date',  // 名前がrecord_dateであることを確認
            'height' => 'nullable|numeric|min:0|max:200',  // 必須から任意に変更
            'weight' => 'nullable|numeric|min:0|max:100',  // 必須から任意に変更
            'memo' => 'nullable|string|max:500',
        ]);

        // 現在のユーザIDを追加
        // $validated['child_id'] = Auth::id();

        // 子供の情報を追加
        $growthRecord = GrowthRecord::create($validated);

        return response()->json($growthRecord, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // バリデーション
        $validated = $request->validate([
            'record_date' => 'required|date',
            'height' => 'required|numeric',
            'weight' => 'required|numeric',
            'memo' => 'nullable|string'
        ]);

        // 指定されたIDの成長記録を取得して更新
        $record = GrowthRecord::findOrFail($id);

        // 認可チェック（この記録がユーザーのものかどうか）
        if ($record->child->user_id !== Auth::id()) {
            return response()->json(['message' => 'This action is unauthorized.'], 403);
        }

        // データを更新
        $record->update($validated);

        return response()->json($record);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $record = GrowthRecord::findOrFail($id);

        // 認可チェック（この記録がユーザーのものかどうか）
        if ($record->child->user_id !== Auth::id()) {
            return response()->json(['message' => 'This action is unauthorized.'], 403);
        }

        $record->delete();

        return response()->json(['message' => 'Record deleted successfully']);
    }
}
