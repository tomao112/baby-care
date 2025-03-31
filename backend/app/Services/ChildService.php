<?php

namespace App\Services;

use App\Models\Child;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ChildService
{
    public function index()
    {
        try {
            $children = Auth::user()->children;
            return $children;
        } catch (\Exception $e) {
            throw new \Exception('子供の情報の取得に失敗しました。');
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validated();
            $validated['user_id'] = Auth::id();
            $child = Child::create($validated);
            return $child;
        } catch (\Exception $e) {
            throw new \Exception('子供の追加に失敗しました。');
        }
    }

    public function update(Request $request, Child $child)
    {
        try {
            // バリデーション
            $validated = $request->validated();

            // 子供の情報を更新
            $child->update($validated);
        } catch (\Exception $e) {
            throw new \Exception('子供の情報の更新に失敗しました。');
        }
    }

    public function getAllChildren()
    {
        return Child::all();
    }
}
