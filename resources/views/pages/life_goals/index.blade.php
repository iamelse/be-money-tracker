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
                    Life Goals
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

            <!-- ====== Table Section Start ===== -->
            <div class="flex flex-col gap-10">
                <!-- ====== Table Start ===== -->
                <div class="mb-10 rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">

                    <div class="border-b border-stroke px-7 py-4 dark:border-strokedark">
                        <h3 class="font-medium text-black dark:text-white">Life Goal Lists</h3>
                    </div>

                    <div class="w-full px-7 py-4">
                        <div class="flex justify-end items-center pb-4">
                            <a href="{{ route('web.app.life.goals.create') }}" class="rounded bg-primary p-3 font-medium text-gray hover:bg-opacity-90">
                                New Life Goal
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
                                        <th class="min-w-[150px] px-4 py-4 font-medium text-black dark:text-white">Goal Name</th>
                                        <th class="min-w-[150px] px-4 py-4 font-medium text-black dark:text-white">Current Amount</th>
                                        <th class="min-w-[180px] px-4 py-4 font-medium text-black dark:text-white">Target Amount</th>
                                        <th class="min-w-[150px] px-4 py-4 font-medium text-black dark:text-white">Progress</th>
                                        <th class="min-w-[250px] px-4 py-4 font-medium text-black dark:text-white">Deadline</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    @forelse ($lifeGoals as $lifeGoal)
                                        <tr>
                                            <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                                <div class="flex items-center space-x-3.5">
                                                    <a href="{{ route('web.app.life.goals.edit', $lifeGoal) }}" class="hover:text-primary">
                                                        <i class='bx bx-edit'></i>
                                                    </a>
                                                    <form action="{{ route('web.app.life.goals.destroy', $lifeGoal->id) }}" method="POST" id="delete-form-{{ $lifeGoal->id }}" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="hover:text-primary" onclick="confirmDelete({{ $lifeGoal->id }})">
                                                            <i class='bx bx-trash'></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                            <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                                <p class="text-black dark:text-white">{{ $lifeGoal->goal_name }}</p>
                                            </td>
                                            <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                                <p class="text-black dark:text-white">{{ $lifeGoal->formatted_current_amount }}</p>
                                            </td>
                                            <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                                <p class="text-black dark:text-white">{{ $lifeGoal->formatted_target_amount }}</p>
                                            </td> 
                                            <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                                <div class="flex items-center gap-3">
                                                    <div class="sm:max-w-[281px] relative w-full h-2 rounded bg-gray-200 dark:bg-gray-800">
                                                        <div class="absolute left-0 h-full bg-primary rounded"
                                                            style="width: {{ $lifeGoal->progress }};">
                                                        </div>
                                                    </div>
                                                    
                                                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-400">
                                                        {{ $lifeGoal->progress }}
                                                    </span>
                                                </div>
                                            </td>                                         
                                            <td class="border-b border-[#eee] px-4 py-5 dark:border-strokedark">
                                                <p class="text-black dark:text-white">{{ $lifeGoal->deadline }}</p>
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
                                Showing {{ $lifeGoals->firstItem() }} to {{ $lifeGoals->lastItem() }} of {{ $lifeGoals->total() }} entries
                            </div>
    
                            <nav>
                                <ul class="flex flex-wrap items-center gap-2">
                                    {{-- Previous Page Link --}}
                                    <li>
                                        <a href="{{ $lifeGoals->appends(['limit' => request('limit'), 'q' => request('q'), 'account_id' => request('account_id'), 'start_date' => request('start_date'), 'end_date' => request('end_date')])->previousPageUrl() }}"
                                        class="flex items-center justify-center rounded px-3 py-1.5 text-xs font-medium {{ $lifeGoals->onFirstPage() ? 'cursor-not-allowed bg-[#EDEFF1] dark:bg-graydark text-black dark:text-white' : 'hover:bg-primary hover:text-white dark:hover:bg-primary dark:hover:text-white' }}">
                                            Previous
                                        </a>
                                    </li>
    
                                    {{-- Pagination Elements --}}
                                    @foreach ($lifeGoals->links()->elements as $element)
                                        @if (is_string($element))
                                            <li>
                                                <span class="flex items-center justify-center px-3 py-1.5 text-xs font-medium text-black dark:text-white">{{ $element }}</span>
                                            </li>
                                        @endif
    
                                        @if (is_array($element))
                                            @foreach ($element as $page => $url)
                                                <li>
                                                    <a href="{{ $lifeGoals->appends(['limit' => request('limit'), 'q' => request('q'), 'account_id' => request('account_id'), 'start_date' => request('start_date'), 'end_date' => request('end_date')])->url($page) }}"
                                                    class="flex items-center justify-center rounded px-3 py-1.5 font-medium {{ $page == $lifeGoals->currentPage() ? 'bg-primary text-white' : 'hover:bg-primary hover:text-white' }}">
                                                        {{ $page }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        @endif
                                    @endforeach
    
                                    {{-- Next Page Link --}}
                                    <li>
                                        <a href="{{ $lifeGoals->appends(['limit' => request('limit'), 'q' => request('q'), 'account_id' => request('account_id'), 'start_date' => request('start_date'), 'end_date' => request('end_date')])->nextPageUrl() }}"
                                        class="flex items-center justify-center rounded px-3 py-1.5 text-xs font-medium {{ $lifeGoals->hasMorePages() ? 'hover:bg-primary hover:text-white dark:hover:bg-primary dark:hover:text-white' : 'cursor-not-allowed bg-[#EDEFF1] dark:bg-graydark text-black dark:text-white' }}">
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