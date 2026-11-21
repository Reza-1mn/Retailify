<x-app-layout>
    <x-slot name="header">
        <h1 class="text-base sm:text-lg font-extrabold text-slate-900">Manajemen Kategori</h1>
    </x-slot>

    <div x-data="{ search: '' }" class="w-full px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        <!-- Header Halaman -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-2xl bg-indigo-50 border border-indigo-200/70 text-indigo-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2 text-xs font-medium text-slate-400">
                        <span>Master Data</span>
                        <span>/</span>
                        <span class="text-indigo-600 font-semibold">Kategori</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">Manajemen Kategori</h2>
                </div>
            </div>

            <a href="{{ route('admin.categories.create') }}"
               class="btn-primary-theme inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold shadow-xs shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Kategori</span>
            </a>
        </div>

        @if(session('success'))
            <div class="flex items-center gap-3 p-4 bg-white border border-slate-200/80 text-slate-800 rounded-2xl shadow-xs">
                <span class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></span>
                <span class="text-xs sm:text-sm font-semibold">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="flex items-center gap-3 p-4 bg-white border border-rose-200 text-rose-700 rounded-2xl shadow-xs">
                <span class="w-2 h-2 rounded-full bg-rose-500 shrink-0"></span>
                <span class="text-xs sm:text-sm font-semibold">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Baris Statistik & Pencarian Instan -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <div class="md:col-span-4 bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-wider text-slate-400">Total Kategori</p>
                    <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $categories->count() }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </div>
            </div>

            <div class="md:col-span-8 bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs flex items-center">
                <div class="relative w-full">
                    <svg class="w-4 h-4 text-slate-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
                    </svg>
                    <input type="text" x-model="search" placeholder="Cari nama kategori secara instan..."
                           class="w-full pl-11 pr-4 py-2.5 rounded-xl bg-slate-50/70 border border-slate-200 text-xs sm:text-sm text-slate-800 focus:bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition">
                </div>
            </div>
        </div>

        <!-- Tabel Kategori -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200/80 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                            <th class="py-3.5 px-6 w-20">No</th>
                            <th class="py-3.5 px-4">Nama Kategori</th>
                            <th class="py-3.5 px-4">Jumlah Produk</th>
                            <th class="py-3.5 px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($categories as $index => $category)
                            <tr x-show="search === '' || '{{ strtolower($category->name) }}'.includes(search.toLowerCase())"
                                class="hover:bg-slate-50 transition-colors">
                                <td class="py-4 px-6 font-mono text-xs font-semibold text-slate-400">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="py-4 px-4">
                                    <div class="inline-flex items-center gap-2.5">
                                        <span class="w-2 h-2 rounded-full bg-indigo-600 shrink-0"></span>
                                        <span class="font-bold text-slate-900">{{ $category->name }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-600">
                                        {{ $category->products_count ?? $category->products()->count() }} produk
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="inline-flex items-center gap-2 text-xs font-bold">
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-700 hover:bg-slate-50 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            <span>Edit</span>
                                        </a>

                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Hapus kategori {{ $category->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn-danger-soft inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 font-bold">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                <span>Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-14 text-center">
                                    <p class="text-sm font-semibold text-slate-600">Belum ada kategori terdaftar</p>
                                    <p class="text-xs text-slate-400 mt-1">Jalankan seeder kategori bawaan atau klik <span class="text-indigo-600 font-semibold">+ Tambah Kategori</span>.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>