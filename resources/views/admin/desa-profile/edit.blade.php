<x-layouts::app :title="__('Profil Desa')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl" level="1">Profil Desa</flux:heading>
        <flux:subheading>Informasi master desa untuk aplikasi SIPILKADES.</flux:subheading>
        
        <flux:separator class="my-4" />

        @if (session('status'))
            <flux:toast variant="success" :text="session('status')" />
        @endif

        <div class="max-w-2xl">
            <form action="{{ route('admin.desa-profile.update') }}" method="POST" class="flex flex-col gap-6">
                @csrf
                @method('PUT')

                <flux:input name="name" :label="__('Nama Desa')" :value="old('name', $profile->name)" required />
                <flux:input name="head_name" :label="__('Nama Kepala Desa / Pj')" :value="old('head_name', $profile->head_name)" />
                <flux:input name="district" :label="__('Kecamatan')" :value="old('district', $profile->district)" />
                <flux:input name="regency" :label="__('Kabupaten')" :value="old('regency', $profile->regency)" />
                <flux:input name="province" :label="__('Provinsi')" :value="old('province', $profile->province)" />
                <flux:input name="postal_code" :label="__('Kode Pos')" :value="old('postal_code', $profile->postal_code)" />
                <flux:textarea name="address" :label="__('Alamat Kantor Desa')" :value="old('address', $profile->address)" />
                
                <div class="flex justify-end">
                    <flux:button variant="primary" type="submit">{{ __('Simpan Profil') }}</flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
