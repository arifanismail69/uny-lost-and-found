{{-- Form laporan: dipakai untuk CREATE (laporan.create) dan UPDATE (laporan.edit) --}}
@php
    $ubah = $report->exists;
    $petugas = auth()->user()->isAdmin();
    $aksi = $ubah ? route('laporan.update', $report) : route('laporan.store');
@endphp
<x-layout :title="$ubah ? 'Ubah Laporan' : 'Lapor Barang'" :active="$petugas ? 'kelola' : 'lapor'" :admin="$petugas">
    <div class="form-narrow">
        <div class="page-head">
            <h1>{{ $ubah ? 'Ubah Laporan ' . $report->kode : 'Lapor Barang' }}</h1>
            <p>
                @if ($ubah)
                    Perubahan hanya bisa dilakukan selama laporan belum diverifikasi petugas.
                @else
                    Laporan diperiksa petugas sebelum tampil di pencarian.
                @endif
                Kolom bertanda <span class="field__req">*</span> wajib diisi.
            </p>
        </div>

        <div class="card card--accent">
            @if ($errors->any())
                <x-alert type="danger" role="alert">Ada {{ $errors->count() }} isian yang belum benar. Periksa kolom yang ditandai merah.</x-alert>
            @endif

            <form method="POST" action="{{ $aksi }}" enctype="multipart/form-data" novalidate>
                @csrf
                @if ($ubah) @method('PUT') @endif
                @php $jenisAktif = old('jenis', $report->jenis); @endphp
                <fieldset class="field" style="border: 0; padding: 0; margin-inline: 0">
                    <legend class="field__label" style="margin-bottom: 6px">Jenis laporan <span class="field__req" aria-hidden="true">*</span></legend>
                    <div class="choice-group">
                        <label class="choice">
                            <input type="radio" name="jenis" value="Hilang" @checked($jenisAktif === 'Hilang')>
                            <span class="icon-bubble"><x-icon name="search" /></span>
                            <div><strong>Saya kehilangan barang</strong><span>Petugas dan penemu akan mencocokkan</span></div>
                        </label>
                        <label class="choice">
                            <input type="radio" name="jenis" value="Temuan" @checked($jenisAktif === 'Temuan')>
                            <span class="icon-bubble icon-bubble--accent"><x-icon name="box" /></span>
                            <div><strong>Saya menemukan barang</strong><span>Pemilik dapat mengajukan klaim</span></div>
                        </label>
                    </div>
                    @error('jenis')<span class="field__error">{{ $message }}</span>@enderror
                </fieldset>

                <div class="form-row">
                    <x-form-field name="nama" label="Nama barang" required placeholder="Contoh: Dompet kulit hitam" :value="$report->nama" />
                    <x-form-field name="kategori" label="Kategori" type="select" :options="\App\Models\Report::KATEGORI" placeholder="Pilih kategori" required :value="$report->kategori" />
                </div>
                <x-form-field name="ciri" label="Ciri barang" type="textarea" :rows="3" required placeholder="Warna, merek, ukuran, ciri khas" :value="$report->ciri" />
                <x-form-field name="ciri_rahasia" label="Ciri rahasia (opsional)" type="textarea" :rows="2" :value="$report->ciri_rahasia"
                    hint="Hanya terlihat oleh petugas, tidak tampil di halaman publik." />
                <div class="form-row">
                    <x-form-field name="lokasi" label="Lokasi" type="select" :options="\App\Models\Report::LOKASI" placeholder="Pilih lokasi" required :value="$report->lokasi" />
                    <x-form-field name="tanggal" label="Tanggal" type="date" required :max="now()->toDateString()" :value="$report->tanggal?->toDateString()"
                        hint="Tidak boleh lebih dari hari ini." />
                </div>
                <x-form-field name="foto" label="Foto barang (opsional)" type="file" accept=".jpg,.jpeg,.png"
                    hint="JPG atau PNG, maksimal 2 MB. Penyimpanan foto menyusul." />
                <x-form-field name="dititipkan_di" label="Dititipkan di (khusus temuan)" placeholder="Contoh: Pos Satpam Gedung FT" :value="$report->dititipkan_di" />

                <div class="actions">
                    <x-button type="submit"><x-icon name="upload" :size="18" /> {{ $ubah ? 'Simpan Perubahan' : 'Kirim Laporan' }}</x-button>
                    <x-button variant="secondary" :href="$petugas ? route('laporan.index') : ($ubah ? route('riwayat') : route('beranda'))">Batal</x-button>
                </div>
            </form>
        </div>
    </div>
</x-layout>
