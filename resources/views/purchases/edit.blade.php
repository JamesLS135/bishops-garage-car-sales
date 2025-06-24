<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Purchase Details for: ') }} {{ $purchase->car->make }} {{ $purchase->car->model }} ({{ $purchase->car->registration_number }})
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900/50 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 rounded-lg">
                            <div class="font-bold">{{ __('Whoops! Something went wrong.') }}</div>
                            <ul class="mt-3 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('purchases.update', $purchase->purchase_id) }}">
                        @csrf
                        @method('PUT')

                        {{-- Main Purchase Details --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="purchase_date" :value="__('Purchase Date')" />
                                <x-text-input id="purchase_date" class="block mt-1 w-full" type="date" name="purchase_date" :value="old('purchase_date', $purchase->purchase_date->format('Y-m-d'))" required />
                            </div>
                            <div>
                                <x-input-label for="supplier_id" :value="__('Supplier')" />
                                <select name="supplier_id" id="supplier_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->supplier_id }}" @selected(old('supplier_id', $purchase->supplier_id) == $supplier->supplier_id)>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="purchase_price" :value="__('Purchase Price (€)')" />
                                <x-text-input id="purchase_price" class="block mt-1 w-full" type="number" step="0.01" name="purchase_price" :value="old('purchase_price', $purchase->purchase_price)" required />
                            </div>
                            <div>
                                <x-input-label for="odometer_at_purchase" :value="__('Odometer at Purchase')" />
                                <x-text-input id="odometer_at_purchase" class="block mt-1 w-full" type="number" name="odometer_at_purchase" :value="old('odometer_at_purchase', $purchase->odometer_at_purchase)" required />
                            </div>
                            <div class="md:col-span-2">
                                <x-input-label for="purchase_invoice_reference" :value="__('Purchase Invoice Reference')" />
                                <x-text-input id="purchase_invoice_reference" class="block mt-1 w-full" type="text" name="purchase_invoice_reference" :value="old('purchase_invoice_reference', $purchase->purchase_invoice_reference)" />
                            </div>
                        </div>

                        {{-- Imported Vehicle Section --}}
                        <div class="mt-6 border-t dark:border-gray-700 pt-6">
                            <label for="is_imported_vehicle" class="inline-flex items-center">
                                <input id="is_imported_vehicle" type="checkbox" name="is_imported_vehicle" class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600" @checked(old('is_imported_vehicle', $purchase->is_imported_vehicle))>
                                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Is this an imported vehicle?') }}</span>
                            </label>
                        </div>
                        
                        {{-- NEW: Registration Change Section --}}
                        @if($purchase->is_imported_vehicle)
                        <div class="mt-6 border-t border-dashed dark:border-gray-700 pt-6">
                             <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-1">Registration Change</h3>
                             <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                 Enter the new Irish registration number here. The old registration ({{ $purchase->original_registration_number ?? $purchase->car->registration_number }}) will be saved for your records.
                             </p>
                             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                 <div>
                                     <x-input-label for="new_registration_number" :value="__('New Irish Registration Number')" />
                                     <x-text-input id="new_registration_number" class="block mt-1 w-full" type="text" name="new_registration_number" :value="old('new_registration_number')" />
                                 </div>
                                 <div>
                                     <x-input-label for="registration_change_date" :value="__('Date of Change')" />
                                     <x-text-input id="registration_change_date" class="block mt-1 w-full" type="date" name="registration_change_date" :value="old('registration_change_date', $purchase->registration_change_date?->format('Y-m-d'))" />
                                 </div>
                             </div>
                             @if($purchase->original_registration_number)
                                 <p class="mt-4 text-sm text-gray-500">Original Reg: <span class="font-semibold">{{ $purchase->original_registration_number }}</span> (changed on {{ $purchase->registration_change_date?->format('d/m/Y') }})</p>
                             @endif
                        </div>
                        @endif


                        {{-- Buttons --}}
                        <div class="flex items-center justify-end mt-6 pt-6 border-t dark:border-gray-700">
                            <a href="{{ route('cars.edit', $purchase->car_id) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline">Cancel</a>
                            <x-primary-button class="ms-4">
                                {{ __('Update Purchase Details') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>