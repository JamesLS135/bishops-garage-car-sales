<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Purchase Details for: ') }} {{ $car->make }} {{ $car->model }} ({{ $car->registration_number }})
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

                    <form method="POST" action="{{ route('purchases.store', $car->id) }}">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Purchase Date -->
                            <div>
                                <x-input-label for="purchase_date" :value="__('Purchase Date')" />
                                <x-text-input id="purchase_date" class="block mt-1 w-full" type="date" name="purchase_date" :value="old('purchase_date')" required />
                            </div>

                            <!-- Supplier Dropdown -->
                            <div>
                                <x-input-label for="supplier_id" :value="__('Supplier')" />
                                <select name="supplier_id" id="supplier_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="">-- Select a Supplier --</option>
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->supplier_id }}" @selected(old('supplier_id') == $supplier->supplier_id)>
                                            {{ $supplier->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Purchase Price -->
                            <div>
                                <x-input-label for="purchase_price" :value="__('Purchase Price (€)')" />
                                <x-text-input id="purchase_price" class="block mt-1 w-full" type="number" step="0.01" name="purchase_price" :value="old('purchase_price')" required />
                            </div>

                            <!-- Odometer at Purchase -->
                            <div>
                                <x-input-label for="odometer_at_purchase" :value="__('Odometer at Purchase')" />
                                <x-text-input id="odometer_at_purchase" class="block mt-1 w-full" type="number" name="odometer_at_purchase" :value="old('odometer_at_purchase')" required />
                            </div>

                             <!-- Purchase Invoice Reference -->
                             <div class="md:col-span-2">
                                <x-input-label for="purchase_invoice_reference" :value="__('Purchase Invoice Reference')" />
                                <x-text-input id="purchase_invoice_reference" class="block mt-1 w-full" type="text" name="purchase_invoice_reference" :value="old('purchase_invoice_reference')" />
                            </div>
                        </div>

                        <!-- Imported Vehicle Toggle -->
                        <div class="mt-6 border-t pt-6">
                            <label for="is_imported_vehicle" class="inline-flex items-center">
                                <input id="is_imported_vehicle" type="checkbox" name="is_imported_vehicle" class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600" {{ old('is_imported_vehicle') ? 'checked' : '' }} onchange="toggleImportFields()">
                                <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Is this an imported vehicle?') }}</span>
                            </label>
                        </div>
                        
                        <!-- Import-Specific Fields -->
                        <div id="import-fields" class="mt-6 border-t pt-6 border-dashed hidden">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Import Costs</h3>
                             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="vrt_paid_amount" :value="__('VRT Paid (€)')" />
                                    <x-text-input id="vrt_paid_amount" class="block mt-1 w-full" type="number" step="0.01" name="vrt_paid_amount" :value="old('vrt_paid_amount')" />
                                </div>
                                <div>
                                    <x-input-label for="vrt_payment_date" :value="__('VRT Payment Date')" />
                                    <x-text-input id="vrt_payment_date" class="block mt-1 w-full" type="date" name="vrt_payment_date" :value="old('vrt_payment_date')" />
                                </div>
                                <div>
                                    <x-input-label for="transport_cost_import" :value="__('Transport Cost (€)')" />
                                    <x-text-input id="transport_cost_import" class="block mt-1 w-full" type="number" step="0.01" name="transport_cost_import" :value="old('transport_cost_import')" />
                                </div>
                                <div>
                                    <x-input-label for="import_duty_amount" :value="__('Import Duty (€)')" />
                                    <x-text-input id="import_duty_amount" class="block mt-1 w-full" type="number" step="0.01" name="import_duty_amount" :value="old('import_duty_amount')" />
                                </div>
                             </div>
                        </div>
                        
                        <!-- Other Expenses & Notes -->
                        <div class="mt-6 border-t pt-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="other_expenses_total" :value="__('Other Prep Expenses (€)')" />
                                    <x-text-input id="other_expenses_total" class="block mt-1 w-full" type="number" step="0.01" name="other_expenses_total" :value="old('other_expenses_total')" />
                                </div>
                                <div class="md:col-span-2">
                                    <x-input-label for="purchase_notes" :value="__('Purchase Notes')" />
                                    <textarea id="purchase_notes" name="purchase_notes" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" rows="3">{{ old('purchase_notes') }}</textarea>
                                </div>
                            </div>
                        </div>


                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('cars.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline">Cancel</a>
                            <x-primary-button>
                                {{ __('Save Purchase Details') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function toggleImportFields() {
            const checkbox = document.getElementById('is_imported_vehicle');
            const fields = document.getElementById('import-fields');
            if (checkbox.checked) {
                fields.classList.remove('hidden');
            } else {
                fields.classList.add('hidden');
            }
        }
        // Run on page load in case of validation errors
        document.addEventListener('DOMContentLoaded', toggleImportFields);
    </script>
</x-app-layout>
