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
        $start_date = $filters['start_date'] ?? null;
        $end_date = $filters['end_date'] ?? null;
        $transaction_type = $filters['transaction_type'] ?? null;
        $order_by = $filters['order_by'] ?? 'transaction_date';
        $order_direction = $filters['order_direction'] ?? 'desc';

        return $query->with('account')
            ->when($account_id, function ($query) use ($account_id) {
                $query->where('account_id', $account_id);
            })
            ->when($transaction_type, function ($query) use ($transaction_type) {
                $query->where('transaction_type', $transaction_type);
            })
            ->when($q, function ($query) use ($q, $columns) {
                $query->where(function ($subquery) use ($q, $columns) {
                    foreach ($columns as $column) {
                        $subquery->orWhere($column, 'LIKE', "%$q%");
                    }
                });
            })
            ->when($start_date, function ($query) use ($start_date) {
                $query->whereDate('transaction_date', '>=', $start_date);
            })
            ->when($end_date, function ($query) use ($end_date) {
                $query->whereDate('transaction_date', '<=', $end_date);
            })
            ->orderBy($order_by, $order_direction)
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