{{-- UPDATE klaim: ubah ciri pembuktian selama klaim masih menunggu --}}
<x-layout :title="'Ubah Klaim ' . $claim->kode" active="riwayat">
    <div class="form-narrow">
        <nav class="breadcrumb" aria-label="Jejak halaman">
            <a href="{{ route('riwayat') }}"><x-icon name="arrow-left" :size="16" /> Riwayat</a> / Ubah klaim {{ $claim->kode }}
        </nav>
        <div class="page-head">
            <h1>Ubah Klaim {{ $claim->kode }}</h1>
            <p>Barang: <strong>{{ $claim->report->nama }}</strong> ({{ $claim->report->kode }}) · <x-status-badge :status="$claim->status" /></p>
        </div>

        <div class="card card--accent">
            @if ($errors->any())
                <x-alert type="danger" role="alert">Periksa kembali isian yang ditandai merah.</x-alert>
            @endif
            <form method="POST" action="{{ route('klaim.update', $claim) }}" novalidate>
                @csrf @method('PUT')
                <x-form-field name="bukti" label="Ciri pembuktian" type="textarea" required :value="$claim->bukti" hint="Minimal 10 karakter." />
                <x-form-field name="catatan" label="Catatan tambahan (opsional)" type="textarea" :rows="2" :value="$claim->catatan" />
                <div class="actions">
                    <x-button type="submit">Simpan Perubahan</x-button>
                    <x-button variant="secondary" :href="route('riwayat')">Batal</x-button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
