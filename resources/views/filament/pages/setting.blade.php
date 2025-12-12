<x-filament-panels::page>
    @if($successMessage)
        <div style="margin-bottom: 1rem; padding: 1rem; background: #d1fae5; border: 1px solid #10b981; border-radius: 0.5rem; color: #065f46;">
            {{ $successMessage }}
        </div>
    @endif

    <form wire:submit.prevent="save">
        {{ $this->form }}
        <div style="margin-top: 1rem">
            <x-filament::button type="submit">
                Simpan
            </x-filament::button>
        </div>
    </form>

    @if($data['show_homepage_button'] ?? true)
        <div style="margin-top: 2rem; padding: 1rem; background: #fef3c7; border: 1px solid #f59e0b; border-radius: 0.5rem;">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h3 style="font-weight: 600; color: #92400e;">Quick Access</h3>
                    <p style="color: #a16207; margin-top: 0.25rem;">Visit your store homepage</p>
                </div>
                <a href="{{ route('home') }}"
                   target="_blank"
                   style="background: #f59e0b; color: white; padding: 0.5rem 1rem; border-radius: 0.375rem; text-decoration: none; font-weight: 500; transition: background-color 0.2s;">
                    {{ $data['homepage_button_text'] ?? 'Visit Homepage' }}
                </a>
            </div>
        </div>
    @endif
</x-filament-panels::page>
