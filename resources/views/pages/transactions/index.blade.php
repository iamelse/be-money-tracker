@extends('layouts.app')

@push('top-scripts')
    <!-- Include SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
    <!-- ===== Main Content Start ===== -->
    <main>
        <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
            <!-- Breadcrumb Start -->
            <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <h2 class="text-title-md2 font-bold text-black dark:text-white">
                    Transactions
                </h2>
                <!--
                <nav>
                    <ol class="flex items-center gap-2">
                        <li>
                            <a class="font-medium" href="index.html">Dashboard /</a>
                        </li>
                        <li class="font-medium text-primary">Tables</li>
                    </ol>
                </nav>
                -->
            </div>
            <!-- Breadcrumb End -->

            <!-- Filter -->
            <div class="mb-10 rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
                <div class="border-b border-stroke px-7 py-4 dark:border-strokedark">
                    <h3 class="font-medium text-black dark:text-white">Transaction Filters</h3>
                </div>
                <form action="{{ route('web.app.transactions.index') }}" method="GET" class="p-5">
                    <!-- Filters in a single row -->
                    <div class="flex flex-col lg:flex-row lg:space-x-4">
                        <!-- Start Date -->
                        <div class="mb-4 w-full lg:mb-0">
                            <label for="start_date" class="sr-only">Start Date</label>
                            <input type="date" id="start_date" name="start_date"
                                class="relative z-20 w-full appearance-none rounded border border-stroke bg-transparent py-3 pl-5 pr-3 outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white"
                                value="{{ request('start_date') }}">
                        </div>

                        <!-- End Date -->
                        <div class="mb-4 w-full lg:mb-0">
                            <label for="end_date" class="sr-only">End Date</label>
                            <input type="date" id="end_date" name="end_date"
                                class="relative z-20 w-full appearance-none rounded border border-stroke bg-transparent py-3 pl-5 pr-3 outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:text-white"
                                value="{{ request('end_date') }}">
                        </div>

                        <!-- Account Filter -->
                        <div class="mb-4 w-full lg:mb-0">
                            <label for="account_id" class="sr-only">Select Account</label>
                            <select id="account_id" name="account_id"
                                    class="relative z-20 w-full appearance-none rounded border border-stroke bg-transparent py-3 pl-5 pr-12 outline-none transition focus:border-primary dark:border-strokedark dark:bg-form-input dark:text-white">
                                <option value="">Select Account</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                                        {{ $account->formatted_account }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Transaction Type Filter -->
                        <div class="mb-4 w-full lg:mb-0">
                            <label for="transaction_type" class="sr-only">Transaction Type</label>
                            <select id="transaction_type" name="transaction_type"
                                    class="relative z-20 w-full appearance-none rounded border border-stroke bg-transparent py-3 pl-5 pr-12 outline-none transition focus:border-primary dark:border-strokedark dark:bg-form-input dark:text-white">
                                <option value="">Select Transaction Type</option>
                                <option value="debit" {{ request('transaction_type') == 'debit' ? 'selected' : '' }}>Debit</option>
                                <option value="credit" {{ request('transaction_type') == 'credit' ? 'selected' : '' }}>Credit</option>
                            </select>
                        </div>

                        <!-- Sorting Filter -->
                        <div class="mb-4 w-full lg:mb-0">
                            <label for="order_by" class="sr-only">Order By</label>
                            <select id="order_by" name="order_by"
                                    class="relative z-20 w-full appearance-none rounded border border-stroke bg-transparent py-3 pl-5 pr-12 outline-none transition focus:border-primary dark:border-strokedark dark:bg-form-input dark:text-white">
                                <option value="">Order By</option>
                                <option value="transaction_date" {{ request('order_by') == 'transaction_date' ? 'selected' : '' }}>Date</option>
                                <option value="credit" {{ request('order_by') == 'credit' ? 'selected' : '' }}>Credit</option>
                                <option value="debit" {{ request('order_by') == 'debit' ? 'selected' : '' }}>Debit</option>
                            </select>
                        </div>

                        <!-- Sorting Direction -->
                        <div class="mb-4 w-full lg:mb-0">
                            <label for="order_direction" class="sr-only">Order Direction</label>
                            <select id="order_direction" name="order_direction"
                                    class="relative z-20 w-full appearance-none rounded border border-stroke bg-transparent py-3 pl-5 pr-12 outline-none transition focus:border-primary dark:border-strokedark dark:bg-form-input dark:text-white">
                                <option value="asc" {{ request('order_direction') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                <option value="desc" {{ request('order_direction') == 'desc' ? 'selected' : '' }}>Descending</option>
                            </select>
                        </div>
                    </div>

                    <!-- Submit and Clear Buttons -->
                    <div class="flex justify-end items-center pt-4">
                        <a href="{{ route('web.app.transactions.index') }}" class="rounded border border-black p-3 text-center font-medium text-black hover:bg-opacity-90">Clear Filter</a>
                        <button type="submit" class="rounded bg-primary p-3 font-medium text-gray hover:bg-opacity-90 ms-3">Apply Filter</button>
                    </div>
                </form>
            </div>

            <!-- ====== Table Section Start ===== -->
            <div class="flex flex-col gap-10">
                <!-- ====== Table Start ===== -->
                <div class="mb-10 rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">

                    <div class="border-b border-stroke px-7 py-4 dark:border-strokedark">
                        <h3 class="font-medium text-black dark:text-white">Transaction Lists</h3>
                    </div>

                    <div class="w-full px-7 py-4">
                        <div class="flex justify-end items-center pb-4">
                            <a href="{{ route('web.app.transactions.create') }}" class="rounded bg-primary p-3 font-medium text-gray hover:bg-opacity-90">
                                New Transaction
                            </a>
                        </div>
                        
                        <form action="{{ request()->url() }}" method="GET" class="flex flex-col sm:flex-row items-center justify-between">
                            <div class="mb-4.5">
                                <input
                                    name="q"
                                    type="text"
                                    placeholder="Search transactions..."
                                    class="relative z-20 w-full appearance-none rounded border border-stroke bg-transparent py-3 pl-6 pr-10 outline-none focus:border-primary focus-visible:shadow-none dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                    value="{{ request('q') }}"
                                />
                            </div>
                        
                            <div class="mb-4.5">
                                <form action="{{ request()->url() }}" method="GET" class="mb-4.5">
                                    <select
                                        name="limit"
                                        onchange="this.form.submit()"
                                        class="relative z-20 w-full appearance-none rounded border border-stroke bg-transparent py-3 px-5 outline-none transition focus:border-primary active:border-primary dark:border-form-strokedark dark:bg-form-input dark:focus:border-primary"
                                    >
                                        @php
                                            $limitOptions = [10, 20, 30, 40, 50, 100];
                                        @endphp
                                        @foreach ($limitOptions as $option)
                                            <option value="{{ $option }}" {{ request('limit') == $option ? 'selected' : '' }}>
                                                {{ $option }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </div>
                        </form>   
    
                        <div class="max-w-full overflow-x-auto">
                            <table class="w-full table-auto">
                                <thead>
                                    <tr class="bg-gray-2 text-left dark:bg-meta-4">
                                        <th class="min-w-[150px] px-4 py-4 font-medium text-black dark:text-white">Actions</th>
                                        <th class="min-w-[150px] px-4 py-4 font-medium text-black dark:text-white">Account</th>
                                        <th class="min-w-[180px] px-4 py-4 font-medium text-black dark:text-white">Date</th>
                                        <th class="min-w-[150px] px-4 py-4 font-medium text-black dark:text-white">Category</th>
                                        <th class="px-4 py-4 font-medium text-black dark:text-white">Credit</th>
                                        <th class="px-4 py-4 font-medium text-black dark:text-white">Debit</th>
                                        <th class="min-w-[250px] px-4 py-4 font-medium text-black dark:text-white">Description</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    @forelse ($transactions as $transaction)
                                        <tr>
                                            <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                                <div class="flex items-center space-x-3.5">
                                                    <a href="{{ route('web.app.transactions.edit', $transaction) }}" class="hover:text-primary">
                                                        <i class='bx bx-edit'></i>
                                                    </a>
                                                    <form action="{{ route('web.app.transactions.destroy', $transaction->id) }}" method="POST" id="delete-form-{{ $transaction->id }}" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="hover:text-primary" onclick="confirmDelete({{ $transaction->id }})">
                                                            <i class='bx bx-trash'></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                                <p class="text-black dark:text-white">{{ $transaction->formatted_account }}</p>
                                            </td>
                                            <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                                <p class="text-black dark:text-white">{{ $transaction->formatted_transaction_date }}</p>
                                            </td>
                                            <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                                <p class="text-black dark:text-white">{{ $transaction->category }}</p>
                                            </td>
                                            <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark" style="text-align: right;">
                                                <p class="inline-flex rounded-full bg-opacity-10 px-3 py-1 font-medium text-success">
                                                    {{ $transaction->formatted_credit }}
                                                </p>
                                            </td>
                                            <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark" style="text-align: right;">
                                                <p class="inline-flex rounded-full bg-opacity-10 px-3 py-1 font-medium text-danger">
                                                    {{ $transaction->formatted_debit }}
                                                </p>
                                            </td>                                            
                                            <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                                <p class="text-black dark:text-white">{{ $transaction->description }}</p>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <p class="text-black dark:text-white">No transactions found.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>                    
    
                        <!-- ====== Pagination Start ===== -->
                        <div class="flex justify-between items-center p-4 sm:p-6 xl:p-7.5">
                            <div>
                                Showing {{ $transactions->firstItem() }} to {{ $transactions->lastItem() }} of {{ $transactions->total() }} entries
                            </div>
    
                            <nav>
                                <ul class="flex flex-wrap items-center gap-2">
                                    {{-- Previous Page Link --}}
                                    <li>
                                        <a href="{{ $transactions->appends(['limit' => request('limit'), 'q' => request('q'), 'account_id' => request('account_id'), 'start_date' => request('start_date'), 'end_date' => request('end_date')])->previousPageUrl() }}"
                                        class="flex items-center justify-center rounded px-3 py-1.5 text-xs font-medium {{ $transactions->onFirstPage() ? 'cursor-not-allowed bg-[#EDEFF1] dark:bg-graydark text-black dark:text-white' : 'hover:bg-primary hover:text-white dark:hover:bg-primary dark:hover:text-white' }}">
                                            Previous
                                        </a>
                                    </li>
    
                                    {{-- Pagination Elements --}}
                                    @foreach ($transactions->links()->elements as $element)
                                        @if (is_string($element))
                                            <li>
                                                <span class="flex items-center justify-center px-3 py-1.5 text-xs font-medium text-black dark:text-white">{{ $element }}</span>
                                            </li>
                                        @endif
    
                                        @if (is_array($element))
                                            @foreach ($element as $page => $url)
                                                <li>
                                                    <a href="{{ $transactions->appends(['limit' => request('limit'), 'q' => request('q'), 'account_id' => request('account_id'), 'start_date' => request('start_date'), 'end_date' => request('end_date')])->url($page) }}"
                                                    class="flex items-center justify-center rounded px-3 py-1.5 font-medium {{ $page == $transactions->currentPage() ? 'bg-primary text-white' : 'hover:bg-primary hover:text-white' }}">
                                                        {{ $page }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        @endif
                                    @endforeach
    
                                    {{-- Next Page Link --}}
                                    <li>
                                        <a href="{{ $transactions->appends(['limit' => request('limit'), 'q' => request('q'), 'account_id' => request('account_id'), 'start_date' => request('start_date'), 'end_date' => request('end_date')])->nextPageUrl() }}"
                                        class="flex items-center justify-center rounded px-3 py-1.5 text-xs font-medium {{ $transactions->hasMorePages() ? 'hover:bg-primary hover:text-white dark:hover:bg-primary dark:hover:text-white' : 'cursor-not-allowed bg-[#EDEFF1] dark:bg-graydark text-black dark:text-white' }}">
                                            Next
                                        </a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                        <!-- ====== Pagination End ===== -->
                    </div>
            
                </div>
                <!-- ====== Table End ===== -->
            </div>
            <!-- ====== Table Section End ===== -->
        </div>
    </main>
    <!-- ===== Main Content End ===== -->
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

<!-- Delete confirmation -->
<script>
    function confirmDelete(itemId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + itemId).submit();
            }
        });
    }
</script>
@endpush