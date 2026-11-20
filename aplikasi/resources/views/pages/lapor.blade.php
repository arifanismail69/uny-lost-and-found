<x-layout title="Lapor Barang" active="lapor">
    <div class="card form-narrow">
        <h1>Lapor Barang</h1>
        @if ($errors->any())
            <x-alert type="danger" role="alert">Ada {{ $errors->count() }} isian yang belum benar. Periksa kolom yang ditandai merah.</x-alert>
        @endif

        <form method="POST" action="{{ route('laporan.store') }}" enctype="multipart/form-data" novalidate>
            @csrf
            @php $jenisAktif = old('jenis', $jenis); @endphp
            <fieldset class="field" style="border: 0; padding: 0">
                <legend class="field__label">Jenis laporan <span class="field__req" aria-hidden="true">*</span></legend>
                <div class="radio-group">
                    <label><input type="radio" name="jenis" value="Hilang" @checked($jenisAktif === 'Hilang')> Saya kehilangan barang</label>
                    <label><input type="radio" name="jenis" value="Temuan" @checked($jenisAktif === 'Temuan')> Saya menemukan barang</label>
                </div>
            </fieldset>

            <div class="form-row">
                <x-form-field name="nama" label="Nama barang" required placeholder="Contoh: Dompet kulit hitam" />
                <x-form-field name="kategori" label="Kategori" type="select" :options="$kategori" placeholder="Pilih kategori" required />
            </div>
            <x-form-field name="ciri" label="Ciri barang" type="textarea" :rows="3" required placeholder="Warna, merek, ukuran, ciri khas" />
            <x-form-field name="ciri_rahasia" label="Ciri rahasia (opsional)" type="textarea" :rows="2"
                hint="Hanya terlihat oleh petugas, tidak tampil di halaman publik." />
            <div class="form-row">
                <x-form-field name="lokasi" label="Lokasi" type="select" :options="$lokasi" placeholder="Pilih lokasi" required />
                <x-form-field name="tanggal" label="Tanggal" type="date" required :max="now()->toDateString()"
                    hint="Tidak boleh lebih dari hari ini." />
            </div>
            <x-form-field name="foto" label="Foto barang (opsional)" type="file" accept=".jpg,.jpeg,.png"
                hint="JPG atau PNG, maksimal 2 MB." />
            <x-form-field name="dititipkan_di" label="Dititipkan di (khusus temuan)" placeholder="Contoh: Pos Satpam Gedung FT" />

            <div class="actions">
                <x-button type="submit">Kirim Laporan</x-button>
                <x-button variant="secondary" :href="route('beranda')">Batal</x-button>
            </div>
        </form>
    </div>
</x-layout>
