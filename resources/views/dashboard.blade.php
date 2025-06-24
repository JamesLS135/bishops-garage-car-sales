<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if($alerts->isNotEmpty())
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium text-red-600 dark:text-red-400">Urgent Alerts</h3>
                    <div class="mt-4 space-y-3">
                        @foreach($alerts as $alert)
                            @php
                                $levelClasses = match($alert->level) {
                                    'danger' => 'bg-red-100 dark:bg-red-800/30 border-red-500 dark:border-red-600 text-red-700 dark:text-red-300',
                                    'warning' => 'bg-yellow-100 dark:bg-yellow-800/30 border-yellow-500 dark:border-yellow-600 text-yellow-700 dark:text-yellow-300',
                                    default => 'bg-gray-100 dark:bg-gray-700 border-gray-500'
                                };
                            @endphp
                            <a href="{{ route('cars.edit', $alert->car_id) }}" class="block p-4 rounded-lg border-l-4 hover:bg-opacity-80 {{ $levelClasses }}">
                                <div class="flex items-center">
                                    <svg class="h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                    </svg>
                                    <p class="font-medium">{{ $alert->message }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <a href="{{ route('admin.reports.sales-history') }}" class="block p-4 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Sales (This Month)</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $salesThisMonth }}</p>
                    </a>

                    <a href="{{ route('admin.reports.profitability') }}" class="block p-4 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Profit (This Month)</h3>
                        <p class="mt-1 text-3xl font-semibold text-green-600">€{{ number_format($profitThisMonth, 2) }}</p>
                    </a>

                    <a href="{{ route('cars.index', ['status' => 'In Stock - Awaiting Prep']) }}" class="block p-4 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Cars Awaiting Prep</h3>
                        <p class="mt-1 text-3xl font-semibold text-yellow-600">{{ $carsAwaitingPrepCount }}</p>
                    </a>

                    <div class="p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Current Stock</h3>
                        <div class="mt-2 space-y-1">
                               @forelse($stockCounts as $status => $total)
                                   <a href="{{ route('cars.index', ['status' => $status]) }}" class="flex justify-between text-sm hover:bg-gray-100 dark:hover:bg-gray-700 p-1 rounded-md">
                                       <span class="text-gray-600 dark:text-gray-300">{{ $status }}</span>
                                       <span class="font-medium text-gray-900 dark:text-gray-100">{{ $total }}</span>
                                   </a>
                               @empty
                                   <p class="text-sm text-gray-500 dark:text-gray-400">No cars in stock.</p>
                               @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium">Available for Sale</h3>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Car</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Registration</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Odometer</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($availableCars as $car)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('cars.edit', $car->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                {{ $car->make }} {{ $car->model }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $car->registration_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ number_format($car->odometer_reading) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">No cars currently available for sale.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>