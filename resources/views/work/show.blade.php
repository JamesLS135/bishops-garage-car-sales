<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Work Record Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Header for the Job --}}
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                            Job for: {{ $workDone->car->make }} {{ $workDone->car->model }} ({{ $workDone->car->registration_number }})
                        </h3>
                    </div>

                    {{-- Details Grid --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date of Work</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $workDone->work_date->format('d F Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Mechanic</dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $workDone->mechanic->name ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Cost</dt>
                            <dd class="mt-1 text-sm font-semibold text-red-600 dark:text-red-400">€{{ number_format($workDone->cost, 2) }}</dd>
                        </div>
                    </div>

                    {{-- Description Section --}}
                    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description of Work</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 prose dark:prose-invert max-w-none">
                             {!! nl2br(e($workDone->description)) !!}
                        </dd>
                    </div>

                    {{-- Parts Used Section --}}
                    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Parts Used</dt>
                        <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 prose dark:prose-invert max-w-none">
                            @if($workDone->parts_used)
                                {!! nl2br(e($workDone->parts_used)) !!}
                            @else
                                <p class="text-gray-500 dark:text-gray-400">No parts were listed for this job.</p>
                            @endif
                        </dd>
                    </div>
                    
                    {{-- Return Button --}}
                    <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6 flex justify-end">
                         <a href="{{ route('cars.edit', $workDone->car_id) }}">
                             <x-primary-button>
                                 {{ __('Return to Car') }}
                             </x-primary-button>
                         </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>