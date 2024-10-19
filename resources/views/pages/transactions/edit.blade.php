@extends('layouts.app')

@push('top-scripts')
    <!-- Include SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div class="w-full p-4 md:p-6 2xl:p-10">
    <div class="flex flex-col gap-9">
        <!-- Transaction Form -->
        <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
                <h3 class="font-medium text-black dark:text-white">Edit Transaction</h3>
            </div>
            <form action="{{ route('web.app.transactions.update', $transaction->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6.5">
                    <div class="mb-4.5">
                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                            Account <span class="text-meta-1">*</span>
                        </label>
                        <select name="account_id" 
                                class="w-full rounded border-[1.5px] {{ $errors->has('account_id') ? 'border-danger' : 'border-stroke' }} bg-transparent px-5 py-3 outline-none transition focus:border-primary active:border-primary dark:border-strokedark dark:bg-form-input dark:focus:border-primary">
                            <option value="" disabled>Select an account</option>
                            @foreach ($accounts as $account)
                                <option value="{{ $account->id }}" {{ $transaction->account_id == $account->id ? 'selected' : '' }}>
                                    {{ $account->account_name }} - {{ $account->brand }}
                                </option>
                            @endforeach
                        </select>
                        @if ($errors->has('account_id'))
                            <span class="text-danger text-sm">{{ $errors->first('account_id') }}</span>
                        @endif
                    </div>

                    <div class="mb-4.5">
                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                            Amount <span class="text-meta-1">*</span>
                        </label>
                        <input type="text" id="amount" placeholder="Enter amount" 
                               class="w-full rounded border-[1.5px] {{ $errors->has('amount') ? 'border-danger' : 'border-stroke' }} bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary" 
                               name="amount" value="{{ old('amount', $transaction->transaction_type === 'debit' ? $transaction->debit : $transaction->credit) }}" required />
                        @if ($errors->has('amount'))
                            <span class="text-danger text-sm">{{ $errors->first('amount') }}</span>
                        @endif
                    </div>                                             

                    <div class="mb-4.5">
                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                            Transaction Date <span class="text-meta-1">*</span>
                        </label>
                        <input type="date" 
                               class="w-full rounded border-[1.5px] {{ $errors->has('transaction_date') ? 'border-danger' : 'border-stroke' }} bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary" 
                               name="transaction_date" value="{{ old('transaction_date', \Carbon\Carbon::parse($transaction->transaction_date)->format('Y-m-d')) }}" required />
                        @if ($errors->has('transaction_date'))
                            <span class="text-danger text-sm">{{ $errors->first('transaction_date') }}</span>
                        @endif
                    </div>

                    <div class="mb-4.5">
                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                            Category <span class="text-meta-1">*</span>
                        </label>
                        <input type="text" placeholder="Enter category" 
                               class="w-full rounded border-[1.5px] {{ $errors->has('category') ? 'border-danger' : 'border-stroke' }} bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary" 
                               name="category" value="{{ old('category', $transaction->category) }}" required />
                        @if ($errors->has('category'))
                            <span class="text-danger text-sm">{{ $errors->first('category') }}</span>
                        @endif
                    </div>

                    <div class="mb-4.5">
                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                            Description
                        </label>
                        <textarea placeholder="Enter description" 
                                  class="w-full rounded border-[1.5px] {{ $errors->has('description') ? 'border-danger' : 'border-stroke' }} bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary dark:border-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary" 
                                  name="description">{{ old('description', $transaction->description) }}</textarea>
                        @if ($errors->has('description'))
                            <span class="text-danger text-sm">{{ $errors->first('description') }}</span>
                        @endif
                    </div>

                    <button class="flex w-full justify-center rounded bg-primary p-3 font-medium text-gray hover:bg-opacity-90">
                        Update Transaction
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('bottom-scripts')
    <!-- Success message script -->
    @if (session('toast_success'))
        <script>
            Swal.fire({
                toast: true,
                icon: 'success',
                title: "{{ session('toast_success') }}",
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        </script>
    @endif

    <script>
        function formatCurrency(amount) {
            let negative = false;
            
            if (amount.startsWith('-')) {
                negative = true;
                amount = amount.substr(1);
            }

            let numberString = amount.replace(/[^,\d]/g, '').toString(),
                split = numberString.split(','),
                remainder = split[0].length % 3,
                rupiah = split[0].substr(0, remainder),
                thousands = split[0].substr(remainder).match(/\d{3}/gi);
    
            if (thousands) {
                let separator = remainder ? '.' : '';
                rupiah += separator + thousands.join('.');
            }
    
            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            
            return (negative ? '-' : '') + rupiah;
        }

        function unformatCurrency(value) {
            return value.replace(/[^,\d-]/g, '').replace(',', '.');
        }
    
        const amountInput = document.getElementById('amount');
    
        if (amountInput.value) {
            amountInput.value = formatCurrency(amountInput.value);
        }
    
        amountInput.addEventListener('keyup', function(e) {
            this.value = formatCurrency(unformatCurrency(this.value));
        });
    </script>
@endpush