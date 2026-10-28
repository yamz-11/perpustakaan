<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Buku') }}
        </h2>
    </x-slot>

    @php
        $covers = ['bg-teal-600', 'bg-indigo-600', 'bg-amber-500', 'bg-rose-500', 'bg-emerald-600', 'bg-sky-600'];
    @endphp

    <div class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8"
            x-data="{
                q: '',
                onlyAvail: false,
                matches(el) {
                    return el.dataset.search.includes(this.q.trim().toLowerCase())
                        && (!this.onlyAvail || el.dataset.available === '1');
                },
                get empty() {
                    return [...this.$refs.grid.querySelectorAll('[data-search]')].every(el => !this.matches(el));
                }
            }">

            <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex flex-1 flex-col gap-3 sm:flex-row sm:items-center">
                    <div class="relative w-full sm:max-w-sm">
                        <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                        <input type="search" x-model="q" placeholder="Cari judul, penulis, atau kategori"
                            class="w-full rounded-lg border-gray-200 bg-white py-2 pl-9 pr-3 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                    </div>
                    <label class="inline-flex cursor-pointer items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                        <input type="checkbox" x-model="onlyAvail" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-800">
                        Hanya yang tersedia
                    </label>
                </div>

                @can('admin')
                    <div>
                        <x-create-button href="{{ route('bukus.create') }}" />
                    </div>
                @endcan
            </div>

            @if (session('success'))
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                    class="mb-6 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700 dark:bg-green-500/10 dark:text-green-400">
                    {{ session('success') }}
                </p>
            @endif
            @if (session('danger'))
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                    class="mb-6 rounded-lg bg-red-50 px-4 py-3 text-sm text-red-700 dark:bg-red-500/10 dark:text-red-400">
                    {{ session('danger') }}
                </p>
            @endif

            <div x-ref="grid" class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse ($bukus as $buku)
                    @php
                        $total = $buku->copies_in_circulation;
                        $available = $buku->availableCopies();
                        $percent = $total > 0 ? min(100, round($available / $total * 100)) : 0;
                        $barColor = $available == 0
                            ? 'bg-gray-300 dark:bg-gray-600'
                            : ($available < 10 ? 'bg-red-500' : ($available < 20 ? 'bg-amber-500' : 'bg-green-500'));
                        $categoryTitle = $buku->category_id ? $buku->category->title : null;
                    @endphp

                    <article x-show="matches($el)" data-available="{{ $available > 0 ? '1' : '0' }}"
                        data-search="{{ mb_strtolower($buku->title . ' ' . $buku->author . ' ' . $categoryTitle) }}"
                        class="flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white transition-colors hover:border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:hover:border-gray-600">

                        <div class="relative flex h-36 items-end justify-between p-4 {{ $covers[$buku->id % count($covers)] }}">
                            <span class="select-none text-6xl font-semibold leading-none text-white/30">{{ mb_strtoupper(mb_substr($buku->title, 0, 1)) }}</span>
                            <span class="rounded-md bg-white/20 px-2 py-0.5 text-xs font-medium text-white">{{ $buku->year }}</span>
                        </div>

                        <div class="flex flex-1 flex-col p-4">
                            <h3 class="line-clamp-2 font-semibold leading-snug text-gray-900 dark:text-white">
                                <a href="{{ $buku->url }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">{{ $buku->title }}</a>
                            </h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $buku->author }}</p>

                            @if ($categoryTitle)
                                <span class="mt-3 inline-flex w-fit rounded-md bg-gray-100 px-2 py-0.5 text-xs font-medium text-gray-700 dark:bg-gray-700 dark:text-gray-200">
                                    {{ $categoryTitle }}
                                </span>
                            @endif

                            <div class="mt-4">
                                <div class="mb-1.5 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                    <span>Tersedia</span>
                                    <span class="font-medium text-gray-700 dark:text-gray-200">{{ $available }} dari {{ $total }}</span>
                                </div>
                                <div class="h-1.5 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700">
                                    <div class="h-full rounded-full {{ $barColor }}" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>

                            <div class="mt-5">
                                @if ($buku->canBeBorrowed())
                                    <a href="{{ route('peminjamen.create', ['buku' => $buku->id]) }}"
                                        class="block w-full rounded-lg bg-indigo-600 px-4 py-2 text-center text-sm font-medium text-white transition-colors hover:bg-indigo-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2">
                                        Pinjam buku
                                    </a>
                                @else
                                    <span class="block w-full cursor-not-allowed rounded-lg bg-gray-100 px-4 py-2 text-center text-sm font-medium text-gray-400 dark:bg-gray-700 dark:text-gray-500">
                                        Stok habis
                                    </span>
                                @endif
                            </div>

                            @can('admin')
                                <div class="mt-4 flex items-center justify-end gap-4 border-t border-gray-100 pt-3 dark:border-gray-700">
                                    <a href="{{ route('bukus.edit', $buku) }}"
                                        class="text-sm font-medium text-gray-600 hover:text-gray-900 dark:text-gray-300 dark:hover:text-white">
                                        Edit
                                    </a>
                                    <form action="{{ route('bukus.destroy', $buku) }}" method="POST"
                                        onsubmit="return confirm('Hapus buku ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-sm font-medium text-red-600 hover:text-red-500 dark:text-red-400 dark:hover:text-red-300">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            @endcan
                        </div>
                    </article>
                @empty
                    <p class="col-span-full rounded-xl border border-dashed border-gray-300 py-16 text-center text-gray-500 dark:border-gray-600 dark:text-gray-400">
                        Belum ada data buku.
                    </p>
                @endforelse
            </div>

            <p x-show="empty" x-cloak
                class="rounded-xl border border-dashed border-gray-300 py-16 text-center text-gray-500 dark:border-gray-600 dark:text-gray-400">
                Tidak ada buku yang cocok dengan pencarian.
            </p>
        </div>
    </div>
</x-app-layout>