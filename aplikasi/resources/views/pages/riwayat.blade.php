<x-layout title="Riwayat" active="riwayat">
    <div class="page-head">
        <h1>Riwayat Saya</h1>
        <p>Pantau status laporan dan klaimmu di sini.</p>
    </div>

    <section>
        <div class="section-head"><h2 class="card-title"><x-icon name="list" /> Laporan Saya</h2></div>
        @forelse ($laporan as $r)
            @if ($loop->first)<div class="card table-wrap"><table class="table table--stack"><thead><tr><th>Kode</th><th>Barang</th><th>Jenis</th><th>Tanggal</th><th>Status</th><th>Keterangan</th></tr></thead><tbody>@endif
            <tr>
                <td class="kode" data-label="Kode">{{ $r['kode'] }}</td><td data-label="Barang"><strong>{{ $r['nama'] }}</strong></td><td data-label="Jenis">{{ $r['jenis'] }}</td>
                <td data-label="Tanggal">{{ \Illuminate\Support\Carbon::parse($r['tanggal'])->translatedFormat('d M Y') }}</td>
                <td data-label="Status"><x-status-badge :status="$r['status']" /></td>
                <td class="text-muted" data-label="Keterangan">{{ isset($r['alasan']) ? 'Alasan: ' . $r['alasan'] : '-' }}</td>
            </tr>
            @if ($loop->last)</tbody></table></div>@endif
        @empty
            <x-empty-state title="Anda belum membuat laporan" icon="plus">
                <x-button variant="secondary" :href="route('laporan.create')">Buat Laporan</x-button>
            </x-empty-state>
        @endforelse
    </section>

    <section class="section">
        <div class="section-head"><h2 class="card-title"><x-icon name="shield" /> Klaim Saya</h2></div>
        @forelse ($klaim as $c)
            @if ($loop->first)<div class="card table-wrap"><table class="table table--stack"><thead><tr><th>Kode</th><th>Barang</th><th>Status</th><th>Keterangan</th></tr></thead><tbody>@endif
            <tr>
                <td class="kode" data-label="Kode">{{ $c['kode'] }}</td><td data-label="Barang"><strong>{{ $c['barang']['nama'] }}</strong></td>
                <td data-label="Status"><x-status-badge :status="$c['status']" /></td>
                <td data-label="Keterangan">
                    @if ($c['status'] === 'Ditolak')
                        <span class="text-muted">Alasan: {{ $c['alasan'] }}</span>
                    @elseif ($c['status'] === 'Disetujui')
                        <span style="display: inline-flex; gap: 6px; align-items: center"><x-icon name="pin" :size="16" /> Ambil di <strong>{{ $c['barang']['dititipkan_di'] }}</strong>, 08.00-15.00. Bawa kartu identitas.</span>
                    @else
                        <span class="text-muted">Sedang ditinjau petugas</span>
                    @endif
                </td>
            </tr>
            @if ($loop->last)</tbody></table></div>@endif
        @empty
            <x-empty-state title="Anda belum mengajukan klaim">
                <x-button variant="secondary" :href="route('barang.cari')">Cari Barang</x-button>
            </x-empty-state>
        @endforelse
    </section>
</x-layout>
