{{-- READ: daftar semua laporan dari database (kelola laporan, petugas) --}}
<x-layout title="Kelola Laporan" active="kelola" :admin="true">
    <div class="page-head">
        <h1>Kelola Laporan</h1>
        <p>{{ $reports->count() }} laporan di database. Ubah atau hapus laporan yang keliru.</p>
    </div>

    <form method="GET" action="{{ route('laporan.index') }}" class="card form-row" style="margin-bottom: var(--space-5)">
        <x-form-field name="jenis" label="Jenis" type="select" :options="\App\Models\Report::JENIS" placeholder="Semua jenis" :value="$jenis" />
        <x-form-field name="status" label="Status" type="select" :options="\App\Models\Report::STATUS" placeholder="Semua status" :value="$status" />
        <div class="actions" style="grid-column: 1 / -1; margin-top: 0">
            <x-button type="submit" variant="secondary"><x-icon name="search" :size="18" /> Terapkan filter</x-button>
            <a href="{{ route('laporan.index') }}">Hapus filter</a>
        </div>
    </form>

    @if ($reports->isEmpty())
        <x-empty-state title="Tidak ada laporan sesuai filter" icon="list">
            <x-button variant="secondary" :href="route('laporan.index')">Hapus filter</x-button>
        </x-empty-state>
    @else
        <div class="card table-wrap">
            <table class="table table--stack">
                <thead><tr><th>Kode</th><th>Barang</th><th>Jenis</th><th>Lokasi</th><th>Tanggal</th><th>Pelapor</th><th>Status</th><th>Klaim</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach ($reports as $r)
                        <tr>
                            <td class="kode" data-label="Kode">{{ $r->kode }}</td>
                            <td data-label="Barang"><strong>{{ $r->nama }}</strong><br><span class="caption">{{ $r->kategori }}</span></td>
                            <td data-label="Jenis">{{ $r->jenis }}</td>
                            <td data-label="Lokasi">{{ $r->lokasi }}</td>
                            <td data-label="Tanggal">{{ $r->tanggal->translatedFormat('d M Y') }}</td>
                            <td data-label="Pelapor">{{ $r->pelapor }}</td>
                            <td data-label="Status"><x-status-badge :status="$r->status" /></td>
                            <td data-label="Klaim">{{ $r->claims_count }}</td>
                            <td data-label="Aksi">
                                <div class="actions" style="margin-top: 0; flex-wrap: nowrap">
                                    <x-button variant="secondary" :href="route('laporan.edit', [$r, 'petugas' => 1])">Ubah</x-button>
                                    <form method="POST" action="{{ route('laporan.destroy', [$r, 'petugas' => 1]) }}"
                                        onsubmit="return confirm('Hapus laporan {{ $r->kode }} beserta {{ $r->claims_count }} klaimnya? Tindakan ini tidak dapat dibatalkan.')">
                                        @csrf @method('DELETE')
                                        <x-button type="submit" variant="danger">Hapus</x-button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-layout>
