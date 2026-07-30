<x-layouts::app :title="__('Edit Calon')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" level="1">Edit Calon</flux:heading>
                <flux:subheading>Perbarui data bakal calon.</flux:subheading>
            </div>
            <flux:button :href="route('admin.candidates.show', $candidate)" icon="arrow-left" wire:navigate>Kembali</flux:button>
        </div>

        <flux:separator class="my-4" />

        <div class="max-w-2xl">
            <form action="{{ route('admin.candidates.update', $candidate) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-6">
                @csrf
                @method('PUT')

                <flux:input name="name" :label="__('Nama Lengkap')" :value="old('name', $candidate->name)" required />
                <flux:input name="nik" :label="__('NIK (16 digit)')" :value="old('nik')" maxlength="16" pattern="\d{16}" placeholder="Isi hanya jika ingin mengubah NIK" description="Biarkan kosong jika tidak berubah." />
                <flux:input name="ballot_number" type="number" :label="__('Nomor Urut Calon')" :value="old('ballot_number', $candidate->ballot_number)" min="1" max="99" />
                <flux:input name="birth_place" :label="__('Tempat Lahir')" :value="old('birth_place', $candidate->birth_place)" />
                <flux:input name="birth_date" type="date" :label="__('Tanggal Lahir')" :value="old('birth_date', $candidate->birth_date?->format('Y-m-d'))" />

                <flux:select name="gender" :label="__('Jenis Kelamin')">
                    <option value="">-- Pilih --</option>
                    <option value="Laki-laki" @selected(old('gender', $candidate->gender) === 'Laki-laki')>Laki-laki</option>
                    <option value="Perempuan" @selected(old('gender', $candidate->gender) === 'Perempuan')>Perempuan</option>
                </flux:select>

                <flux:textarea name="address" :label="__('Alamat')" :value="old('address', $candidate->address)" />
                <flux:textarea name="vision" :label="__('Visi')" :value="old('vision', $candidate->vision)" rows="3" />
                <flux:textarea name="mission" :label="__('Misi')" :value="old('mission', $candidate->mission)" rows="5" />
                <flux:input name="photo" type="file" :label="__('Ganti Foto Calon')" accept="image/*" description="Format JPG/PNG, maks 2 MB. Kosongkan jika tidak diganti." />

                <div class="flex justify-end">
                    <flux:button variant="primary" type="submit">{{ __('Simpan Perubahan') }}</flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
