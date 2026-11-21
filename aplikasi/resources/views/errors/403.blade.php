<x-layout title="Tidak diizinkan">
    <x-empty-state title="Tidak diizinkan" :text="$exception->getMessage() ?: 'Anda tidak memiliki akses ke halaman ini.'" icon="lock">
        <x-button :href="route('riwayat')">Ke Riwayat</x-button>
        <x-button variant="secondary" :href="route('beranda')">Ke Beranda</x-button>
    </x-empty-state>
</x-layout>
