<x-layouts::app :title="__('Impor DPS')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" level="1">Impor Data Pemilih (DPS)</flux:heading>
                <flux:subheading>Unggah file Excel dengan format: NIK | Nama | Jenis Kelamin | Tempat Lahir | Tgl Lahir | Alamat | RT | RW | TPS</flux:subheading>
            </div>
            <flux:button :href="route('admin.voters.index')" icon="arrow-left" wire:navigate>Kembali</flux:button>
        </div>

        <flux:separator class="my-4" />

        <div class="max-w-xl">
            <form action="{{ route('admin.voters.import-store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-6">
                @csrf

                <flux:select name="election_period_id" :label="__('Periode Pilkades')">
                    @foreach($periods as $period)
                        <option value="{{ $period->id }}" @selected($period->is_active)>{{ $period->name }}</option>
                    @endforeach
                </flux:select>

                <flux:input name="file" type="file" :label="__('File Excel DPS')" accept=".xlsx,.xls,.csv" required
                    description="Format .xlsx/.xls/.csv, maks 10 MB. Baris pertama dianggap header." />

                <div class="flex justify-end">
                    <flux:button variant="primary" type="submit" icon="arrow-up-tray">Unggah & Proses</flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts::app>
