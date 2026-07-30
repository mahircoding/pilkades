<x-layouts::app :title="__('Calon Kepala Desa')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" level="1">Calon Kepala Desa</flux:heading>
                <flux:subheading>Data bakal calon dan status verifikasi.</flux:subheading>
            </div>
            @can('manage candidates')
            <flux:button :href="route('admin.candidates.create')" variant="primary" icon="plus" wire:navigate>Tambah Calon</flux:button>
            @endcan
        </div>

        <flux:separator class="my-4" />

        @if (session('status'))
            <flux:callout variant="success" icon="check-circle">
                <flux:callout.text>{{ session('status') }}</flux:callout.text>
            </flux:callout>
        @endif

        @if($activePeriod)
            <flux:callout variant="info" icon="information-circle">
                <flux:callout.text>Menampilkan calon untuk periode: <strong>{{ $activePeriod->name }}</strong></flux:callout.text>
            </flux:callout>
        @endif

        <div class="overflow-hidden border border-zinc-200 dark:border-zinc-700 rounded-xl">
            <table class="w-full text-sm text-left">
                <thead class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <th class="px-4 py-3">No Urut</th>
                        <th class="px-4 py-3">Nama Calon</th>
                        <th class="px-4 py-3">Periode</th>
                        <th class="px-4 py-3">Status Verifikasi</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse ($candidates as $candidate)
                        <tr class="bg-white dark:bg-zinc-900">
                            <td class="px-4 py-3">{{ $candidate->ballot_number ?? '-' }}</td>
                            <td class="px-4 py-3 font-medium">{{ $candidate->name }}</td>
                            <td class="px-4 py-3 text-zinc-500 text-xs">{{ $candidate->electionPeriod->name }}</td>
                            <td class="px-4 py-3">
                                <flux:badge color="{{ match($candidate->verification_status) {
                                    'verified' => 'green',
                                    'rejected' => 'red',
                                    default    => 'yellow',
                                } }}">
                                    {{ match($candidate->verification_status) {
                                        'verified' => 'Terverifikasi',
                                        'rejected' => 'Ditolak',
                                        default    => 'Menunggu',
                                    } }}
                                </flux:badge>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <flux:button size="sm" :href="route('admin.candidates.show', $candidate)" wire:navigate>Detail</flux:button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-zinc-500">Belum ada data calon.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $candidates->links() }}</div>
    </div>
</x-layouts::app>
