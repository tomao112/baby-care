<?php

namespace Tests\Unit\Service;

use Tests\TestCase;
use App\Services\ChildService;
use App\Models\Child;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Mockery;
use Database\Factories\UserFactory;

class ChildServiceTest extends TestCase
{
    use RefreshDatabase;

    private ChildService $childService;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->childService = new ChildService();

        // ユーザーを作成して認証状態にする
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_index_returns_user_children()
    {
        // 子供のテストデータを作成
        $children = Child::factory()->count(3)->create([
            'user_id' => $this->user->id
        ]);

        $result = $this->childService->index();

        $this->assertCount(3, $result);
        $this->assertEquals($children->pluck('id'), $result->pluck('id'));
    }

    public function test_store_creates_new_child()
    {
        $childData = [
            'name' => 'テスト太郎',
            'birth_date' => '2020-01-01'
        ];

        // モックリクエストの作成
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('validated')
            ->once()
            ->andReturn($childData);

        $result = $this->childService->store($request);

        $this->assertDatabaseHas('children', [
            'name' => 'テスト太郎',
            'user_id' => $this->user->id
        ]);
    }

    public function test_update_modifies_child_data()
    {
        // 子供のテストデータを作成
        $child = Child::factory()->create([
            'user_id' => $this->user->id,
            'name' => '古い名前'
        ]);

        $updateData = [
            'name' => '新しい名前'
        ];

        // モックリクエストの作成
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('validated')
            ->once()
            ->andReturn($updateData);

        $this->childService->update($request, $child);

        $this->assertDatabaseHas('children', [
            'id' => $child->id,
            'name' => '新しい名前'
        ]);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}