<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add New Car') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    @if ($errors->any())
                        <div class="mb-4">
                            <div class="font-medium text-red-600">{{ __('Whoops! Something went wrong.') }}</div>
                            <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('cars.store') }}">
                        @csrf

                        <!-- VIN -->
                        <div>
                            <x-input-label for="vin" :value="__('VIN (Vehicle Identification Number)')" />
                            <x-text-input id="vin" class="block mt-1 w-full" type="text" name="vin" :value="old('vin')" required autofocus />
                        </div>

                        <!-- Registration Number -->
                        <div class="mt-4">
                            <x-input-label for="registration_number" :value="__('Registration Number')" />
                            <x-text-input id="registration_number" class="block mt-1 w-full" type="text" name="registration_number" :value="old('registration_number')" required />
                        </div>

                        <!-- Make -->
                        <div class="mt-4">
                            <x-input-label for="make" :value="__('Make')" />
                            <x-text-input id="make" class="block mt-1 w-full" type="text" name="make" :value="old('make')" required />
                        </div>

                        <!-- Model -->
                        <div class="mt-4">
                            <x-input-label for="model" :value="__('Model')" />
                            <x-text-input id="model" class="block mt-1 w-full" type="text" name="model" :value="old('model')" required />
                        </div>

                        <!-- Year -->
                        <div class="mt-4">
                            <x-input-label for="year" :value="__('Year')" />
                            <x-text-input id="year" class="block mt-1 w-full" type="number" name="year" :value="old('year')" required />
                        </div>

                        <!-- Odometer -->
                        <div class="mt-4">
                            <x-input-label for="odometer_reading" :value="__('Odometer Reading')" />
                            <x-text-input id="odometer_reading" class="block mt-1 w-full" type="number" name="odometer_reading" :value="old('odometer_reading')" required />
                        </div>

                        <!-- Current Status -->
                        <div class="mt-4">
                            <x-input-label for="current_status" :value="__('Current Status')" />
                            <select name="current_status" id="current_status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="In Stock - Awaiting Prep">In Stock - Awaiting Prep</option>
                                <option value="In Stock - Available">In Stock - Available</option>
                                <option value="In Stock - Awaiting VRT/NCT">In Stock - Awaiting VRT/NCT</option>
                                <option value="Reserved">Reserved</option>
                                <option value="Sold">Sold</option>
                                <option value="In Service">In Service</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('cars.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                            <x-primary-button>
                                {{ __('Save Car') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
