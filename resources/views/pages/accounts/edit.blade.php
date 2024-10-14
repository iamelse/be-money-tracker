@extends('layouts.app')

@push('top-scripts')
    <!-- Include SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div class="w-full p-4 md:p-6 2xl:p-10">
    <div class="flex flex-col gap-9">
        <!-- Account Form -->
        <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
                <h3 class="font-medium text-black dark:text-white">Edit Account</h3>
            </div>
            <form action="{{ route('web.app.accounts.update', $account->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="p-6.5">
                    <div class="mb-4.5">
                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                            Account Name <span class="text-meta-1">*</span>
                        </label>
                        <input type="text" placeholder="Enter account name" 
                               class="w-full rounded border-[1.5px] {{ $errors->has('account_name') ? 'border-danger' : 'border-stroke' }} bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary" 
                               name="account_name" value="{{ old('account_name', $account->account_name) }}" required />
                        @if ($errors->has('account_name'))
                            <span class="text-danger text-sm">{{ $errors->first('account_name') }}</span>
                        @endif
                    </div>
                    
                    <div class="mb-4.5">
                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                            Brand <span class="text-meta-1">*</span>
                        </label>
                        <select name="brand" 
                                class="w-full rounded border-[1.5px] {{ $errors->has('brand') ? 'border-danger' : 'border-stroke' }} bg-transparent px-5 py-3 outline-none transition focus:border-primary active:border-primary dark:border-strokedark dark:bg-form-input dark:focus:border-primary">
                            <option value="" disabled>Select account brand</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand }}" {{ old('brand', $account->brand) === $brand ? 'selected' : '' }}>{{ $brand }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('brand'))
                            <span class="text-danger text-sm">{{ $errors->first('brand') }}</span>
                        @endif
                    </div>

                    <div class="mb-4.5">
                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                            Account Type <span class="text-meta-1">*</span>
                        </label>
                        <select name="account_type" 
                                class="w-full rounded border-[1.5px] {{ $errors->has('account_type') ? 'border-danger' : 'border-stroke' }} bg-transparent px-5 py-3 outline-none transition focus:border-primary active:border-primary dark:border-strokedark dark:bg-form-input dark:focus:border-primary">
                            <option value="" disabled>Select account type</option>
                            @foreach ($account_types as $type)
                                <option value="{{ $type }}" {{ old('account_type', $account->account_type) === $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('account_type'))
                            <span class="text-danger text-sm">{{ $errors->first('account_type') }}</span>
                        @endif
                    </div>

                    <div class="mb-4.5">
                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                            Balance <span class="text-meta-1">*</span>
                        </label>
                        <input type="text" id="balance" placeholder="Enter balance" 
                               class="w-full rounded border-[1.5px] {{ $errors->has('balance') ? 'border-danger' : 'border-stroke' }} bg-transparent px-5 py-3 font-normal text-black outline-none transition focus:border-primary active:border-primary disabled:cursor-default disabled:bg-whiter dark:border-form-strokedark dark:bg-form-input dark:text-white dark:focus:border-primary" 
                               name="balance" value="{{ old('balance', $account->calculate_balance()) }}" required />
                        @if ($errors->has('balance'))
                            <span class="text-danger text-sm">{{ $errors->first('balance') }}</span>
                        @endif
                    </div>

                    <button class="flex w-full justify-center rounded bg-primary p-3 font-medium text-gray hover:bg-opacity-90">
                        Update Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('bottom-scripts')
    <!-- Check for success message -->
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
        function formatCurrency(amount, prefix) {
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
            return prefix === undefined ? rupiah : (rupiah ? 'Rp ' + rupiah : '');
        }
    
        function unformatCurrency(value) {
            return value.replace(/[^,\d]/g, '').replace(',', '.');
        }
    
        const balanceInput = document.getElementById('balance');
    
        if (balanceInput.value) {
            balanceInput.value = formatCurrency(balanceInput.value);
        }
    
        balanceInput.addEventListener('keyup', function(e) {
            this.value = formatCurrency(unformatCurrency(this.value));
        });
    </script>    
@endpush