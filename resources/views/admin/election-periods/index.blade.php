<x-layouts::app :title="__('Periode Pemilihan')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" level="1">Periode Pemilihan</flux:heading>
                <flux:subheading>Kelola tahapan Pilkades.</flux:subheading>
            </div>
            <flux:button :href="route('admin.election-periods.create')" variant="primary" icon="plus" wire:navigate>Tambah Periode</flux:button>
        </div>
        
        <flux:separator class="my-4" />

        @if (session('status'))
            <flux:toast variant="success" :text="session('status')" />
        @endif

        <div class="overflow-hidden border border-zinc-200 dark:border-zinc-700 rounded-xl">
            <table class="w-full text-sm text-left">
                <thead class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <th class="px-4 py-3">Nama</th>
                        <th class="px-4 py-3">Hari H</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Aktif</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse ($periods as $period)
                        <tr class="bg-white dark:bg-zinc-900">
                            <td class="px-4 py-3 font-medium">{{ $period->name }}</td>
                            <td class="px-4 py-3">{{ $period->election_date?->format('d M Y') ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <flux:badge color="{{ $period->status === 'active' ? 'green' : ($period->status === 'closed' ? 'zinc' : 'yellow') }}">
                                    {{ ucfirst($period->status) }}
                                </flux:badge>
                            </td>
                            <td class="px-4 py-3">
                                @if($period->is_active)
                                    <flux:icon.check-circle class="size-5 text-green-500" />
                                @else
                                    <flux:icon.minus-circle class="size-5 text-zinc-400" />
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <flux:button size="sm" :href="route('admin.election-periods.edit', $period)" wire:navigate>Edit</flux:button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-zinc-500">Belum ada data periode pemilihan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $periods->links() }}
        </div>
    </div>
</x-layouts::app>
