<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class ChildController extends Controller
{
    /**
     * ログインユーザーの子ども一覧を取得
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $children = Auth::user()->$children;
        return response()->json($children);
    }

    /**
     * 新しい子ども情報を保存
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //バリデーション
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'nullable|in:male,female,other',
            'notes' => 'nullable|string',
        ]);

        // 現在のユーザIDを追加
        $validated['user_id'] = Auth::id();

        // 子供の情報を追加
        $child = Child::create($validated);

        return response()->json($child, 201);
    }

    /**
     * 指定した子ども情報を取得
     *
     * @param  \App\Models\Child  $child
     * @return \Illuminate\Http\Response
     */
    public function show(Child $child)
    {
        //権限チェック
        if(Auth::id() !== $child->user_id) {
            return response()->json(['message' => '権限がありません'], 403);
        }

        return response()->json($child);
    }

    /**
     * 指定した子ども情報を更新
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Child  $child
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Child $child)
    {
        //権限チェック
        if(Auth::id() !== $child->user_id) {
            return response()->json(['message' => '権限がありません'], 403);
        }

        // バリデーション
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'nullable|in:male,female,other',
            'notes' => 'nullable|string',
        ]);

        // 子供の情報を更新
        $child->update($validated);

        return response()->json($child);
    }


        /**
     * 指定した子ども情報を削除
     *
     * @param  \App\Models\Child  $child
     * @return \Illuminate\Http\Response
     */
    public function destroy(Child $child)
    {
        //権限チェック
        if(Auth::id() !== $child->user_id) {
            return response()->json(['message' => '権限がありません'], 403);
        }

        $child->delete();

        return response()->json(['message' => '削除しました']);
    }
}
