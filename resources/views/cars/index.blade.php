<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Car Inventory') }}
            </h2>
            <a href="{{ route('cars.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Add New Car') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <form action="{{ route('cars.index') }}" method="GET">
                            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                <div>
                                    <x-input-label for="make" :value="__('Make')" />
                                    <x-text-input id="make" name="make" type="text" class="mt-1 block w-full" placeholder="e.g. Ford" :value="request('make')" />
                                </div>
                                <div>
                                    <x-input-label for="model" :value="__('Model')" />
                                    <x-text-input id="model" name="model" type="text" class="mt-1 block w-full" placeholder="e.g. Focus" :value="request('model')" />
                                </div>
                                <div>
                                    <x-input-label for="year" :value="__('Year')" />
                                    <x-text-input id="year" name="year" type="number" class="mt-1 block w-full" placeholder="e.g. 2021" :value="request('year')" />
                                </div>
                                <div class="flex items-end space-x-2">
                                    <x-primary-button class="w-full justify-center">
                                        {{ __('Filter') }}
                                    </x-primary-button>
                                    <a href="{{ route('cars.index') }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                                        Clear
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded relative" role="alert">
                            <strong class="font-bold">Success!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                     @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded relative" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Photo</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Reg Number</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Make & Model</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Year</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($cars as $car)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $primaryImage = $car->images->where('is_primary', true)->first() ?? $car->images->first();
                                            @endphp
                                            @if($primaryImage)
                                                <img src="{{ $primaryImage->url }}" alt="Car Photo" class="w-20 h-14 object-cover rounded" />
                                            @else
                                                <span class="text-xs text-gray-400">No Image</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $car->registration_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $car->make }} {{ $car->model }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $car->year }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($car->current_status == 'Sold') bg-red-100 text-red-800 @else bg-blue-100 text-blue-800 @endif">
                                                {{ $car->current_status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('cars.edit', $car->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 mr-4">Edit Car</a>
                                            @if(!$car->purchase)
                                                <a href="{{ route('purchases.create', $car->id) }}" class="text-green-600 dark:text-green-400 hover:text-green-900">Add Purchase</a>
                                            @endif
                                            @if($car->purchase && !$car->sale && $car->current_status != 'Reserved')
                                                <a href="{{ route('sales.create', $car->id) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 ml-4">Sell Car</a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">No cars found matching your search.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $cars->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>