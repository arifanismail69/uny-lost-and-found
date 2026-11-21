<x-layout title="Halaman Uji Komponen">
    <div class="page-head">
        <h1>Halaman uji komponen</h1>
        <p class="text-muted">Semua komponen memakai design token dari <code>public/css/tokens.css</code>.</p>
    </div>

    <div class="stack">
        <section class="card">
            <h2>Button</h2>
            <div class="actions">
                <x-button>Default</x-button>
                <x-button variant="secondary">Secondary</x-button>
                <x-button variant="danger">Danger</x-button>
                <x-button :disabled="true">Disabled</x-button>
                <x-button class="is-loading">Loading</x-button>
            </div>
            <p class="caption">Hover dan focus: arahkan kursor atau tekan Tab pada tombol.</p>
        </section>

        <section class="card">
            <h2>Form field</h2>
            <div class="grid grid--2">
                <x-form-field name="contoh_kosong" label="Empty" placeholder="Belum diisi" />
                <x-form-field name="contoh_terisi" label="Filled" value="Dompet kulit hitam" />
                <x-form-field name="contoh_fokus" label="Focus (klik atau Tab)" placeholder="Fokus di sini" hint="Teks bantuan" />
                <x-form-field name="contoh_error" label="Error" required error="Kolom ini wajib diisi." />
            </div>
        </section>

        <section class="card">
            <h2>Status badge</h2>
            <div class="actions">
                @foreach (['Menunggu', 'Menunggu verifikasi', 'Terverifikasi', 'Disetujui', 'Ditolak', 'Dikembalikan', 'Sudah diklaim'] as $s)
                    <x-status-badge :status="$s" />
                @endforeach
            </div>
        </section>

        <section>
            <h2>Item card (normal, terpilih, sudah diklaim)</h2>
            <div class="grid grid--3">
                <x-item-card :item="\App\Models\Report::find(2)" />
                <x-item-card :item="\App\Models\Report::find(1)" :selected="true" />
                <x-item-card :item="\App\Models\Report::find(6)" />
            </div>
        </section>

        <section>
            <h2>Alert dan empty state</h2>
            <x-alert type="success">Laporan terkirim.</x-alert>
            <x-alert type="warning">Ciri rahasia disembunyikan.</x-alert>
            <x-alert type="danger">Barang sudah diklaim.</x-alert>
            <x-empty-state title="Tidak ada barang yang cocok" text="Coba ubah kata kunci atau kurangi filter.">
                <x-button variant="secondary">Hapus filter</x-button>
            </x-empty-state>
        </section>
    </div>
</x-layout>
