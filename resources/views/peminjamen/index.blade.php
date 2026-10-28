@php use Carbon\Carbon; @endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Peminjaman</h2>
    </x-slot>

    @php
        $today = now()->startOfDay();
        $covers = ['bg-teal-600', 'bg-indigo-600', 'bg-amber-500', 'bg-rose-500', 'bg-emerald-600', 'bg-sky-600'];

        $items = $peminjamen->map(function ($p) use ($today) {
            $due = Carbon::parse($p->return_date)->startOfDay();
            $days = (int) $today->diffInDays($due, false);
            $status = $days < 0 ? 'late' : ($days <= 2 ? 'soon' : 'ok');

            $percent = null;
            if ($p->created_at) {
                $start = $p->created_at->copy()->startOfDay();
                $span = max(1, (int) $start->diffInDays($due, false));
                $elapsed = max(0, (int) $start->diffInDays($today, false));
                $percent = (int) min(100, round($elapsed / $span * 100));
            }

            return (object) [
                'model' => $p,
                'due' => $due,
                'days' => $days,
                'status' => $status,
                'percent' => $percent,
            ];
        });

        $total = $items->count();
        $overdue = $items->where('status', 'late')->count();
        $soon = $items->where('status', 'soon')->count();
    @endphp

    <div class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

            @if (session()->has('status'))
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                    class="mb-6 rounded-lg bg-green-50 px-4 py-3 text-sm text-green-700 dark:bg-green-500/10 dark:text-green-400"
                    role="alert">
                    {{ session()->get('status') }}
                </div>
            @endif

            @if ($total > 0)
                <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-500/10 dark:text-indigo-300">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 19.5V5a2 2 0 0 1 2-2h13v16H6a2 2 0 0 0-2 2" /><path d="M4 19.5A2.5 2.5 0 0 0 6.5 22H19" /></svg>
                        </span>
                        <div>
                            <p class="text-2xl font-semibold tabular-nums leading-none text-gray-900 dark:text-white">{{ $total }}</p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Sedang dipinjam</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-600 dark:bg-amber-500/10 dark:text-amber-400">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path d="M12 7v5l3 2" /></svg>
                        </span>
                        <div>
                            <p class="text-2xl font-semibold tabular-nums leading-none text-gray-900 dark:text-white">{{ $soon }}</p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Segera jatuh tempo</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-800">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-400">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 9v4" /><path d="M12 17h.01" /><path d="M10.3 3.9 1.8 18a2 2 0 0 0 1.7 3h17a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z" /></svg>
                        </span>
                        <div>
                            <p class="text-2xl font-semibold tabular-nums leading-none {{ $overdue > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">{{ $overdue }}</p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Terlambat</p>
                        </div>
                    </div>
                </div>

                <div x-data="{ tab: 'all' }">
                    <div class="mb-4 inline-flex rounded-lg bg-gray-100 p-1 dark:bg-gray-800" role="tablist">
                        @foreach ([['all', 'Semua', $total], ['soon', 'Segera jatuh tempo', $soon], ['late', 'Terlambat', $overdue]] as [$key, $label, $count])
                            <button type="button" role="tab" @click="tab = '{{ $key }}'" :aria-selected="tab === '{{ $key }}'"
                                :class="tab === '{{ $key }}'
                                    ? 'bg-white text-gray-900 shadow-sm dark:bg-gray-700 dark:text-white'
                                    : 'text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200'"
                                class="rounded-md px-3 py-1.5 text-sm font-medium transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500">
                                {{ $label }}
                                <span class="ml-1 text-xs tabular-nums text-gray-400">{{ $count }}</span>
                            </button>
                        @endforeach
                    </div>

                    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
                        <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                            @foreach ($items as $item)
                                @php
                                    $p = $item->model;
                                    $days = $item->days;

                                    if ($item->status === 'late') {
                                        $statusText = 'Terlambat ' . abs($days) . ' hari';
                                        $badge = 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-500/10 dark:text-red-400 dark:ring-red-400/20';
                                        $bar = 'bg-red-500';
                                        $edge = 'border-red-500';
                                    } elseif ($item->status === 'soon') {
                                        $statusText = $days === 0 ? 'Jatuh tempo hari ini' : $days . ' hari lagi';
                                        $badge = 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-500/10 dark:text-amber-400 dark:ring-amber-400/20';
                                        $bar = 'bg-amber-500';
                                        $edge = 'border-transparent';
                                    } else {
                                        $statusText = $days . ' hari lagi';
                                        $badge = 'bg-green-50 text-green-700 ring-green-600/20 dark:bg-green-500/10 dark:text-green-400 dark:ring-green-400/20';
                                        $bar = 'bg-green-500';
                                        $edge = 'border-transparent';
                                    }
                                @endphp

                                <li x-show="tab === 'all' || tab === '{{ $item->status === 'ok' ? 'none' : $item->status }}'"
                                    class="border-l-4 {{ $edge }} px-5 py-4 transition-colors hover:bg-gray-50 dark:hover:bg-gray-700/40">
                                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                        <div class="flex min-w-0 items-center gap-4">
                                            <span class="flex h-11 w-11 shrink-0 select-none items-center justify-center rounded-lg text-lg font-semibold text-white {{ $covers[$p->buku->id % count($covers)] }}">
                                                {{ mb_strtoupper(mb_substr($p->buku->title, 0, 1)) }}
                                            </span>
                                            <div class="min-w-0">
                                                <p class="truncate font-medium text-gray-900 dark:text-white">{{ $p->buku->title }}</p>
                                                <p class="mt-0.5 text-sm text-gray-500 dark:text-gray-400">No. peminjaman {{ $p->number_borrowed }}</p>
                                            </div>
                                        </div>

                                        <div class="flex items-center justify-between gap-6 sm:justify-end">
                                            <div class="sm:text-right">
                                                <p class="text-sm text-gray-900 dark:text-gray-100">{{ $item->due->copy()->locale('id')->translatedFormat('j F Y') }}</p>
                                                <span class="mt-1 inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset {{ $badge }}">
                                                    {{ $statusText }}
                                                </span>
                                            </div>

                                            <a href="{{ route('peminjamen.terminate', ['peminjaman' => $p->id]) }}"
                                                onclick="return confirm('Kembalikan buku ini?')"
                                                class="whitespace-nowrap rounded-lg px-3.5 py-2 text-sm font-medium transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2
                                                    {{ $item->status === 'late'
                                                        ? 'bg-indigo-600 text-white hover:bg-indigo-500'
                                                        : 'border border-gray-200 bg-white text-gray-700 hover:border-gray-300 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700' }}">
                                                Kembalikan
                                            </a>
                                        </div>
                                    </div>

                                    @if (!is_null($item->percent))
                                        <div class="mt-4" title="Masa pinjam terpakai {{ $item->percent }}%">
                                            <div class="h-1 w-full overflow-hidden rounded-full bg-gray-100 dark:bg-gray-700">
                                                <div class="h-full rounded-full {{ $bar }}" style="width: {{ $item->percent }}%"></div>
                                            </div>
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>

                        <p x-show="(tab === 'soon' && {{ $soon }} === 0) || (tab === 'late' && {{ $overdue }} === 0)" style="display: none;"
                            class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                            Tidak ada peminjaman di kategori ini.
                        </p>
                    </div>
                </div>
            @else
                <div class="rounded-xl border border-dashed border-gray-300 bg-white px-6 py-16 text-center dark:border-gray-600 dark:bg-gray-800">
                    <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 text-gray-400 dark:bg-gray-700">
                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 19.5V5a2 2 0 0 1 2-2h13v16H6a2 2 0 0 0-2 2" /><path d="M4 19.5A2.5 2.5 0 0 0 6.5 22H19" /></svg>
                    </span>
                    <h3 class="mt-4 text-base font-semibold text-gray-900 dark:text-white">Belum ada buku yang dipinjam</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Pilih buku dari katalog untuk mulai meminjam.</p>
                    <a href="{{ route('bukus.index') }}"
                        class="mt-6 inline-flex rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-indigo-500">
                        Lihat katalog buku
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>