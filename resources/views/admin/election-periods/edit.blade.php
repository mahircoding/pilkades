<x-layouts::app :title="__('Edit Periode Pemilihan')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" level="1">Edit Periode</flux:heading>
                <flux:subheading>Ubah jadwal dan status Pilkades.</flux:subheading>
            </div>
            <flux:button :href="route('admin.election-periods.index')" icon="arrow-left" wire:navigate>Kembali</flux:button>
        </div>
        
        <flux:separator class="my-4" />

        <div class="max-w-2xl">
            <form action="{{ route('admin.election-periods.update', $period) }}" method="POST" class="flex flex-col gap-6">
                @csrf
                @method('PUT')

                <flux:input name="name" :label="__('Nama Periode')" :value="old('name', $period->name)" required />
                <flux:input name="election_date" type="date" :label="__('Tanggal Pemungutan Suara')" :value="old('election_date', $period->election_date?->format('Y-m-d'))" />
                
                <div class="grid grid-cols-2 gap-4">
                    <flux:input name="registration_starts_at" type="datetime-local" :label="__('Mulai Pendaftaran Calon')" :value="old('registration_starts_at', $period->registration_starts_at?->format('Y-m-d\TH:i'))" />
                    <flux:input name="registration_ends_at" type="datetime-local" :label="__('Tutup Pendaftaran Calon')" :value="old('registration_ends_at', $period->registration_ends_at?->format('Y-m-d\TH:i'))" />
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <flux:input name="dps_starts_at" type="datetime-local" :label="__('Mulai Masa DPS')" :value="old('dps_starts_at', $period->dps_starts_at?->format('Y-m-d\TH:i'))" />
                    <flux:input name="dps_ends_at" type="datetime-local" :label="__('Tutup Masa DPS')" :value="old('dps_ends_at', $period->dps_ends_at?->format('Y-m-d\TH:i'))" />
                </div>
                
                <flux:separator />
                
                <flux:select name="status" :label="__('Status Tahapan')">
                    <option value="draft" @selected(old('status', $period->status) === 'draft')>Draft (Persiapan)</option>
                    <option value="active" @selected(old('status', $period->status) === 'active')>Aktif (Sedang Berjalan)</option>
                    <option value="closed" @selected(old('status', $period->status) === 'closed')>Selesai (Ditutup)</option>
                </flux:select>
                
                <flux:checkbox name="is_active" :label="__('Jadikan Periode Aktif Utama')" description="Hanya satu periode yang bisa aktif secara bersamaan." :checked="old('is_active', $period->is_active)" value="1" />
                
                <div class="flex justify-end">
                    <flux:button variant="primary" type="submit">{{ __('Simpan Perubahan') }}</flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
