<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Goal extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'current_amount' => 'float',
        'target_amount' => 'float',
    ];

    protected function formattedTargetAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Rp. ' . number_format($this->attributes['target_amount'], 0, ',', '.')
        );
    }

    protected function formattedCurrentAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Rp. ' . number_format($this->attributes['current_amount'], 0, ',', '.')
        );
    }

    protected function deadline(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? Carbon::parse($value)->format('d M, Y') : null
        );
    }

    protected function formattedDeadlineForEditBlade(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->attributes['deadline'] 
                ? Carbon::parse($this->attributes['deadline'])->format('Y-m-d') 
                : null
        );
    }

    protected function progress(): Attribute
    {
        return Attribute::make(
            get: function () {
                $current = $this->current_amount ?? 0;
                $target = $this->target_amount ?? 1;

                return ($target > 0) 
                    ? round(($current / $target) * 100) . '%'
                    : '0%';
            }
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}