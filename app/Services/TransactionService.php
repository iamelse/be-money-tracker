<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionService
{
    public function createTransaction(array $data)
    {
        DB::transaction(function () use ($data) {
            
            $account = Account::findOrFail($data['account_id']);

            $debit = $data['debit'] ?? 0;
            $credit = $data['credit'] ?? 0;
            $balanceChange = $credit - $debit;

            $newBalance = $account->balance + $balanceChange;

            $transaction = Transaction::create([
                'user_id' => $data['user_id'],
                'account_id' => $data['account_id'],
                'debit' => $debit,
                'credit' => $credit,
                'transaction_date' => $data['transaction_date'],
                'transaction_type' => $data['transaction_type'],
                'category' => $data['category'],
                'description' => $data['description'],
            ]);

            $account->update(['balance' => $newBalance]);
        });
    }

    public function updateTransaction(array $data, $transactionId)
    {
        DB::transaction(function () use ($data, $transactionId) {

            $transaction = Transaction::findOrFail($transactionId);
            $account = Account::findOrFail($data['account_id']);

            $oldAmount = $transaction->credit > 0 ? $transaction->credit : -$transaction->debit;
            $newBalance = $account->balance - $oldAmount;

            $debit = $data['debit'];
            $credit = $data['credit'];

            $newAmount = $credit > 0 ? $credit : -$debit;
            $newBalance += $newAmount;

            $transaction->update([
                'account_id' => $data['account_id'],
                'debit' => $debit,
                'credit' => $credit,
                'transaction_date' => $data['transaction_date'],
                'transaction_type' => $data['transaction_type'],
                'category' => $data['category'],
                'description' => $data['description'],
            ]);

            $account->update(['balance' => $newBalance]);
        });
    }

    public function deleteTransaction($transactionId)
    {
        DB::transaction(function () use ($transactionId) {
            $transaction = Transaction::findOrFail($transactionId);
            $account = Account::findOrFail($transaction->account_id);

            $debit = $transaction->amount < 0 ? abs($transaction->amount) : 0;
            $credit = $transaction->amount > 0 ? $transaction->amount : 0;

            $newBalance = $account->balance - $credit + $debit;

            $account->update(['balance' => $newBalance]);

            $account->refresh();

            $transaction->delete();
        });
    }
}
