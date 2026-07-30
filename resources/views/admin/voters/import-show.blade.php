<x-layouts::app :title="__('Hasil Impor DPS')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" level="1">Hasil Impor: {{ $batch->original_name }}</flux:heading>
                <flux:subheading>{{ $batch->electionPeriod->name }} — diunggah oleh {{ $batch->uploader?->name ?? 'Sistem' }}</flux:subheading>
            </div>
            <flux:button :href="route('admin.voters.index')" icon="arrow-left" wire:navigate>Kembali</flux:button>
        </div>

        <flux:separator class="my-4" />

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 text-center">
                <div class="text-2xl font-bold">{{ $batch->total_rows }}</div>
                <div class="text-xs text-zinc-500 mt-1">Total Baris</div>
            </div>
            <div class="border border-green-200 dark:border-green-800 bg-green-50 dark:bg-green-950 rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-green-700 dark:text-green-400">{{ $batch->accepted_rows }}</div>
                <div class="text-xs text-green-600 dark:text-green-500 mt-1">Diterima</div>
            </div>
            <div class="border border-yellow-200 dark:border-yellow-800 bg-yellow-50 dark:bg-yellow-950 rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-yellow-700 dark:text-yellow-400">{{ $batch->duplicate_rows }}</div>
                <div class="text-xs text-yellow-600 dark:text-yellow-500 mt-1">Duplikat</div>
            </div>
            <div class="border border-red-200 dark:border-red-800 bg-red-50 dark:bg-red-950 rounded-xl p-4 text-center">
                <div class="text-2xl font-bold text-red-700 dark:text-red-400">{{ $batch->invalid_rows }}</div>
                <div class="text-xs text-red-600 dark:text-red-500 mt-1">Tidak Valid</div>
            </div>
        </div>

        @if($issues->total() > 0)
        <flux:heading level="3" size="lg" class="mt-4">Daftar Masalah ({{ $issues->total() }} baris)</flux:heading>
        <div class="overflow-hidden border border-zinc-200 dark:border-zinc-700 rounded-xl">
            <table class="w-full text-sm text-left">
                <thead class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <th class="px-4 py-3">Baris</th>
                        <th class="px-4 py-3">NIK (Tersamar)</th>
                        <th class="px-4 py-3">Tipe</th>
                        <th class="px-4 py-3">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach($issues as $issue)
                    <tr class="bg-white dark:bg-zinc-900">
                        <td class="px-4 py-3">{{ $issue->row_number }}</td>
                        <td class="px-4 py-3 font-mono text-xs">{{ $issue->nik_masked ?? '—' }}</td>
                        <td class="px-4 py-3">
                            <flux:badge color="{{ $issue->type === 'invalid' ? 'red' : 'yellow' }}" size="sm">
                                {{ match($issue->type) {
                                    'invalid' => 'Tidak Valid',
                                    'duplicate_file' => 'Duplikat (File)',
                                    'duplicate_db' => 'Duplikat (DB)',
                                    default => $issue->type,
                                } }}
                            </flux:badge>
                        </td>
                        <td class="px-4 py-3 text-zinc-600 dark:text-zinc-400">{{ $issue->message }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $issues->links() }}</div>
        @endif
    </div>
</x-layouts::app>
