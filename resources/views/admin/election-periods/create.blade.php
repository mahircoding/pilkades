<x-layouts::app :title="__('Tambah Periode Pemilihan')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" level="1">Tambah Periode</flux:heading>
                <flux:subheading>Buat jadwal Pilkades baru.</flux:subheading>
            </div>
            <flux:button :href="route('admin.election-periods.index')" icon="arrow-left" wire:navigate>Kembali</flux:button>
        </div>
        
        <flux:separator class="my-4" />

        <div class="max-w-2xl">
            <form action="{{ route('admin.election-periods.store') }}" method="POST" class="flex flex-col gap-6">
                @csrf

                <flux:input name="name" :label="__('Nama Periode (Contoh: Pilkades 2026)')" :value="old('name')" required />
                <flux:input name="election_date" type="date" :label="__('Tanggal Pemungutan Suara')" :value="old('election_date')" />
                
                <div class="grid grid-cols-2 gap-4">
                    <flux:input name="registration_starts_at" type="datetime-local" :label="__('Mulai Pendaftaran Calon')" :value="old('registration_starts_at')" />
                    <flux:input name="registration_ends_at" type="datetime-local" :label="__('Tutup Pendaftaran Calon')" :value="old('registration_ends_at')" />
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <flux:input name="dps_starts_at" type="datetime-local" :label="__('Mulai Masa DPS')" :value="old('dps_starts_at')" />
                    <flux:input name="dps_ends_at" type="datetime-local" :label="__('Tutup Masa DPS (DPT Ditetapkan)')" :value="old('dps_ends_at')" />
                </div>
                
                <div class="flex justify-end">
                    <flux:button variant="primary" type="submit">{{ __('Simpan') }}</flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
