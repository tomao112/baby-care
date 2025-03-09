<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Child extends Model
{
    use HasFactory;

    /**
     * 複数代入可能な属性
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'birth_date',
        'gender',
        'notes',
    ];

    /**
     * 日付として扱う属性
     * 
     * @var array<int, string>
     */
    protected $casts = [
        'birth_date' => 'date',
    ];

    /**
     * 子供の親ユーザー取得
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
