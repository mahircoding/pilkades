<x-layouts::app :title="__('Tambah Calon')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" level="1">Tambah Calon</flux:heading>
                <flux:subheading>Daftarkan bakal calon kepala desa.</flux:subheading>
            </div>
            <flux:button :href="route('admin.candidates.index')" icon="arrow-left" wire:navigate>Kembali</flux:button>
        </div>

        <flux:separator class="my-4" />

        <div class="max-w-2xl">
            <form action="{{ route('admin.candidates.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-6">
                @csrf

                <flux:select name="election_period_id" :label="__('Periode Pilkades')">
                    @foreach($periods as $period)
                        <option value="{{ $period->id }}" @selected(old('election_period_id', $period->is_active ? $period->id : null) == $period->id)>
                            {{ $period->name }}
                        </option>
                    @endforeach
                </flux:select>

                <flux:input name="name" :label="__('Nama Lengkap')" :value="old('name')" required />
                <flux:input name="nik" :label="__('NIK (16 digit)')" :value="old('nik')" maxlength="16" pattern="\d{16}" required />
                <flux:input name="ballot_number" type="number" :label="__('Nomor Urut Calon')" :value="old('ballot_number')" min="1" max="99" description="Kosongkan jika belum ditetapkan." />
                <flux:input name="birth_place" :label="__('Tempat Lahir')" :value="old('birth_place')" />
                <flux:input name="birth_date" type="date" :label="__('Tanggal Lahir')" :value="old('birth_date')" />

                <flux:select name="gender" :label="__('Jenis Kelamin')">
                    <option value="">-- Pilih --</option>
                    <option value="Laki-laki" @selected(old('gender') === 'Laki-laki')>Laki-laki</option>
                    <option value="Perempuan" @selected(old('gender') === 'Perempuan')>Perempuan</option>
                </flux:select>

                <flux:textarea name="address" :label="__('Alamat')" :value="old('address')" />
                <flux:textarea name="vision" :label="__('Visi')" :value="old('vision')" rows="3" />
                <flux:textarea name="mission" :label="__('Misi')" :value="old('mission')" rows="5" />

                <flux:input name="photo" type="file" :label="__('Foto Calon')" accept="image/*" description="Format JPG/PNG, maks 2 MB." />

                <div class="flex justify-end">
                    <flux:button variant="primary" type="submit">{{ __('Simpan') }}</flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
