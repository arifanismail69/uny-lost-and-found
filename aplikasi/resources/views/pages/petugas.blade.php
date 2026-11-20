<x-layout title="Laporan dan Klaim" active="petugas" :admin="true">
    <div class="page-head"><h1>Laporan dan Klaim</h1></div>

    <div class="master-detail">
        <section class="card" aria-label="Daftar laporan dan klaim">
            <form method="GET" action="{{ route('petugas.index') }}" class="form-row">
                <div class="field">
                    <label class="field__label" for="f-status">Status</label>
                    <select id="f-status" name="status" class="field__control" onchange="this.form.submit()">
                        @foreach (['Menunggu', 'Terverifikasi', 'Disetujui', 'Ditolak', 'Dikembalikan', ''] as $s)
                            <option value="{{ $s }}" @selected($status === $s)>{{ $s ?: 'Semua' }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label class="field__label" for="f-jenis">Jenis</label>
                    <select id="f-jenis" name="jenis" class="field__control" onchange="this.form.submit()">
                        @foreach (['' => 'Semua', 'Hilang' => 'Hilang', 'Temuan' => 'Temuan', 'Klaim' => 'Klaim'] as $v => $l)
                            <option value="{{ $v }}" @selected($jenis === $v)>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <noscript><x-button type="submit" variant="secondary">Terapkan filter</x-button></noscript>
            </form>

            @forelse ($items as $i)
                <a class="list-item" href="{{ route('petugas.index', ['status' => $status, 'jenis' => $jenis, 'kode' => $i['kode']]) }}"
                    @if ($dipilih && request('kode') === $i['kode']) aria-current="true" @endif>
                    <strong>{{ $i['kode'] }}</strong> <x-status-badge :status="$i['status']" /><br>
                    <span class="caption">{{ $i['judul'] }}</span>
                </a>
            @empty
                <x-empty-state title="Tidak ada laporan sesuai filter">
                    <x-button variant="secondary" :href="route('petugas.index', ['status' => ''])">Hapus filter</x-button>
                </x-empty-state>
            @endforelse
        </section>

        <section class="card" aria-label="Rincian">
            @if (! $dipilih)
                <p class="text-muted">Pilih satu baris untuk melihat rincian.</p>
            @else
                @php $b = $dipilih['barang']; $k = $dipilih['klaim'] ?? null; $menunggu = $k ? $k['status'] === 'Menunggu' : $b['status'] === 'Menunggu verifikasi'; @endphp
                <h2>{{ $k ? 'Klaim ' . $k['kode'] : $b['kode'] }}: {{ $b['nama'] }}
                    <x-status-badge :status="$k['status'] ?? $b['status']" /></h2>
                <p class="caption">{{ $b['jenis'] }} · {{ $b['lokasi'] }} · {{ $b['tanggal'] }}{{ $k ? ' · diajukan ' . $k['pemilik'] : '' }}</p>

                <div class="compare">
                    <div class="card card--muted"><strong>Ciri rahasia (laporan)</strong><p>{{ $b['ciri_rahasia'] ?: '-' }}</p></div>
                    <div class="card card--muted">
                        <strong>{{ $k ? 'Bukti dari pengklaim' : 'Ciri umum' }}</strong>
                        <p>{{ $k ? $k['bukti'] : $b['ciri'] }}</p>
                    </div>
                </div>

                @if ($dipilih['blokir'])
                    <x-alert type="warning" style="margin-top: 16px">Sudah ada klaim lain yang disetujui untuk barang ini. Tombol Setujui dinonaktifkan.</x-alert>
                @endif

                @if ($menunggu)
                    <form method="POST" action="{{ route('petugas.keputusan', $k['kode'] ?? $b['kode']) }}" style="margin-top: 16px" novalidate>
                        @csrf
                        <x-form-field name="alasan" label="Alasan penolakan" type="textarea" :rows="3" hint="Wajib diisi saat menolak." />
                        <div class="actions">
                            <x-button type="submit" name="aksi" value="setujui" :disabled="$dipilih['blokir']">Setujui</x-button>
                            <x-button type="submit" name="aksi" value="tolak" variant="danger">Tolak</x-button>
                        </div>
                    </form>
                @else
                    <p class="section text-muted">Keputusan sudah dicatat{{ isset(($k ?? $b)['alasan']) ? ': ' . ($k ?? $b)['alasan'] : '.' }}</p>
                @endif
            @endif
        </section>
    </div>
</x-layout>
