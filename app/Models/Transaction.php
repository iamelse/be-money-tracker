<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function scopeFilter($query, $filters = [])
    {
        $q = $filters['q'] ?? null;
        $perPage = $filters['perPage'] ?? 10;
        $columns = $filters['columns'] ?? [];
        $account_id = $filters['account_id'] ?? null;

        return $query->with('account')
            ->when($account_id, function ($query) use ($account_id) {
                $query->where('account_id', $account_id);
            })
            ->when($q, function ($query) use ($q, $columns) {
                $query->where(function ($subquery) use ($q, $columns) {
                    foreach ($columns as $column) {
                        $subquery->orWhere($column, 'LIKE', "%$q%");
                    }
                });
            })
            ->latest('transaction_date')
            ->paginate($perPage);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }
}