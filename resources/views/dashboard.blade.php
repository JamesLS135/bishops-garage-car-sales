<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Stat Cards Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    {{-- CORRECTED ROUTE --}}
                    <a href="{{ route('admin.reports.profitability') }}" class="block p-4 rounded-lg hover:bg-gray-50">
                        <h3 class="text-sm font-medium text-gray-500 truncate">Sales (This Month)</h3>
                        <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $salesThisMonth }}</p>
                    </a>

                    {{-- CORRECTED ROUTE --}}
                    <a href="{{ route('admin.reports.profitability') }}" class="block p-4 rounded-lg hover:bg-gray-50">
                        <h3 class="text-sm font-medium text-gray-500 truncate">Profit (This Month)</h3>
                        <p class="mt-1 text-3xl font-semibold text-green-600">€{{ number_format($profitThisMonth, 2) }}</p>
                    </a>

                    <!-- Cars Awaiting Prep Card (Clickable) -->
                    <a href="{{ route('cars.index', ['status' => 'In Stock - Awaiting Prep']) }}" class="block p-4 rounded-lg hover:bg-gray-50">
                        <h3 class="text-sm font-medium text-gray-500 truncate">Cars Awaiting Prep</h3>
                        <p class="mt-1 text-3xl font-semibold text-yellow-600">{{ $carsAwaitingPrepCount }}</p>
                    </a>

                    <!-- Stock Levels Card -->
                    <div class="p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-500 truncate">Current Stock</h3>
                        <div class="mt-2 space-y-1">
                             @forelse($stockCounts as $status => $total)
                                <a href="{{ route('cars.index', ['status' => $status]) }}" class="flex justify-between text-sm hover:bg-gray-100 p-1 rounded-md">
                                    <span class="text-gray-600">{{ $status }}</span>
                                    <span class="font-medium text-gray-900">{{ $total }}</span>
                                </a>
                            @empty
                                <p class="text-sm text-gray-500">No cars in stock.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available for Sale List Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium">Available for Sale</h3>
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Car</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registration</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Odometer</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @forelse ($availableCars as $car)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('cars.edit', $car->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                {{ $car->make }} {{ $car->model }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $car->registration_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($car->odometer_reading) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">No cars currently available for sale.</td>
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
