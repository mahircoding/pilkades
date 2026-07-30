<x-layouts::app :title="__('DPS / DPT')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" level="1">DPS / DPT</flux:heading>
                <flux:subheading>Data Pemilih Sementara dan Tetap.</flux:subheading>
            </div>
            <div class="flex gap-2">
                @can('import voters')
                <flux:button :href="route('admin.voters.import-create')" icon="arrow-up-tray" wire:navigate>Impor DPS</flux:button>
                @endcan
                @can('manage voters')
                <form action="{{ route('admin.voters.promote-dpt') }}" method="POST">
                    @csrf
                    <input type="hidden" name="election_period_id" value="{{ $activePeriod?->id }}">
                    <flux:button variant="primary" type="submit" icon="check-badge"
                        onclick="return confirm('Promosikan semua DPS ke DPT?')">
                        Promosi ke DPT
                    </flux:button>
                </form>
                @endcan
            </div>
        </div>

        <flux:separator class="my-4" />

        @if (session('status'))
            <flux:callout variant="success" icon="check-circle">
                <flux:callout.text>{{ session('status') }}</flux:callout.text>
            </flux:callout>
        @endif

        @if($activePeriod)
            <flux:callout variant="info" icon="information-circle">
                <flux:callout.text>Periode aktif: <strong>{{ $activePeriod->name }}</strong></flux:callout.text>
            </flux:callout>
        @endif

        <div class="overflow-hidden border border-zinc-200 dark:border-zinc-700 rounded-xl">
            <table class="w-full text-sm text-left">
                <thead class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">NIK (Tersamar)</th>
                        <th class="px-4 py-3">TPS</th>
                        <th class="px-4 py-3">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse ($voters as $voter)
                        <tr class="bg-white dark:bg-zinc-900">
                            <td class="px-4 py-3 font-medium">{{ $voter->name }}</td>
                            <td class="px-4 py-3 font-mono text-xs">{{ $voter->nik_masked }}</td>
                            <td class="px-4 py-3">{{ $voter->tps_label ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <flux:badge color="{{ $voter->list_status === 'dpt' ? 'green' : 'yellow' }}">
                                    {{ strtoupper($voter->list_status) }}
                                </flux:badge>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-zinc-500">Belum ada data pemilih. Silakan impor file Excel DPS.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $voters->links() }}</div>
    </div>
</x-layouts::app>
