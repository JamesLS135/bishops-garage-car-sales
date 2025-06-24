<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add New Car') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 dark:bg-red-800/30 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">{{ __('Whoops! Something went wrong.') }}</strong>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('cars.store') }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="vin" :value="__('VIN (Vehicle Identification Number)')" />
                                <x-text-input id="vin" class="block mt-1 w-full" type="text" name="vin" :value="old('vin')" required autofocus />
                            </div>

                            <div>
                                <x-input-label for="registration_number" :value="__('Registration Number')" />
                                <x-text-input id="registration_number" class="block mt-1 w-full" type="text" name="registration_number" :value="old('registration_number')" required />
                            </div>

                            <div>
                                <x-input-label for="make" :value="__('Make')" />
                                <x-text-input id="make" class="block mt-1 w-full" type="text" name="make" :value="old('make')" required />
                            </div>

                            <div>
                                <x-input-label for="model" :value="__('Model')" />
                                <x-text-input id="model" class="block mt-1 w-full" type="text" name="model" :value="old('model')" required />
                            </div>

                            <div>
                                <x-input-label for="year" :value="__('Year')" />
                                <x-text-input id="year" class="block mt-1 w-full" type="number" name="year" :value="old('year')" required />
                            </div>

                            <div>
                                <x-input-label for="odometer_reading" :value="__('Odometer Reading')" />
                                <x-text-input id="odometer_reading" class="block mt-1 w-full" type="number" name="odometer_reading" :value="old('odometer_reading')" required />
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="current_status" :value="__('Current Status')" />
                                <select name="current_status" id="current_status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="In Stock - Awaiting Prep">In Stock - Awaiting Prep</option>
                                    <option value="In Stock - Available">In Stock - Available</option>
                                    <option value="In Stock - Awaiting VRT/NCT">In Stock - Awaiting VRT/NCT</option>
                                    <option value="Reserved">Reserved</option>
                                    <option value="Sold">Sold</option>
                                    <option value="In Service">In Service</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('cars.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 mr-4">
                                {{ __('Cancel') }}
                            </a>
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