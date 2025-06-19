<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Car') }}: {{ $car->make }} {{ $car->model }} ({{ $car->registration_number }})
            </h2>
             <a href="{{ route('cars.cover_sheet', $car->id) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Print Cover Sheet
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Success Message --}}
            @if(session('success'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    <span class="font-medium">Success!</span> {{ session('success') }}
                </div>
            @endif

            {{-- Edit Car Form Section --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Car Details') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __("Update the car's details below.") }}
                            </p>
                        </header>

                        @if ($errors->any())
                            <div class="mt-4 text-sm text-red-600">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('cars.update', $car->id) }}" class="mt-6 space-y-6">
                            @csrf
                            @method('PUT')

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="vin" :value="__('VIN')" />
                                    <x-text-input id="vin" name="vin" type="text" class="mt-1 block w-full" :value="old('vin', $car->vin)" required autofocus />
                                </div>
                                <div>
                                    <x-input-label for="registration_number" :value="__('Registration Number')" />
                                    <x-text-input id="registration_number" name="registration_number" type="text" class="mt-1 block w-full" :value="old('registration_number', $car->registration_number)" required />
                                </div>
                                <div>
                                    <x-input-label for="make" :value="__('Make')" />
                                    <x-text-input id="make" name="make" type="text" class="mt-1 block w-full" :value="old('make', $car->make)" required />
                                </div>
                                <div>
                                    <x-input-label for="model" :value="__('Model')" />
                                    <x-text-input id="model" name="model" type="text" class="mt-1 block w-full" :value="old('model', $car->model)" required />
                                </div>
                                <div>
                                    <x-input-label for="year" :value="__('Year')" />
                                    <x-text-input id="year" name="year" type="number" class="mt-1 block w-full" :value="old('year', $car->year)" required />
                                </div>
                                <div>
                                    <x-input-label for="odometer_reading" :value="__('Odometer Reading')" />
                                    <x-text-input id="odometer_reading" name="odometer_reading" type="number" class="mt-1 block w-full" :value="old('odometer_reading', $car->odometer_reading)" required />
                                </div>
                            </div>
                            
                            <div>
                                <x-input-label for="current_status" :value="__('Current Status')" />
                                <select name="current_status" id="current_status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="In Stock - Awaiting Prep" @selected(old('current_status', $car->current_status) == 'In Stock - Awaiting Prep')>In Stock - Awaiting Prep</option>
                                    <option value="In Stock - Available" @selected(old('current_status', $car->current_status) == 'In Stock - Available')>In Stock - Available</option>
                                    <option value="In Stock - Awaiting VRT/NCT" @selected(old('current_status', $car->current_status) == 'In Stock - Awaiting VRT/NCT')>In Stock - Awaiting VRT/NCT</option>
                                    <option value="Reserved" @selected(old('current_status', $car->current_status) == 'Reserved')>Reserved</option>
                                    <option value="Sold" @selected(old('current_status', $car->current_status) == 'Sold')>Sold</option>
                                    <option value="In Service" @selected(old('current_status', $car->current_status) == 'In Service')>In Service</option>
                                </select>
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Update Car') }}</x-primary-button>
                                <a href="{{ route('cars.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline">Cancel</a>
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            {{-- Work Done History Section --}}
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <section class="space-y-6">
                    <header class="flex justify-between items-center">
                        <div>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Work Done History') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600">
                                A log of all service and preparation work for this vehicle.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('work.create', $car->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Add Work Record
                            </a>
                        </div>
                    </header>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cost</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mechanic</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($car->workDone as $work)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ \Carbon\Carbon::parse($work->work_date)->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-normal text-sm text-gray-900">{{ $work->description }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">€{{ number_format($work->cost, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $work->mechanic->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No work records found for this car.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
