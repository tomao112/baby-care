<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Children\AddChildRequest;
use App\Http\Requests\Children\UpdateChildRequest;
use Illuminate\Support\Facades\Log;
use App\Helpers\LogHelper;

class ChildController extends Controller
{
    /**
     * ログインユーザーの子供一覧を取得
     *
     * @return void
     */
    public function index()
    {
        $children = Auth::user()->children;
        return response()->json($children);

    }


    /**
     * 子供の情報を追加
     *
     * @param AddChildRequest $request
     * @return void
     */
    public function store(AddChildRequest $request)
    {
        try {

            // バリデーション
            $validated = $request->validated();
            // ユーザーIDを追加
            $validated['user_id'] = Auth::id();

            // 子供の情報を追加
            $child = Child::create($validated);

            // log出力
            LogHelper::pretty_log('子供の追加リクエスト', [
                '子供の情報' => $child,
            ]);

            return response()->json($child, 201);
        } catch (ChildAddException $e) {
            return response()->json([
                'message' => '子供の情報を正しく追加できませんでした。'
            ], 400);
        }
    }

    /**
     * 子供の情報を取得
     *
     * @param Child $child
     * @return void
     */
    public function show(Child $child)
    {
        return response()->json($child);
    }

    /**
     * 子供の情報を更新
     *
     * @param UpdateChildRequest $request
     * @param Child $child
     * @return void
     */
    public function update(UpdateChildRequest $request, Child $child)
    {
        try {
            // バリデーション
            $validated = $request->validated();

            // 子供の情報を更新
            $child->update($validated);

            return response()->json($child);
        } catch (ChildUpdateException $e) {
            return response()->json([
                'message' => '子供の情報を正しく更新できませんでした。'
            ], 400);
        }
    }



    /**
     * 子供の情報を削除
     *
     * @param Child $child
     * @return void
     */
    public function destroy(Child $child)
    {
        try {
            $child->delete();

            return response()->json(['message' => '削除しました']);
        } catch (Exception $e) {
            return response()->json([
                'message' => '子供の情報を正しく削除できませんでした。'
            ], 400);
        }

    }
}
