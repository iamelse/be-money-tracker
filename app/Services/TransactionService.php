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
            $amount = $data['amount'];
            
            $newBalance = $account->balance + $amount;

            $transaction = Transaction::create([
                'user_id' => $data['user_id'],
                'account_id' => $data['account_id'],
                'amount' => $amount,
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
            
            $transaction->update([
                'account_id' => $data['account_id'],
                'amount' => $data['amount'],
                'transaction_date' => $data['transaction_date'],
                'transaction_type' => $data['transaction_type'],
                'category' => $data['category'],
                'description' => $data['description'],
            ]);

            $transaction->refresh();
            $account->refresh();
            $account->update(['balance' => $account->balance]);
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
