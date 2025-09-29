<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Bandingkan Kinerja Properti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-6">Pilih Properti dan Rentang Tanggal</h3>

                    <form method="GET" action="{{ route('admin.properties.compare.results') }}">
                        
                        {{-- Pemilihan Properti --}}
                        <div class="mb-6">
                            <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-2">Pilih Properti (Minimal 1):</label>
                            
                            <div class="mt-2 mb-4">
                                <label for="select-all-properties" class="flex items-center space-x-2 cursor-pointer">
                                    <input type="checkbox" id="select-all-properties" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Pilih Semua Properti</span>
                                </label>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 max-h-60 overflow-y-auto p-2 border dark:border-gray-700 rounded-md">
                                @forelse ($properties as $property)
                                    <label for="property_{{ $property->id }}" class="flex items-center space-x-2 p-2 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-md cursor-pointer">
                                        {{-- ======================= PERBAIKAN 1 DI SINI ======================= --}}
                                        <input type="checkbox" id="property_{{ $property->id }}" name="property_ids[]" value="{{ $property->id }}"
                                               class="property-checkbox rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                                               {{ (is_array(old('property_ids')) && in_array($property->id, old('property_ids'))) ? 'checked' : '' }}>
                                        <span class="text-sm text-gray-700 dark:text-gray-300">{{ $property->name }}</span>
                                    </label>
                                @empty
                                    <p class="text-sm text-gray-500 dark:text-gray-400 col-span-full">Tidak ada properti tersedia.</p>
                                @endforelse
                            </div>
                            
                            {{-- ======================= PERBAIKAN 2 DI SINI ======================= --}}
                            @error('property_ids')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Pemilihan Rentang Tanggal --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Dari Tanggal') }}</label>
                                <input id="start_date" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700" type="date" name="start_date" value="{{ old('start_date', \Carbon\Carbon::now()->startOfMonth()->toDateString()) }}" required />
                                @error('start_date')
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Sampai Tanggal') }}</label>
                                <input id="end_date" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:border-gray-700" type="date" name="end_date" value="{{ old('end_date', \Carbon\Carbon::now()->endOfMonth()->toDateString()) }}" required />
                                @error('end_date')
                                    <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                {{ __('Tampilkan Perbandingan') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Skrip untuk fungsionalitas "Pilih Semua" --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAllCheckbox = document.getElementById('select-all-properties');
            const propertyCheckboxes = document.querySelectorAll('.property-checkbox');

            selectAllCheckbox.addEventListener('change', function () {
                propertyCheckboxes.forEach(checkbox => {
                    checkbox.checked = this.checked;
                });
            });

            propertyCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    if (!this.checked) {
                        selectAllCheckbox.checked = false;
                    } else {
                        const allChecked = Array.from(propertyCheckboxes).every(cb => cb.checked);
                        selectAllCheckbox.checked = allChecked;
                    }
                });
            });
        });
    </script>
    @endpush
</x-admin-layout>