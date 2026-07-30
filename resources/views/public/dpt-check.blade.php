<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-zinc-50 dark:bg-zinc-900">
    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-16">
        <div class="w-full max-w-md">
            <div class="mb-8 text-center">
                <flux:heading size="xl" level="1">Cek Status DPT</flux:heading>
                <flux:subheading>Masukkan NIK untuk memeriksa apakah Anda terdaftar sebagai Pemilih Tetap (DPT).</flux:subheading>
            </div>

            <div class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-2xl p-6">
                <form action="{{ route('public.dpt-check.store') }}" method="POST" class="flex flex-col gap-4">
                    @csrf
                    <flux:input
                        name="nik"
                        :label="__('Nomor Induk Kependudukan (NIK)')"
                        placeholder="16 digit NIK"
                        maxlength="16"
                        pattern="\d{16}"
                        inputmode="numeric"
                        :value="old('nik')"
                        required
                    />

                    @error('nik')
                        <flux:callout variant="danger" icon="exclamation-triangle">
                            <flux:callout.text>{{ $message }}</flux:callout.text>
                        </flux:callout>
                    @enderror

                    <flux:button variant="primary" type="submit" class="w-full">Cek Status DPT</flux:button>
                </form>
            </div>

            @if($submitted ?? false)
            <div class="mt-6">
                @if($voter)
                    <div class="bg-green-50 dark:bg-green-950 border border-green-200 dark:border-green-800 rounded-2xl p-6">
                        <div class="flex items-center gap-2 mb-4">
                            <flux:icon.check-circle class="size-6 text-green-600 dark:text-green-400" />
                            <flux:heading level="3" class="text-green-800 dark:text-green-300">{{ $voter['status'] }}</flux:heading>
                        </div>
                        <dl class="grid grid-cols-2 gap-y-2 text-sm">
                            <dt class="text-zinc-600 dark:text-zinc-400">Nama</dt>
                            <dd class="font-medium">{{ $voter['name'] }}</dd>
                            <dt class="text-zinc-600 dark:text-zinc-400">NIK</dt>
                            <dd class="font-mono">{{ $voter['nik_masked'] }}</dd>
                            <dt class="text-zinc-600 dark:text-zinc-400">TPS</dt>
                            <dd>{{ $voter['tps_label'] ?? '-' }}</dd>
                            <dt class="text-zinc-600 dark:text-zinc-400">RT/RW</dt>
                            <dd>{{ $voter['rt'] ?? '-' }} / {{ $voter['rw'] ?? '-' }}</dd>
                            <dt class="text-zinc-600 dark:text-zinc-400">Periode</dt>
                            <dd>{{ $voter['period'] }}</dd>
                        </dl>
                    </div>
                @else
                    <div class="bg-red-50 dark:bg-red-950 border border-red-200 dark:border-red-800 rounded-2xl p-6">
                        <div class="flex items-center gap-2">
                            <flux:icon.x-circle class="size-6 text-red-600 dark:text-red-400" />
                            <flux:heading level="3" class="text-red-800 dark:text-red-300">Tidak Terdaftar</flux:heading>
                        </div>
                        <flux:text class="mt-2 text-sm text-red-700 dark:text-red-400">
                            NIK yang Anda masukkan tidak ditemukan dalam Daftar Pemilih Tetap (DPT). Hubungi panitia untuk informasi lebih lanjut.
                        </flux:text>
                    </div>
                @endif
            </div>
            @endif

            <p class="mt-6 text-center text-xs text-zinc-500">
                NIK Anda tidak akan disimpan. Pencarian dilakukan secara aman.
                <a href="{{ route('home') }}" class="underline hover:text-zinc-700">← Kembali ke Beranda</a>
            </p>
        </div>
    </div>
    @fluxScripts
</body>
</html>
