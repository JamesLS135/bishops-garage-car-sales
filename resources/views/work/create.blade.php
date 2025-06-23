<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Work Record for: ') }} {{ $car->make }} {{ $car->model }} ({{ $car->registration_number }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            <div class="font-bold">{{ __('Whoops! Something went wrong.') }}</div>
                            <ul class="mt-3 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('work.store', $car->id) }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Work Date -->
                            <div>
                                <x-input-label for="work_date" :value="__('Date of Work')" />
                                <x-text-input id="work_date" class="block mt-1 w-full" type="date" name="work_date" :value="old('work_date', now()->toDateString())" required />
                            </div>

                            <!-- Cost -->
                            <div>
                                <x-input-label for="cost" :value="__('Cost of Work (€)')" />
                                <x-text-input id="cost" class="block mt-1 w-full" type="number" step="0.01" name="cost" :value="old('cost', '0.00')" required />
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <x-input-label for="description" :value="__('Description of Work')" />
                                <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4" required>{{ old('description') }}</textarea>
                            </div>
                            
                            <!-- Parts Used -->
                            <div class="md:col-span-2">
                                <x-input-label for="parts_used" :value="__('Parts Used (Optional)')" />
                                <textarea id="parts_used" name="parts_used" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="3">{{ old('parts_used') }}</textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('cars.edit', $car->id) }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                            <x-primary-button>
                                {{ __('Save Work Record') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
