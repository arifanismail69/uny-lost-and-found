<x-layout title="Riwayat" active="riwayat">
    <div class="page-head">
        <h1>Riwayat Saya</h1>
        <p>Pantau status laporan dan klaimmu di sini. Laporan dan klaim yang masih menunggu dapat diubah atau dibatalkan.</p>
    </div>

    <section>
        <div class="section-head">
            <h2 class="card-title"><x-icon name="list" /> Laporan Saya</h2>
            <x-button variant="secondary" :href="route('laporan.create')"><x-icon name="plus" :size="18" /> Buat Laporan</x-button>
        </div>
        @if ($laporan->isEmpty())
            <x-empty-state title="Anda belum membuat laporan" icon="plus">
                <x-button variant="secondary" :href="route('laporan.create')">Buat Laporan</x-button>
            </x-empty-state>
        @else
            <div class="card table-wrap">
                <table class="table table--stack">
                    <thead><tr><th>Kode</th><th>Barang</th><th>Jenis</th><th>Tanggal</th><th>Status</th><th>Keterangan</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @foreach ($laporan as $r)
                            <tr>
                                <td class="kode" data-label="Kode">{{ $r->kode }}</td>
                                <td data-label="Barang"><strong>{{ $r->nama }}</strong></td>
                                <td data-label="Jenis">{{ $r->jenis }}</td>
                                <td data-label="Tanggal">{{ $r->tanggal->translatedFormat('d M Y') }}</td>
                                <td data-label="Status"><x-status-badge :status="$r->status" /></td>
                                <td class="text-muted" data-label="Keterangan">{{ $r->alasan ? 'Alasan: ' . $r->alasan : '-' }}</td>
                                <td data-label="Aksi">
                                    @can('update', $r)
                                        <div class="actions" style="margin-top: 0; flex-wrap: nowrap">
                                            <x-button variant="secondary" :href="route('laporan.edit', $r)">Ubah</x-button>
                                            <form method="POST" action="{{ route('laporan.destroy', $r) }}" onsubmit="return confirm('Hapus laporan {{ $r->kode }}? Tindakan ini tidak dapat dibatalkan.')">
                                                @csrf @method('DELETE')
                                                <x-button type="submit" variant="danger">Hapus</x-button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="caption">Terkunci setelah diputuskan</span>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    <section class="section">
        <div class="section-head"><h2 class="card-title"><x-icon name="shield" /> Klaim Saya</h2></div>
        @if ($klaim->isEmpty())
            <x-empty-state title="Anda belum mengajukan klaim">
                <x-button variant="secondary" :href="route('barang.cari')">Cari Barang</x-button>
            </x-empty-state>
        @else
            <div class="card table-wrap">
                <table class="table table--stack">
                    <thead><tr><th>Kode</th><th>Barang</th><th>Status</th><th>Keterangan</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @foreach ($klaim as $c)
                            <tr>
                                <td class="kode" data-label="Kode">{{ $c->kode }}</td>
                                <td data-label="Barang"><strong>{{ $c->report->nama }}</strong><br><span class="caption">{{ $c->report->kode }}</span></td>
                                <td data-label="Status"><x-status-badge :status="$c->status" /></td>
                                <td data-label="Keterangan">
                                    @if ($c->status === 'Ditolak')
                                        <span class="text-muted">Alasan: {{ $c->alasan }}</span>
                                    @elseif ($c->status === 'Disetujui' && $c->report->status === 'Dikembalikan')
                                        <span class="text-muted">Barang sudah diterima.</span>
                                    @elseif ($c->status === 'Disetujui')
                                        <span style="display: inline-flex; gap: 6px; align-items: center"><x-icon name="pin" :size="16" /> Ambil di <strong>{{ $c->report->dititipkan_di }}</strong>, 08.00-15.00. Bawa kartu identitas.</span>
                                    @else
                                        <span class="text-muted">Sedang ditinjau petugas</span>
                                    @endif
                                </td>
                                <td data-label="Aksi">
                                    @can('update', $c)
                                        <div class="actions" style="margin-top: 0; flex-wrap: nowrap">
                                            <x-button variant="secondary" :href="route('klaim.edit', $c)">Ubah</x-button>
                                            <form method="POST" action="{{ route('klaim.destroy', $c) }}" onsubmit="return confirm('Batalkan klaim {{ $c->kode }}?')">
                                                @csrf @method('DELETE')
                                                <x-button type="submit" variant="danger">Batalkan</x-button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="caption">-</span>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</x-layout>
