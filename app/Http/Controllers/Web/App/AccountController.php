<?php

namespace App\Http\Controllers\Web\App;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAccountRequest;
use App\Http\Requests\UpdateAccountRequest;
use App\Models\Account;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function index(Request $request) : View
    {
        $filters = [
            'q' => $request->input('q', ''),
            'perPage' => $request->input('limit', 10),
            'columns' => ['account_name', 'account_type', 'brand', 'balance']
        ];
        
        $accounts = $this->_getFilteredAccounts($filters);

        return view('pages.accounts.index', [
            'title' => 'Your accounts | CashFlow',
            'accounts' => $accounts,
            'perPage' => $filters['perPage'],
            'q' => $filters['q']
        ]);
    }

    public function create() : View 
    {
        $account_types = ['savings', 'credit', 'investment'];
        $brands = ['BNI', 'BCA', 'BSI', 'DANA', 'BRI', 'OVO'];

        return view('pages.accounts.create', [
            'title' => 'Your Accounts | CashFlow',
            'account_types' => $account_types,
            'brands' => $brands
        ]);
    }

    public function store(StoreAccountRequest $request)
    {
        $account = Account::create([
            'user_id' => Auth::user()->id,
            'brand' => $request->brand,
            'account_name' => $request->account_name,
            'account_type' => $request->account_type,
            'balance' => $request->balance,
        ]);

        return redirect()->route('web.app.accounts.create')->withToastSuccess('Account created successfully!');
    }

    public function edit(Account $account) : View
    {
        return abort(404);
        /*
        $account_types = ['savings', 'credit', 'investment'];
        $brands = ['BNI', 'BCA', 'BSI', 'DANA', 'BRI', 'OVO'];

        return view('pages.accounts.edit', [
            'title' => 'Edit Account | CashFlow',
            'account' => $account,
            'account_types' => $account_types,
            'brands' => $brands
        ]);
        */
    }

    public function update(UpdateAccountRequest $request, Account $account) : RedirectResponse
    {
        return abort(404);
        /*
        $account->update([
            'user_id' => Auth::user()->id,
            'brand' => $request->brand,
            'account_name' => $request->account_name,
            'account_type' => $request->account_type,
            'balance' => $request->balance,
        ]);

        return redirect()->route('web.app.accounts.edit', $account->id)->withToastSuccess('Account updated successfully!');
        */
    }

    public function destroy(Account $account) : RedirectResponse 
    {
        $account->delete();

        return redirect()->route('web.app.accounts.index')->withToastSuccess('Account deleted successfully!');
    }

    private function _getFilteredAccounts($filters)
    {
        return Account::where('user_id', Auth::user()->id)->filter($filters);
    }
}
