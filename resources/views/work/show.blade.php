<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Work Record Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">

                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Job for: {{ $workDone->car->make }} {{ $workDone->car->model }} ({{ $workDone->car->registration_number }})</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 border-t pt-4">
                        <div>
                            <span class="font-semibold text-gray-600">Date of Work:</span>
                            <p>{{ \Carbon\Carbon::parse($workDone->work_date)->format('d F Y') }}</p>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-600">Mechanic:</span>
                            <p>{{ $workDone->mechanic->name }}</p>
                        </div>
                        <div>
                            <span class="font-semibold text-gray-600">Total Cost:</span>
                            <p>€{{ number_format($workDone->cost, 2) }}</p>
                        </div>
                    </div>

                    <div class="border-t pt-4">
                        <span class="font-semibold text-gray-600">Description of Work:</span>
                        <p class="mt-1">{{ $workDone->description }}</p>
                    </div>

                    <div class="border-t pt-4">
                        <span class="font-semibold text-gray-600">Parts Used:</span>
                        @if($workDone->parts_used)
                            <div class="mt-1 prose max-w-none">
                                {!! nl2br(e($workDone->parts_used)) !!}
                            </div>
                        @else
                            <p class="mt-1 text-gray-500">No parts listed for this job.</p>
                        @endif
                    </div>
                    
                    <div class="border-t pt-6 text-right">
                         <a href="{{ route('cars.edit', $workDone->car_id) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Return to Car
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
