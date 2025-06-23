<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Supplier') }}: {{ $supplier->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if ($errors->any())
                        <div class="mb-4 text-sm text-red-600">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.suppliers.update', $supplier->supplier_id) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" :value="__('Supplier Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $supplier->name)" required autofocus />
                        </div>
                        
                        <div>
                            <x-input-label for="contact_person" :value="__('Contact Person')" />
                            <x-text-input id="contact_person" name="contact_person" type="text" class="mt-1 block w-full" :value="old('contact_person', $supplier->contact_person)" />
                        </div>

                        <div>
                            <x-input-label for="phone" :value="__('Phone')" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $supplier->phone)" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $supplier->email)" />
                        </div>

                        <div>
                            <x-input-label for="address" :value="__('Address')" />
                            <textarea id="address" name="address" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('address', $supplier->address) }}</textarea>
                        </div>
                        
                        <div>
                            <x-input-label for="notes" :value="__('Notes')" />
                            <textarea id="notes" name="notes" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('notes', $supplier->notes) }}</textarea>
                        </div>


                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Update Supplier') }}</x-primary-button>
                            <a href="{{ route('admin.suppliers.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
