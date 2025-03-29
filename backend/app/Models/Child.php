<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Database\Factories\ChildFactory;

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

    public function growthRecords(): HasMany
    {
        return $this->hasMany(growthRecords::class);
    }

    protected static function newFactory()
    {
        return ChildFactory::new();
    }
}
