<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Record Sale for: ') }} {{ $car->make }} {{ $car->model }} ({{ $car->registration_number }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
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

                    <form method="POST" action="{{ route('sales.store', $car->id) }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Sale Date -->
                            <div>
                                <x-input-label for="sale_date" :value="__('Sale Date')" />
                                <x-text-input id="sale_date" class="block mt-1 w-full" type="date" name="sale_date" :value="old('sale_date', now()->toDateString())" required />
                            </div>

                            <!-- Selling Price -->
                            <div>
                                <x-input-label for="selling_price" :value="__('Selling Price (€)')" />
                                <x-text-input id="selling_price" class="block mt-1 w-full" type="number" step="0.01" name="selling_price" :value="old('selling_price')" required />
                            </div>
                            
                            <!-- Customer Dropdown -->
                            <div>
                                <x-input-label for="customer_id" :value="__('Customer')" />
                                <select name="customer_id" id="customer_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="">-- Select a Customer --</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->customer_id }}" @selected(old('customer_id') == $customer->customer_id)>
                                            {{ $customer->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Odometer at Sale -->
                            <div>
                                <x-input-label for="odometer_at_sale" :value="__('Odometer at Sale')" />
                                <x-text-input id="odometer_at_sale" class="block mt-1 w-full" type="number" name="odometer_at_sale" :value="old('odometer_at_sale', $car->odometer_reading)" required />
                            </div>

                             <!-- Warranty Details -->
                             <div class="md:col-span-2">
                                <x-input-label for="warranty_details" :value="__('Warranty Details')" />
                                <textarea id="warranty_details" name="warranty_details" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="3">{{ old('warranty_details') }}</textarea>
                            </div>
                        </div>

                        <!-- Part-Exchange Section -->
                        <div class="mt-6 border-t pt-6">
                             <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Part-Exchange (Optional)</h3>
                             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="part_exchange_car_id" :value="__('Part-Exchange Vehicle')" />
                                    <select name="part_exchange_car_id" id="part_exchange_car_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="">No Part-Exchange</option>
                                        @foreach($partExchangeCars as $pxCar)
                                            <option value="{{ $pxCar->id }}" @selected(old('part_exchange_car_id') == $pxCar->id)>
                                                {{ $pxCar->registration_number }} - {{ $pxCar->make }} {{ $pxCar->model }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="part_exchange_value" :value="__('Part-Exchange Value (€)')" />
                                    <x-text-input id="part_exchange_value" class="block mt-1 w-full" type="number" step="0.01" name="part_exchange_value" :value="old('part_exchange_value')" />
                                </div>
                             </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('cars.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline">Cancel</a>
                            <x-primary-button>
                                {{ __('Complete Sale') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
