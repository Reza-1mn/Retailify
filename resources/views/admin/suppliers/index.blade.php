<x-app-layout>
    <div x-data="{ search: '' }" class="min-h-screen bg-slate-50/70 pb-12">
        <!-- Top Banner Header -->
        <div class="bg-white border-b border-slate-200/80">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-sky-500 to-blue-600 text-white flex items-center justify-center shadow-md shadow-sky-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 text-xs font-medium text-slate-400 mb-0.5">
                                <span>Master Data</span>
                                <span>/</span>
                                <span class="text-sky-600">Supplier</span>
                            </div>
                            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Manajemen Supplier</h1>
                        </div>
                    </div>

                    <a href="{{ route('admin.suppliers.create') }}"
                       class="inline-flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm shadow-indigo-200 transition-all duration-150">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Tambah Supplier
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 space-y-6">
            @if(session('success'))
                <div class="flex items-center gap-3 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl shadow-sm">
                    <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </span>
                    <span class="text-sm font-semibold">{{ session('success') }}</span>
                </div>
            @endif

            <!-- Ringkasan & Pencarian -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Mitra Supplier</p>
                        <p class="text-3xl font-extrabold text-slate-900 mt-1">{{ $suppliers->count() }}</p>
                    </div>
                    <div class="w-11 h-11 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    </div>
                </div>

                <div class="md:col-span-2 bg-white rounded-2xl border border-slate-200/80 p-5 shadow-sm flex items-center">
                    <div class="relative w-full">
                        <svg class="w-5 h-5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z"/></svg>
                        <input type="text" x-model="search" placeholder="Cari nama supplier atau alamat..."
                               class="w-full pl-11 pr-4 py-2.5 bg-slate-50 border-slate-200 rounded-xl text-sm text-slate-700 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition">
                    </div>
                </div>
            </div>

            <!-- Tabel -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200/80 text-xs font-bold uppercase tracking-wider text-slate-500">
                                <th class="py-4 px-6 w-16">No</th>
                                <th class="py-4 px-6">Nama Supplier</th>
                                <th class="py-4 px-6">Kontak / Telepon</th>
                                <th class="py-4 px-6">Alamat</th>
                                <th class="py-4 px-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($suppliers as $index => $supplier)
                                <tr x-show="search === '' || '{{ strtolower($supplier->name . ' ' . $supplier->address) }}'.includes(search.toLowerCase())"
                                    class="hover:bg-slate-50/90 transition-colors">
                                    <td class="py-4 px-6 font-medium text-slate-400">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl bg-sky-50 text-sky-700 font-bold text-xs flex items-center justify-center uppercase shrink-0">
                                                {{ substr($supplier->name, 0, 2) }}
                                            </div>
                                            <span class="font-semibold text-slate-800">{{ $supplier->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        @if($supplier->phone)
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-700 font-mono">
                                                {{ $supplier->phone }}
                                            </span>
                                        @else
                                            <span class="text-slate-400 text-xs italic">Tidak ada nomor</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-slate-600 max-w-xs truncate">{{ $supplier->address ?? '-' }}</td>
                                    <td class="py-4 px-6 text-right">
                                        <div class="inline-flex items-center gap-2">
                                            <a href="{{ route('admin.suppliers.edit', $supplier) }}"
                                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-amber-50 text-slate-700 hover:text-amber-700 rounded-lg text-xs font-semibold transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.suppliers.destroy', $supplier) }}" method="POST" class="inline" onsubmit="return confirm('Hapus supplier {{ $supplier->name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 hover:bg-rose-50 text-slate-700 hover:text-rose-700 rounded-lg text-xs font-semibold transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-16 text-center">
                                        <p class="text-slate-600 font-semibold text-sm">Belum ada data supplier</p>
                                        <p class="text-slate-400 text-xs mt-1">Klik <span class="text-indigo-600 font-semibold">+ Tambah Supplier</span> untuk mencatat pemasok barang.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>