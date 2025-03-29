<?php

namespace Tests\Unit\Http\Requests\Children;

use App\Http\Requests\Children\AddChildRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class AddChildRequestTest extends TestCase
{
    protected $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->request = new AddChildRequest();
    }

    // 正しいデータの場合のテスト
    public function test_正しい子供の情報でバリデーションが通る(): void
    {
        $validator = Validator::make([
            'name' => 'たろう',
            'birth_date' => '2020-01-01',
            'gender' => 'male',
            'notes' => 'とても元気な子です',
        ], $this->request->rules());

        $this->assertFalse($validator->fails());
    }

    // 名前が空の場合のテスト
    public function test_名前が空の場合はエラーになる(): void
    {
        $validator = Validator::make([
            'name' => '',
            'birth_date' => '2020-01-01',
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('name'));
    }

    // 誕生日の形式が間違っている場合のテスト
    public function test_誕生日の形式が間違っている場合はエラーになる(): void
    {
        $validator = Validator::make([
            'name' => 'たろう',
            'birth_date' => '間違った日付',
        ], $this->request->rules());

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('birth_date'));
    }
}