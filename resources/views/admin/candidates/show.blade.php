<x-layouts::app :title="__('Detail Calon')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="xl" level="1">{{ $candidate->name }}</flux:heading>
                <flux:subheading>{{ $candidate->electionPeriod->name }}</flux:subheading>
            </div>
            <div class="flex gap-2">
                @can('manage candidates')
                <flux:button :href="route('admin.candidates.edit', $candidate)" icon="pencil" wire:navigate>Edit</flux:button>
                @endcan
                <flux:button :href="route('admin.candidates.index')" icon="arrow-left" wire:navigate>Kembali</flux:button>
            </div>
        </div>

        <flux:separator class="my-4" />

        @if (session('status'))
            <flux:callout variant="success" icon="check-circle">
                <flux:callout.text>{{ session('status') }}</flux:callout.text>
            </flux:callout>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- Info utama --}}
            <div class="md:col-span-2 flex flex-col gap-4">
                <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 flex flex-col gap-3">
                    <flux:heading level="3" size="lg">Data Diri</flux:heading>
                    <dl class="grid grid-cols-2 gap-2 text-sm">
                        <dt class="text-zinc-500">No Urut</dt>
                        <dd>{{ $candidate->ballot_number ?? '-' }}</dd>
                        <dt class="text-zinc-500">NIK</dt>
                        <dd class="font-mono">{{ $candidate->nik_masked ?? '—' }}</dd>
                        <dt class="text-zinc-500">Tempat, Tgl Lahir</dt>
                        <dd>{{ $candidate->birth_place }}, {{ $candidate->birth_date?->format('d M Y') }}</dd>
                        <dt class="text-zinc-500">Jenis Kelamin</dt>
                        <dd>{{ $candidate->gender ?? '-' }}</dd>
                        <dt class="text-zinc-500">Alamat</dt>
                        <dd>{{ $candidate->address ?? '-' }}</dd>
                        <dt class="text-zinc-500">Status</dt>
                        <dd>
                            <flux:badge color="{{ match($candidate->verification_status) {
                                'verified' => 'green', 'rejected' => 'red', default => 'yellow',
                            } }}">
                                {{ match($candidate->verification_status) {
                                    'verified' => 'Terverifikasi', 'rejected' => 'Ditolak', default => 'Menunggu Verifikasi',
                                } }}
                            </flux:badge>
                        </dd>
                    </dl>
                </div>

                @if($candidate->vision || $candidate->mission)
                <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 flex flex-col gap-3">
                    <flux:heading level="3" size="lg">Visi & Misi</flux:heading>
                    @if($candidate->vision)
                        <flux:subheading>Visi</flux:subheading>
                        <flux:text>{{ $candidate->vision }}</flux:text>
                    @endif
                    @if($candidate->mission)
                        <flux:subheading>Misi</flux:subheading>
                        <flux:text class="whitespace-pre-line">{{ $candidate->mission }}</flux:text>
                    @endif
                </div>
                @endif

                {{-- Verifikasi --}}
                @can('verify candidates')
                @if($candidate->verification_status === 'submitted')
                <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl p-4">
                    <flux:heading level="3" size="lg" class="mb-4">Verifikasi Calon</flux:heading>
                    <form action="{{ route('admin.candidates.verify', $candidate) }}" method="POST" class="flex flex-col gap-4">
                        @csrf
                        <flux:select name="verification_status" :label="__('Keputusan')">
                            <option value="verified">Terverifikasi / Ditetapkan</option>
                            <option value="rejected">Ditolak</option>
                        </flux:select>
                        <flux:textarea name="verification_notes" :label="__('Catatan (opsional)')" rows="2" />
                        <flux:button variant="primary" type="submit">Simpan Keputusan</flux:button>
                    </form>
                </div>
                @endif
                @endcan
            </div>

            {{-- Dokumen --}}
            <div class="flex flex-col gap-4">
                <div class="border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 flex flex-col gap-3">
                    <flux:heading level="3" size="lg">Dokumen</flux:heading>
                    @forelse($candidate->documents as $doc)
                        <div class="flex items-center justify-between gap-2 text-sm">
                            <span class="truncate">{{ $doc->type }} — {{ $doc->original_name }}</span>
                            <flux:badge color="{{ $doc->verification_status === 'verified' ? 'green' : ($doc->verification_status === 'rejected' ? 'red' : 'yellow') }}" size="sm">
                                {{ ucfirst($doc->verification_status) }}
                            </flux:badge>
                        </div>
                    @empty
                        <flux:text class="text-zinc-500 text-sm">Belum ada dokumen.</flux:text>
                    @endforelse

                    @can('manage candidates')
                    <flux:separator class="my-2" />
                    <form action="{{ route('admin.candidates.documents.store', $candidate) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-3">
                        @csrf
                        <flux:input name="type" :label="__('Jenis Dokumen')" placeholder="Contoh: KTP, Ijazah, SKCK" required />
                        <flux:input name="file" type="file" :label="__('File')" accept=".pdf,.jpg,.jpeg,.png" required />
                        <flux:button variant="filled" type="submit" size="sm">Unggah Dokumen</flux:button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-layouts::app>
