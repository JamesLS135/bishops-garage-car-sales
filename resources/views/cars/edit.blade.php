<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Car') }}: {{ $car->make }} {{ $car->model }} ({{ $car->registration_number }})
            </h2>
             <a href="{{ route('cars.cover_sheet', $car->id) }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Print Cover Sheet
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                    <span class="font-medium">Success!</span> {{ session('success') }}
                </div>
            @endif
             @if ($errors->any())
                <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                    <span class="font-medium">Error!</span> Please check the forms below for issues.
                </div>
            @endif

            {{-- Edit Car Form Section --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-full">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Car Details') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __("Update the car's details below.") }}
                            </p>
                        </header>

                        <form method="POST" action="{{ route('cars.update', $car->id) }}" class="mt-6 space-y-6">
                            @csrf
                            @method('PUT')
                             <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="vin" :value="__('VIN')" />
                                    <x-text-input id="vin" name="vin" type="text" class="mt-1 block w-full" :value="old('vin', $car->vin)" required />
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
                                <div class="md:col-span-2">
                                    <x-input-label for="current_status" :value="__('Current Status')" />
                                    <select name="current_status" id="current_status" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="In Stock - Awaiting Prep" @selected(old('current_status', $car->current_status) == 'In Stock - Awaiting Prep')>In Stock - Awaiting Prep</option>
                                        <option value="In Stock - Available" @selected(old('current_status', $car->current_status) == 'In Stock - Available')>In Stock - Available</option>
                                        <option value="In Stock - Awaiting VRT/NCT" @selected(old('current_status', $car->current_status) == 'In Stock - Awaiting VRT/NCT')>In Stock - Awaiting VRT/NCT</option>
                                        <option value="Reserved" @selected(old('current_status', $car->current_status) == 'Reserved')>Reserved</option>
                                        <option value="Sold" @selected(old('current_status', $car->current_status) == 'Sold')>Sold</option>
                                        <option value="In Service" @selected(old('current_status', $car->current_status) == 'In Service')>In Service</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex items-center gap-4">
                                <x-primary-button>{{ __('Update Car') }}</x-primary-button>
                                <a href="{{ route('cars.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline">Cancel</a>
                            </div>
                        </form>
                    </section>
                </div>
            </div>

            {{-- Image Management Section --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <section>
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Image Management
                        </h2>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            Upload and manage photos for this vehicle. The primary image will be shown on the main inventory list.
                        </p>
                    </header>

                    <form action="{{ route('car-images.store', $car->id) }}" method="POST" enctype="multipart/form-data" class="mt-6 border-b dark:border-gray-700 pb-6">
                        @csrf
                        <div class="flex items-center gap-4">
                            <x-input-label for="image" value="New Image" class="sr-only" />
                            <x-text-input id="image" name="image" type="file" class="block w-full" required />
                            <x-primary-button>{{ __('Upload') }}</x-primary-button>
                        </div>
                         @error('image')
                            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </form>

                    <div class="mt-6 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @forelse($car->images as $image)
                            <div class="relative group">
                                <img src="{{ $image->url }}" alt="Car Image" class="rounded-lg object-cover w-full h-32 image-management-img border border-gray-300 dark:border-gray-700 shadow-sm">
                                @if($image->is_primary)
                                    <div class="absolute top-2 right-2 bg-green-600 text-white text-xs font-semibold px-2 py-1 rounded-full shadow">
                                        Primary
                                    </div>
                                @endif
                                <div class="absolute top-2 left-2 flex flex-col items-start space-y-2">
                                    @if(!$image->is_primary)
                                    <form action="{{ route('car-images.set-primary', $image->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center px-2 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded shadow transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2">
                                            Set as Primary
                                        </button>
                                    </form>
                                    @endif

                                    <form action="{{ route('car-images.destroy', $image->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this image?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded shadow transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-red-400 focus:ring-offset-2">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 col-span-full">No images uploaded for this car.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            {{-- Work Done History Section --}}
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <section>
                    <header class="flex justify-between items-center">
                        <div>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Work Done History') }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                A log of all service and preparation work for this vehicle.
                            </p>
                        </div>
                        <div>
                            <a href="{{ route('work.create', $car->id) }}">
                                <x-secondary-button>
                                    {{ __('Add Work Record') }}
                                </x-secondary-button>
                            </a>
                        </div>
                    </header>

                    <div class="mt-6 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Description</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cost</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Mechanic</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($car->workDone as $work)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ \Carbon\Carbon::parse($work->work_date)->format('d/m/Y') }}</td>
                                        <td class="px-6 py-4 whitespace-normal text-sm text-gray-900 dark:text-gray-100">
                                            <a href="{{ route('work.show', $work->work_id) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 hover:underline">
                                                {{ Str::limit($work->description, 50) }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">€{{ number_format($work->cost, 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $work->mechanic->name }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">No work records found for this car.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <style>
        /* Make car images smaller in the image management grid */
        .image-management-img {
            width: 100px !important;
            height: 75px !important;
            object-fit: cover;
        }
    </style>
    <script>
        // Optionally, you can add a class via JS if you want to avoid editing the Blade file
        document.querySelectorAll('.image-management-img').forEach(img => {
            img.classList.add('rounded-lg');
        });
    </script>
</x-app-layout>