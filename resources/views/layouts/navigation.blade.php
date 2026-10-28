@php
    $links = [
        ['route' => 'peminjamen.index', 'active' => 'peminjamen.*', 'label' => 'Peminjaman', 'show' => true],
        ['route' => 'bukus.index', 'active' => 'bukus.*', 'label' => 'Buku', 'show' => true],
        ['route' => 'category.index', 'active' => 'category.*', 'label' => 'Kategori', 'show' => true],
        ['route' => 'user.index', 'active' => 'user.*', 'label' => 'Anggota', 'show' => Gate::allows('admin')],
    ];
    $initial = mb_strtoupper(mb_substr(Auth::user()->name, 0, 1));
@endphp

<nav x-data="{
        open: false,
        dark: document.documentElement.classList.contains('dark'),
        toggleTheme() {
            this.dark = !this.dark;
            document.documentElement.classList.toggle('dark', this.dark);
            try { localStorage.setItem('theme', this.dark ? 'dark' : 'light'); } catch (e) {}
        }
    }"
    class="sticky top-0 z-30 border-b border-gray-200 bg-white/90 backdrop-blur dark:border-gray-700 dark:bg-gray-800/90">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">

            <div class="flex items-center gap-8">
                <a href="{{ route('peminjamen.index') }}" class="flex items-center gap-2.5">
                    <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600 text-white">
                        <svg class="h-4.5 w-4.5" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 19.5V5a2 2 0 0 1 2-2h13v16H6a2 2 0 0 0-2 2" />
                            <path d="M4 19.5A2.5 2.5 0 0 0 6.5 22H19" />
                        </svg>
                    </span>
                    <span class="text-base font-semibold tracking-tight text-gray-900 dark:text-white">Perpustakaan</span>
                </a>

                <div class="hidden items-center gap-1 sm:flex">
                    @foreach ($links as $link)
                        @if ($link['show'])
                            <a href="{{ route($link['route']) }}"
                                @class([
                                    'rounded-lg px-3 py-2 text-sm font-medium transition-colors',
                                    'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white' => request()->routeIs($link['active']),
                                    'text-gray-500 hover:bg-gray-50 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700/60 dark:hover:text-white' => !request()->routeIs($link['active']),
                                ])
                                @if (request()->routeIs($link['active'])) aria-current="page" @endif>
                                {{ __($link['label']) }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:gap-2">
                <button type="button" @click="toggleTheme()" :aria-label="dark ? 'Ganti ke mode terang' : 'Ganti ke mode gelap'"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200">
                    <span x-show="!dark"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8Z" /></svg></span>
                    <span x-show="dark" style="display: none;"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4" /><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" /></svg></span>
                </button>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center gap-2 rounded-lg px-2 py-1.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 focus:outline-none dark:text-gray-200 dark:hover:bg-gray-700/60">
                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-indigo-100 text-xs font-semibold text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300">
                                {{ $initial }}
                            </span>
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="border-b border-gray-100 px-4 py-2 dark:border-gray-700">
                            <p class="truncate text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="flex items-center gap-1 sm:hidden">
                <button type="button" @click="toggleTheme()" :aria-label="dark ? 'Ganti ke mode terang' : 'Ganti ke mode gelap'"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200">
                    <span x-show="!dark"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8Z" /></svg></span>
                    <span x-show="dark" style="display: none;"><svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4" /><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41" /></svg></span>
                </button>
                <button @click="open = ! open" type="button" aria-label="Buka menu" :aria-expanded="open"
                    class="inline-flex items-center justify-center rounded-lg p-2 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 focus:outline-none dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                        <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path x-show="open" style="display: none;" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="open" x-transition style="display: none;" class="border-t border-gray-200 sm:hidden dark:border-gray-700">
        <div class="space-y-1 px-4 py-3">
            @foreach ($links as $link)
                @if ($link['show'])
                    <a href="{{ route($link['route']) }}"
                        @class([
                            'block rounded-lg px-3 py-2 text-base font-medium',
                            'bg-gray-100 text-gray-900 dark:bg-gray-700 dark:text-white' => request()->routeIs($link['active']),
                            'text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700/60' => !request()->routeIs($link['active']),
                        ])>
                        {{ __($link['label']) }}
                    </a>
                @endif
            @endforeach
        </div>

        <div class="border-t border-gray-200 px-4 py-3 dark:border-gray-700">
            <div class="flex items-center gap-3 px-3 py-2">
                <span class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 text-sm font-semibold text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300">
                    {{ $initial }}
                </span>
                <div class="min-w-0">
                    <div class="truncate text-sm font-medium text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</div>
                    <div class="truncate text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-2 space-y-1">
                <a href="{{ route('profile.edit') }}"
                    class="block rounded-lg px-3 py-2 text-base font-medium text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700/60">
                    {{ __('Profile') }}
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="block w-full rounded-lg px-3 py-2 text-left text-base font-medium text-gray-600 hover:bg-gray-50 dark:text-gray-300 dark:hover:bg-gray-700/60">
                        {{ __('Log Out') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>