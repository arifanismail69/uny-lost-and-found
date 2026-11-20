<x-layout :title="$barang['nama']" active="cari">
    <nav class="breadcrumb" aria-label="Jejak halaman">
        <a href="{{ route('barang.cari') }}">Cari Barang</a> / {{ $barang['nama'] }}
    </nav>

    <div class="detail-layout">
        <div class="stack">
            <div class="photo photo--large" aria-hidden="true">Foto barang</div>
            <div>
                <h1>{{ $barang['nama'] }}</h1>
                <x-status-badge :status="$sudahDiklaim ? 'Sudah diklaim' : 'Dapat diklaim'" />
            </div>
            <dl class="card card--muted" style="margin: 0">
                <dt class="caption">Kategori</dt><dd style="margin: 0 0 8px">{{ $barang['kategori'] }}</dd>
                <dt class="caption">Ditemukan di</dt><dd style="margin: 0 0 8px">{{ $barang['lokasi'] }}</dd>
                <dt class="caption">Tanggal ditemukan</dt><dd style="margin: 0 0 8px">{{ \Illuminate\Support\Carbon::parse($barang['tanggal'])->translatedFormat('d F Y') }}</dd>
                <dt class="caption">Ciri umum</dt><dd style="margin: 0 0 8px">{{ $barang['ciri'] }}</dd>
                <dt class="caption">Tempat penitipan</dt><dd style="margin: 0" class="text-muted">Ditampilkan setelah klaim disetujui</dd>
            </dl>
            <x-alert type="warning">Ciri rahasia disembunyikan untuk verifikasi klaim oleh petugas.</x-alert>
        </div>

        <section class="card" aria-labelledby="judul-klaim">
            <h2 id="judul-klaim">Ajukan Klaim</h2>
            @if ($sudahDiklaim)
                <x-alert type="danger">Barang sudah diklaim. Klaim baru tidak dapat diajukan.</x-alert>
                <x-button :disabled="true" block>Barang sudah diklaim</x-button>
            @else
                <p class="text-muted">Jelaskan ciri yang hanya diketahui pemilik barang.</p>
                @if ($errors->any())
                    <x-alert type="danger" role="alert">Periksa kembali isian yang ditandai merah.</x-alert>
                @endif
                <form method="POST" action="{{ route('barang.klaim', $barang['kode']) }}" novalidate>
                    @csrf
                    <x-form-field name="bukti" label="Ciri pembuktian" type="textarea" required
                        placeholder="Contoh: isi dompet, bekas goresan, foto di kartu" />
                    <x-form-field name="catatan" label="Catatan tambahan (opsional)" type="textarea" :rows="2" />
                    <div class="actions">
                        <x-button type="submit">Kirim Klaim</x-button>
                        <x-button variant="secondary" :href="route('barang.cari')">Batal</x-button>
                    </div>
                </form>
            @endif
        </section>
    </div>
</x-layout>
