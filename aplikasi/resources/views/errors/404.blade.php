<x-layout title="Tidak ditemukan">
    <x-empty-state title="Halaman atau barang tidak ditemukan" text="Alamat tidak ada, atau barang sudah tidak ditampilkan.">
        <x-button :href="route('beranda')">Kembali ke Beranda</x-button>
        <x-button variant="secondary" :href="route('barang.cari')">Cari Barang</x-button>
    </x-empty-state>
</x-layout>
