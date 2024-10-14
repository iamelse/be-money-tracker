<?php

namespace App\Http\Controllers\Web\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Account;
use App\Models\LedgerEntry;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
    
    public function index(Request $request)
    {
        $filters = [
            'q' => $request->input('q', ''),
            'perPage' => $request->input('limit', 10),
            'account_id' => $request->input('account_id', null),
            'columns' => ['amount', 'category', 'description']
        ];

        $transactions = $this->_getFilteredTransactions($filters);

        return view('pages.transactions.index', [
            'title' => 'Your Transactions | CashFlow',
            'transactions' => $transactions,
            'perPage' => $filters['perPage'],
            'q' => $filters['q'],
            'account_id' => $filters['account_id'],
        ]);
    }

    public function create()
    {
        $accounts = Account::where('user_id', Auth::user()->id)->get();

        return view('pages.transactions.create', [
            'title' => 'New Transaction | CashFlow',
            'accounts' => $accounts
        ]);
    }

    public function store(StoreTransactionRequest $request)
    {
        try {
            $this->transactionService->createTransaction([
                'user_id' => Auth::user()->id,
                'account_id' => $request->account_id,
                'amount' => $request->amount,
                'transaction_date' => $request->transaction_date,
                'category' => $request->category,
                'description' => $request->description,
            ]);

            return redirect()->route('web.app.transactions.create')->withToastSuccess('Transaction created successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('web.app.transactions.create')->withToastError($th->getMessage());
        }
    }

    public function edit(Transaction $transaction)
    {
        $transaction = Transaction::findOrFail($transaction->id);
        $accounts = Account::where('user_id', Auth::user()->id)->get();

        return view('pages.transactions.edit', [
            'title' => 'Edit Transaction | CashFlow',
            'transaction' => $transaction,
            'accounts' => $accounts,
        ]);
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        try {
            $oldTransaction = Transaction::findOrFail($transaction->id);

            DB::transaction(function () use ($request, $oldTransaction) {
                $oldTransaction->update($request->validated());

                $this->_updateAccountBalance($oldTransaction->account_id);
                
                $newAmount = $request->amount;
                $oldTransaction->transaction_type = $newAmount >= 0 ? 'credit' : 'debit';
                $oldTransaction->save();
            });

            return redirect()->route('web.app.transactions.index')->withToastSuccess('Transaction updated successfully.');
        } catch (\Throwable $th) {
            return redirect()->route('web.app.transactions.edit', $transaction->id)->withToastError($th->getMessage());
        }
    }

    public function destroy(Transaction $transaction)
    {
        $transactionService = new TransactionService();

        try {
            $transactionService->deleteTransaction($transaction->id);
            return redirect()->route('web.app.transactions.index')->with('success', 'Transaction deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('web.app.transactions.index')->with('error', 'Failed to delete transaction: ' . $e->getMessage());
        }
    }

    private function _getFilteredTransactions($filters)
    {
        return Transaction::where('user_id', Auth::user()->id)
            ->with('account')
            ->whereIn('account_id', Auth::user()->accounts()->pluck('id'))
            ->filter($filters);
    }

    private function _updateAccountBalance(Account $account)
    {
        $account = Account::findOrFail($account->id);
        $balance = $account->calculate_balance();
        $account->balance = $balance;
        $account->save();
    }
}