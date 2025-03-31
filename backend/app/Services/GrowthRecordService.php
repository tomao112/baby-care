<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\GrowthRecordRepository;
use App\Services\TokenService;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\Auth\InvalidCredentialsException;
use App\Exceptions\Auth\UserAlreadyExistsException;
use App\Events\Auth\UserRegistered;
use Illuminate\Support\Facades\DB;
use App\Models\GrowthRecord;
use Illuminate\Http\Request;

class GrowthRecordService
{
    private $growthRecordRepository;

    public function __construct(
        GrowthRecordRepository $growthRecordRepository,
    ) {
        $this->GrowthRecordRepository = $growthRecordRepository;
    }

    public function getByChild(string $childId)
    {
        try {
            $growthRecords = $this->growthRecordRepository->findByChildId($childId);
            return $growthRecords;
        } catch (\Exception $e) {
            throw new Exception('成長記録の取得に失敗しました。');
        }
    }

    public function update(Request $request, GrowthRecord $growthRecord)
    {
        try {
            $growthRecord->update($request->all());
            return $growthRecord;
        } catch (\Exception $e) {
            throw new Exception('成長記録の更新に失敗しました。');
        }
    }
}