<x-layouts::app :title="__('Log Aktivitas')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <flux:heading size="xl" level="1">Log Aktivitas</flux:heading>
        <flux:subheading>Jejak audit semua perubahan data penting.</flux:subheading>

        <flux:separator class="my-4" />

        <div class="overflow-hidden border border-zinc-200 dark:border-zinc-700 rounded-xl">
            <table class="w-full text-sm text-left">
                <thead class="bg-zinc-50 dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700">
                    <tr>
                        <th class="px-4 py-3">Waktu</th>
                        <th class="px-4 py-3">Pengguna</th>
                        <th class="px-4 py-3">Aktivitas</th>
                        <th class="px-4 py-3">Subyek</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($logs as $log)
                    <tr class="bg-white dark:bg-zinc-900">
                        <td class="px-4 py-3 whitespace-nowrap text-zinc-500">{{ $log->created_at->format('d M Y H:i') }}</td>
                        <td class="px-4 py-3">{{ $log->causer?->name ?? 'Sistem' }}</td>
                        <td class="px-4 py-3">{{ $log->description }}</td>
                        <td class="px-4 py-3 text-xs text-zinc-500">{{ class_basename($log->subject_type ?? '') }} #{{ $log->subject_id }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-zinc-500">Belum ada log aktivitas.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $logs->links() }}</div>
    </div>
</x-layouts::app>
