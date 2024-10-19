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
                    Account Lists
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

            <!-- ====== Table Section Start ===== -->
            <div class="flex flex-col gap-10">
                
                <!-- ====== Table Start ===== -->
                <div class="rounded-sm border border-stroke bg-white px-5 pb-2.5 pt-6 shadow-default dark:border-strokedark dark:bg-boxdark sm:px-7.5 xl:pb-1">
                    
                    <div class="flex justify-end items-center pb-4">
                        <a href="{{ route('web.app.accounts.create') }}" class="rounded bg-primary p-3 font-medium text-gray hover:bg-opacity-90">
                            New Account
                        </a>
                    </div>
                    
                    <form action="{{ request()->url() }}" method="GET" class="flex flex-col sm:flex-row items-center justify-between">
                        <div class="mb-4.5">
                            <input
                                name="q"
                                type="text"
                                placeholder="Search accounts..."
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
                                    <th class="min-w-[150px] px-4 py-4 font-medium text-black dark:text-white">Account Name</th>
                                    <th class="min-w-[150px] px-4 py-4 font-medium text-black dark:text-white">Brand</th>
                                    <th class="min-w-[150px] px-4 py-4 font-medium text-black dark:text-white">Number</th>
                                    <th class="min-w-[150px] px-4 py-4 font-medium text-black dark:text-white">Balance</th>
                                    <th class="min-w-[150px] px-4 py-4 font-medium text-black dark:text-white">Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($accounts as $account)
                                    <tr>
                                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                            <div class="flex items-center space-x-3.5">
                                                <!-- Delete Item -->
                                                <form action="{{ route('web.app.accounts.destroy', $account->id) }}" method="POST" id="delete-form-{{ $account->id }}" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="hover:text-primary" onclick="confirmDelete({{ $account->id }})">
                                                        <i class='bx bx-trash'></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                            <p class="text-black dark:text-white">{{ $account->account_name }}</p>
                                        </td>
                                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                            <p class="text-black dark:text-white">{{ $account->brand }}</p>
                                        </td>
                                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                            <p class="text-black dark:text-white">{{ $account->account_number }}</p>
                                        </td>
                                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                            <!-- Inline Balance and Toggle Button -->
                                            <span class="flex items-center">
                                                <span id="balance-{{ $account->id }}" class="text-black dark:text-white">*****</span>
                                                <button onclick="toggleBalance({{ $account->id }}, {{ $account->calculate_balance() }})" class="ml-2 hover:text-blue-700">
                                                    <i id="toggle-icon-{{ $account->id }}" class='bx bx-show'></i>
                                                </button>
                                            </span>
                                        </td>
                                        <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                            <p class="text-black dark:text-white">{{ $account->account_type }}</p>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <p class="text-black dark:text-white">No accounts found.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>                                        

                    <!-- ====== Pagination Start ===== -->
                    <div class="flex justify-between items-center p-4 sm:p-6 xl:p-7.5">
                        <div>
                            Showing {{ $accounts->firstItem() }} to {{ $accounts->lastItem() }} of {{ $accounts->total() }} entries
                        </div>

                        <nav>
                            <ul class="flex flex-wrap items-center gap-2">
                                {{-- Previous Page Link --}}
                                <li>
                                    <a href="{{ $accounts->appends(['limit' => $perPage, 'q' => $q])->previousPageUrl() }}"
                                    class="flex items-center justify-center rounded px-3 py-1.5 text-xs font-medium {{ $accounts->onFirstPage() ? 'cursor-not-allowed bg-[#EDEFF1] dark:bg-graydark text-black dark:text-white' : 'hover:bg-primary hover:text-white dark:hover:bg-primary dark:hover:text-white' }}">
                                        Previous
                                    </a>
                                </li>

                                {{-- Pagination Elements --}}
                                @foreach ($accounts->links()->elements as $element)
                                    @if (is_string($element))
                                        <li>
                                            <span class="flex items-center justify-center px-3 py-1.5 text-xs font-medium text-black dark:text-white">{{ $element }}</span>
                                        </li>
                                    @endif

                                    @if (is_array($element))
                                        @foreach ($element as $page => $url)
                                            <li>
                                                <a href="{{ $accounts->appends(['limit' => $perPage, 'q' => $q])->url($page) }}"
                                                class="flex items-center justify-center rounded px-3 py-1.5 font-medium {{ $page == $accounts->currentPage() ? 'bg-primary text-white' : 'hover:bg-primary hover:text-white' }}">
                                                    {{ $page }}
                                                </a>
                                            </li>
                                        @endforeach
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                <li>
                                    <a href="{{ $accounts->appends(['limit' => $perPage, 'q' => $q])->nextPageUrl() }}"
                                    class="flex items-center justify-center rounded px-3 py-1.5 text-xs font-medium {{ $accounts->hasMorePages() ? 'hover:bg-primary hover:text-white dark:hover:bg-primary dark:hover:text-white' : 'cursor-not-allowed bg-[#EDEFF1] dark:bg-graydark text-black dark:text-white' }}">
                                        Next
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <!-- ====== Pagination End ===== -->
            
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

<script>
    function toggleBalance(accountId, balance) {
        const balanceElement = document.getElementById('balance-' + accountId);
        const iconElement = document.getElementById('toggle-icon-' + accountId);
        const currentText = balanceElement.textContent;

        if (currentText.includes('*****')) {
            // Show the actual balance
            balanceElement.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(balance);
            // Switch icon to 'bx-hide'
            iconElement.classList.remove('bx-show');
            iconElement.classList.add('bx-hide');
        } else {
            // Hide the balance with *****
            balanceElement.textContent = '*****';
            // Switch icon to 'bx-show'
            iconElement.classList.remove('bx-hide');
            iconElement.classList.add('bx-show');
        }
    }
</script>
@endpush