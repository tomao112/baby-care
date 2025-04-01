<?php

namespace Tests\Unit\Http\Controllers\Api;

use Tests\TestCase;
use App\Http\Controllers\Api\GrowthRecordController;
use App\Services\GrowthRecordService;
use App\Models\GrowthRecord;
use Illuminate\Http\Request;
use Mockery;

class GrowthRecordControllerTest extends TestCase
{
    protected $controller;
    protected $growthRecordService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->growthRecordService = Mockery::mock(GrowthRecordService::class);
        $this->controller = new GrowthRecordController($this->growthRecordService);
    }

    public function test_update_modifies_record_successfully()
    {
        // テストデータ準備
        $growthRecord = new GrowthRecord();  // モックではなく実際のインスタンスを使用
        $request = new Request();

        // モックの設定
        $this->growthRecordService
            ->shouldReceive('update')
            ->with(Mockery::type(Request::class), Mockery::type(GrowthRecord::class))
            ->once()
            ->andReturn($growthRecord);

        // テスト実行
        $response = $this->controller->update($request, $growthRecord);

        // アサーション
        $this->assertEquals(200, $response->status());
        $this->assertEquals($growthRecord->toArray(), $response->getData(true));
    }

    public function test_update_handles_error()
    {
        // テストデータ準備
        $growthRecord = new GrowthRecord();
        $request = new Request();

        // モックの設定
        $this->growthRecordService
            ->shouldReceive('update')
            ->with(Mockery::type(Request::class), Mockery::type(GrowthRecord::class))
            ->once()
            ->andThrow(new \Exception('エラー'));

        // テスト実行
        $response = $this->controller->update($request, $growthRecord);

        // アサーション
        $this->assertEquals(400, $response->status());
        $this->assertEquals('成長記録の更新に失敗しました', $response->getData(true)['message']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}