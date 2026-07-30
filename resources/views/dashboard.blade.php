<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl" level="1">Dashboard</flux:heading>
        <flux:subheading>Ringkasan status Pilkades.</flux:subheading>

        @if($activePeriod)
            <flux:callout variant="info" icon="calendar-days">
                <flux:callout.text>
                    Periode aktif: <strong>{{ $activePeriod->name }}</strong>
                    @if($activePeriod->election_date)
                        — Hari-H: {{ $activePeriod->election_date->format('d M Y') }}
                    @endif
                </flux:callout.text>
            </flux:callout>
        @else
            <flux:callout variant="warning" icon="exclamation-triangle">
                <flux:callout.text>Belum ada periode Pilkades yang aktif. <a href="{{ route('admin.election-periods.index') }}" class="underline" wire:navigate>Atur periode sekarang →</a></flux:callout.text>
            </flux:callout>
        @endif

        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5">
                <flux:text class="text-xs text-zinc-500 uppercase tracking-wide">Calon Terdaftar</flux:text>
                <div class="mt-1 text-3xl font-bold">{{ $totalCandidates }}</div>
                <div class="mt-1 text-sm text-zinc-500">{{ $verifiedCandidates }} terverifikasi</div>
            </div>

            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5">
                <flux:text class="text-xs text-zinc-500 uppercase tracking-wide">Pemilih DPS</flux:text>
                <div class="mt-1 text-3xl font-bold">{{ $totalDps }}</div>
                <div class="mt-1 text-sm text-zinc-500">Data Pemilih Sementara</div>
            </div>

            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5">
                <flux:text class="text-xs text-zinc-500 uppercase tracking-wide">Pemilih DPT</flux:text>
                <div class="mt-1 text-3xl font-bold">{{ $totalDpt }}</div>
                <div class="mt-1 text-sm text-zinc-500">Data Pemilih Tetap</div>
            </div>
        </div>

        @if($latestImport)
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5">
            <flux:heading level="3" size="lg" class="mb-3">Impor Terakhir</flux:heading>
            <dl class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <dt class="text-zinc-500">File</dt>
                    <dd class="font-medium truncate">{{ $latestImport->original_name }}</dd>
                </div>
                <div>
                    <dt class="text-zinc-500">Status</dt>
                    <dd>
                        <flux:badge color="{{ $latestImport->status === 'completed' ? 'green' : ($latestImport->status === 'failed' ? 'red' : 'yellow') }}">
                            {{ ucfirst($latestImport->status) }}
                        </flux:badge>
                    </dd>
                </div>
                <div>
                    <dt class="text-zinc-500">Diterima</dt>
                    <dd class="font-medium">{{ $latestImport->accepted_rows }} / {{ $latestImport->total_rows }}</dd>
                </div>
                <div>
                    <dt class="text-zinc-500">Waktu</dt>
                    <dd class="text-zinc-500">{{ $latestImport->created_at->diffForHumans() }}</dd>
                </div>
            </dl>
        </div>
        @endif
    </div>
</x-layouts::app>
