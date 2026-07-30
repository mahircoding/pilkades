<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Utama')" class="grid">
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </flux:sidebar.item>
                </flux:sidebar.group>

                @canany(['manage desa profile', 'manage election periods', 'manage candidates', 'manage voters', 'import voters', 'view activity logs'])
                <flux:sidebar.group :heading="__('Administrasi')" class="grid">
                    @can('manage desa profile')
                    <flux:sidebar.item icon="building-office" :href="route('admin.desa-profile.edit')" :current="request()->routeIs('admin.desa-profile.*')" wire:navigate>
                        {{ __('Profil Desa') }}
                    </flux:sidebar.item>
                    @endcan

                    @can('manage election periods')
                    <flux:sidebar.item icon="calendar-days" :href="route('admin.election-periods.index')" :current="request()->routeIs('admin.election-periods.*')" wire:navigate>
                        {{ __('Periode Pilkades') }}
                    </flux:sidebar.item>
                    @endcan

                    @canany(['manage candidates', 'verify candidates'])
                    <flux:sidebar.item icon="user-group" :href="route('admin.candidates.index')" :current="request()->routeIs('admin.candidates.*')" wire:navigate>
                        {{ __('Calon Kepala Desa') }}
                    </flux:sidebar.item>
                    @endcanany

                    @canany(['manage voters', 'import voters'])
                    <flux:sidebar.item icon="clipboard-document-list" :href="route('admin.voters.index')" :current="request()->routeIs('admin.voters.*')" wire:navigate>
                        {{ __('DPS / DPT') }}
                    </flux:sidebar.item>
                    @endcanany

                    @can('view activity logs')
                    <flux:sidebar.item icon="queue-list" :href="route('admin.activity-logs.index')" :current="request()->routeIs('admin.activity-logs.*')" wire:navigate>
                        {{ __('Log Aktivitas') }}
                    </flux:sidebar.item>
                    @endcan
                </flux:sidebar.group>
                @endcanany
            </flux:sidebar.nav>

            <flux:spacer />

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
