@extends('layouts.app')

@push('top-scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div class="w-full p-4 md:p-6 2xl:p-10">
    <div class="flex flex-col gap-9">
        <div class="rounded-sm border border-stroke bg-white shadow-default dark:border-strokedark dark:bg-boxdark">
            <div class="border-b border-stroke px-6.5 py-4 dark:border-strokedark">
                <h3 class="font-medium text-black dark:text-white">Edit Life Goal</h3>
            </div>
            <form action="{{ route('web.app.life.goals.update', $lifeGoal->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-6.5">
                    <div class="mb-4.5">
                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                            Goal Name <span class="text-meta-1">*</span>
                        </label>
                        <input type="text" name="goal_name" placeholder="Enter your goal name" 
                               class="w-full rounded border-[1.5px] {{ $errors->has('goal_name') ? 'border-danger' : 'border-stroke' }} bg-transparent px-5 py-3 outline-none focus:border-primary dark:border-strokedark dark:bg-form-input dark:focus:border-primary" 
                               value="{{ old('goal_name', $lifeGoal->goal_name) }}" required />
                        @error('goal_name')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4.5">
                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                            Current Amount
                        </label>
                        <input type="text" id="current_amount" placeholder="Enter current amount" 
                               class="w-full rounded border-[1.5px] {{ $errors->has('current_amount') ? 'border-danger' : 'border-stroke' }} bg-transparent px-5 py-3 outline-none focus:border-primary dark:border-strokedark dark:bg-form-input dark:focus:border-primary" 
                               name="current_amount" value="{{ old('current_amount', $lifeGoal->current_amount ?? 0) }}" />
                        @error('current_amount')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4.5">
                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                            Target Amount <span class="text-meta-1">*</span>
                        </label>
                        <input type="text" id="target_amount" placeholder="Enter target amount" 
                               class="w-full rounded border-[1.5px] {{ $errors->has('target_amount') ? 'border-danger' : 'border-stroke' }} bg-transparent px-5 py-3 outline-none focus:border-primary dark:border-strokedark dark:bg-form-input dark:focus:border-primary" 
                               name="target_amount" value="{{ old('target_amount', $lifeGoal->target_amount) }}" required />
                        @error('target_amount')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4.5">
                        <label class="mb-3 block text-sm font-medium text-black dark:text-white">
                            Deadline <span class="text-meta-1">*</span>
                        </label>
                        <input type="date" name="deadline" 
                               class="w-full rounded border-[1.5px] {{ $errors->has('deadline') ? 'border-danger' : 'border-stroke' }} bg-transparent px-5 py-3 outline-none focus:border-primary dark:border-strokedark dark:bg-form-input dark:focus:border-primary" 
                               value="{{ old('deadline', $lifeGoal->formatted_deadline_for_edit_blade) }}" required />
                        @error('deadline')
                            <span class="text-danger text-sm">{{ $message }}</span>
                        @enderror
                    </div>

                    @if(session('toast_message'))
                        {{ dd(session()->all()) }}
                    @endif

                    <button class="flex w-full justify-center rounded bg-primary p-3 font-medium text-gray hover:bg-opacity-90">
                        Update Goal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('bottom-scripts')
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
    
        const targetAmountInput = document.getElementById('target_amount');
        const currentAmountInput = document.getElementById('current_amount');
    
        if (targetAmountInput.value) {
            targetAmountInput.value = formatCurrency(targetAmountInput.value);
        }
    
        if (currentAmountInput.value) {
            currentAmountInput.value = formatCurrency(currentAmountInput.value);
        }
    
        targetAmountInput.addEventListener('keyup', function(e) {
            this.value = formatCurrency(unformatCurrency(this.value));
        });
    
        currentAmountInput.addEventListener('keyup', function(e) {
            this.value = formatCurrency(unformatCurrency(this.value));
        });
    </script>
@endpush