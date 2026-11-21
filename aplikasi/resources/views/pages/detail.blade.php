<x-layout :title="$barang->nama" active="cari">
    <nav class="breadcrumb" aria-label="Jejak halaman">
        <a href="{{ route('barang.cari') }}"><x-icon name="arrow-left" :size="16" /> Cari Barang</a> / {{ $barang->nama }}
    </nav>

    <div class="detail-layout">
        <div class="stack">
            <x-item-photo :kategori="$barang->kategori" :large="true" />
            <div>
                <x-status-badge :status="$sudahDiklaim ? 'Sudah diklaim' : 'Dapat diklaim'" />
                <h1 style="margin-top: var(--space-2)">{{ $barang->nama }}</h1>
                <p class="text-muted">{{ $barang->ciri }}</p>
            </div>
            <dl class="card info-list">
                <div><span class="icon-bubble"><x-icon name="tag" /></span><div><dt>Kategori</dt><dd>{{ $barang->kategori }}</dd></div></div>
                <div><span class="icon-bubble"><x-icon name="pin" /></span><div><dt>Ditemukan di</dt><dd>{{ $barang->lokasi }}</dd></div></div>
                <div><span class="icon-bubble"><x-icon name="calendar" /></span><div><dt>Tanggal ditemukan</dt><dd>{{ $barang->tanggal->translatedFormat('d F Y') }}</dd></div></div>
                <div><span class="icon-bubble icon-bubble--accent"><x-icon name="lock" /></span><div><dt>Tempat penitipan</dt><dd class="text-muted" style="font-weight: 400">Ditampilkan setelah klaim disetujui</dd></div></div>
            </dl>
            <x-alert type="warning">Ciri rahasia disembunyikan untuk verifikasi klaim oleh petugas.</x-alert>
        </div>

        <section class="card card--accent sticky-panel" aria-labelledby="judul-klaim">
            <h2 id="judul-klaim">Ajukan Klaim</h2>
            <ol class="mini-steps" aria-label="Langkah klaim">
                <li class="is-current">1. Isi bukti</li><li>2. Diperiksa petugas</li><li>3. Ambil barang</li>
            </ol>
            @if ($sudahDiklaim)
                <x-alert type="danger">Barang sudah diklaim. Klaim baru tidak dapat diajukan.</x-alert>
                <x-button :disabled="true" block>Barang sudah diklaim</x-button>
            @else
                <p class="text-muted">Jelaskan ciri yang hanya diketahui pemilik barang. Semakin spesifik, semakin cepat petugas memutuskan.</p>
                @if ($errors->any())
                    <x-alert type="danger" role="alert">Periksa kembali isian yang ditandai merah.</x-alert>
                @endif
                <form method="POST" action="{{ route('klaim.store', $barang) }}" novalidate>
                    @csrf
                    <x-form-field name="bukti" label="Ciri pembuktian" type="textarea" required
                        placeholder="Contoh: isi dompet, bekas goresan, foto di kartu" hint="Minimal 10 karakter." />
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
