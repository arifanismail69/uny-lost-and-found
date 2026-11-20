<x-layout title="Riwayat" active="riwayat">
    <div class="page-head"><h1>Riwayat Saya</h1></div>

    <section>
        <h2>Laporan Saya</h2>
        @forelse ($laporan as $r)
            @if ($loop->first)<div class="card table-wrap"><table class="table"><thead><tr><th>Kode</th><th>Barang</th><th>Jenis</th><th>Tanggal</th><th>Status</th><th>Keterangan</th></tr></thead><tbody>@endif
            <tr>
                <td>{{ $r['kode'] }}</td><td>{{ $r['nama'] }}</td><td>{{ $r['jenis'] }}</td><td>{{ $r['tanggal'] }}</td>
                <td><x-status-badge :status="$r['status']" /></td>
                <td class="text-muted">{{ isset($r['alasan']) ? 'Alasan: ' . $r['alasan'] : '' }}</td>
            </tr>
            @if ($loop->last)</tbody></table></div>@endif
        @empty
            <x-empty-state title="Anda belum membuat laporan">
                <x-button variant="secondary" :href="route('laporan.create')">Buat Laporan</x-button>
            </x-empty-state>
        @endforelse
    </section>

    <section class="section">
        <h2>Klaim Saya</h2>
        @forelse ($klaim as $c)
            @if ($loop->first)<div class="card table-wrap"><table class="table"><thead><tr><th>Kode</th><th>Barang</th><th>Status</th><th>Keterangan</th></tr></thead><tbody>@endif
            <tr>
                <td>{{ $c['kode'] }}</td><td>{{ $c['barang']['nama'] }}</td>
                <td><x-status-badge :status="$c['status']" /></td>
                <td class="text-muted">
                    @if ($c['status'] === 'Ditolak') Alasan: {{ $c['alasan'] }}
                    @elseif ($c['status'] === 'Disetujui') Ambil di {{ $c['barang']['dititipkan_di'] }}, 08.00-15.00. Bawa kartu identitas.
                    @else Sedang ditinjau petugas @endif
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
