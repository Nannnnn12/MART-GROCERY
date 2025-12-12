<x-filament-widgets::widget>
    <x-filament::section heading="Customer Information">
        <x-filament-panels::page>
            <div class="grid grid-cols-2 gap-6">

                <div class="space-y-1">
                    <p class="text-sm text-gray-500">Nama</p>
                    <p class="text-base font-semibold text-gray-900">
                        {{ $record->name }}
                    </p>
                </div>

                <div class="space-y-1">
                    <p class="text-sm text-gray-500">Email</p>
                    <p class="text-base font-semibold text-gray-900">
                        {{ $record->email }}
                    </p>
                </div>

                <div class="space-y-1">
                    <p class="text-sm text-red-500">Nomor WhatsApp</p>
                    <p class="text-base font-semibold text-gray-900">
                        {{ $record->phone_number }}
                    </p>
                </div>

                <div class="col-span-2 space-y-1">
                    <p class="text-sm text-gray-500">Alamat</p>
                    <p class="text-base font-semibold text-gray-900">
                        {{ $record->address }}
                    </p>
                </div>

            </div>
        </x-filament-panels::page>
    </x-filament::section>
</x-filament-widgets::widget>
