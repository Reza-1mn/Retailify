<x-app-layout>
    <x-slot name="header">
        <h1 class="text-base sm:text-lg font-extrabold text-slate-900">Manajemen Supplier</h1>
    </x-slot>

    <div x-data="{
             search: '',
             contactFilter: 'all',
             viewMode: 'list',
             resetFilter() {
                 this.search = '';
                 this.contactFilter = 'all';
             }
         }"
         class="w-full px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        @php
            $totalSuppliers = $suppliers->count();
            $withPhone = $suppliers->filter(fn($s) => !empty($s->phone))->count();
            $withAddress = $suppliers->filter(fn($s) => !empty($s->address))->count();
        @endphp

        <!-- Header Halaman -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 text-xs font-medium text-slate-400">
                    <span>Manajemen Toko</span>
                    <span>/</span>
                    <span class="text-indigo-600 font-semibold">Manage Suppliers</span>
                </div>
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-0.5">Manage Suppliers</h2>
            </div>

            <div class="flex items-center gap-2.5 shrink-0">
                <a href="{{ route('admin.products.index') }}"
                   class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs sm:text-sm font-bold transition">
                    Kelola Produk
                </a>
                <a href="{{ route('admin.suppliers.create') }}"
                   class="btn-primary-theme inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-xs sm:text-sm font-bold shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span>Add Supplier</span>
                </a>
            </div>
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

        <!-- Ringkasan Kondisi Supplier -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5 shadow-xs space-y-4">
            <div class="flex items-center gap-2 text-xs font-bold text-slate-700">
                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 16v-4m0-4h.01"/>
                </svg>
                <span>Ringkasan Mitra Supplier</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <button type="button" @click="contactFilter = 'all'"
                        :class="contactFilter === 'all' ? 'border-slate-300 bg-slate-50/70' : 'border-slate-200/80 bg-white hover:bg-slate-50/50'"
                        class="text-left rounded-2xl border p-4 transition flex flex-col justify-between gap-3">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs font-extrabold text-slate-900">Total Mitra Supplier</span>
                        <span class="px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200/60 text-slate-700 text-[11px] font-bold">{{ $totalSuppliers }} mitra</span>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">Seluruh pemasok barang dan mitra restok yang terdaftar di sistem.</p>
                    <span class="text-xs font-bold text-slate-700 hover:text-indigo-600 transition">Tampilkan semua &rsaquo;</span>
                </button>

                <button type="button" @click="contactFilter = 'has_phone'"
                        :class="contactFilter === 'has_phone' ? 'border-slate-300 bg-slate-50/70' : 'border-slate-200/80 bg-white hover:bg-slate-50/50'"
                        class="text-left rounded-2xl border p-4 transition flex flex-col justify-between gap-3">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs font-extrabold text-slate-900">Kontak Aktif</span>
                        <span class="px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200/60 text-slate-700 text-[11px] font-bold">{{ $withPhone }} kontak</span>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">Supplier yang sudah memiliki nomor telepon atau WhatsApp untuk koordinasi restok.</p>
                    <span class="text-xs font-bold text-slate-700 hover:text-indigo-600 transition">Filter kontak aktif &rsaquo;</span>
                </button>

                <button type="button" @click="contactFilter = 'has_address'"
                        :class="contactFilter === 'has_address' ? 'border-slate-300 bg-slate-50/70' : 'border-slate-200/80 bg-white hover:bg-slate-50/50'"
                        class="text-left rounded-2xl border p-4 transition flex flex-col justify-between gap-3">
                    <div class="flex items-center justify-between gap-2">
                        <span class="text-xs font-extrabold text-slate-900">Alamat Terdata</span>
                        <span class="px-2 py-0.5 rounded-md bg-slate-100 border border-slate-200/60 text-slate-700 text-[11px] font-bold">{{ $withAddress }} lokasi</span>
                    </div>
                    <p class="text-xs text-slate-500 leading-relaxed">Data lokasi kantor atau gudang pemasok yang sudah tercatat lengkap.</p>
                    <span class="text-xs font-bold text-slate-700 hover:text-indigo-600 transition">Lihat alamat terdata &rsaquo;</span>
                </button>
            </div>
        </div>

        <!-- Kontainer Tab Filter, Pencarian, Toggle Grid/List & Konten -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <!-- Tab Filter Cepat -->
            <div class="px-6 pt-4 border-b border-slate-200/70 flex items-center gap-6 text-xs font-bold">
                <button type="button" @click="contactFilter = 'all'"
                        :class="contactFilter === 'all' ? 'text-indigo-600 border-b-2 border-indigo-600 pb-3' : 'text-slate-500 hover:text-slate-800 pb-3'">
                    All <span class="ml-1 text-slate-400">{{ $totalSuppliers }}</span>
                </button>
                <button type="button" @click="contactFilter = 'has_phone'"
                        :class="contactFilter === 'has_phone' ? 'text-indigo-600 border-b-2 border-indigo-600 pb-3' : 'text-slate-500 hover:text-slate-800 pb-3'">
                    Kontak Lengkap <span class="ml-1 text-slate-400">{{ $withPhone }}</span>
                </button>
                <button type="button" @click="contactFilter = 'has_address'"
                        :class="contactFilter === 'has_address' ? 'text-indigo-600 border-b-2 border-indigo-600 pb-3' : 'text-slate-500 hover:text-slate-800 pb-3'">
                    Alamat Lengkap <span class="ml-1 text-slate-400">{{ $withAddress }}</span>
                </button>
            </div>

            <!-- Baris Search + Filter + Toggle Grid/List + Reset -->
            <div class="p-4 sm:px-6 border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="relative flex-1 max-w-md">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
                    </svg>
                    <input type="text" x-model="search" placeholder="Cari nama supplier, kontak, atau alamat..."
                           class="w-full pl-10 pr-4 py-2 rounded-xl bg-slate-50/70 border border-slate-200 text-xs sm:text-sm text-slate-800 focus:bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition">
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    <select x-model="contactFilter"
                            class="rounded-xl border-slate-200 bg-white text-xs font-semibold text-slate-700 py-2 pl-3 pr-8 focus:border-indigo-600 focus:ring-indigo-600">
                        <option value="all">Semua Supplier</option>
                        <option value="has_phone">Punya No. Telepon</option>
                        <option value="has_address">Punya Alamat</option>
                    </select>

                    <!-- Toggle Memanjang (List) vs Kotak-Kotak (Grid) -->
                    <div class="inline-flex items-center rounded-xl border border-slate-200 bg-slate-50 p-1">
                        <button type="button"
                                @click="viewMode = 'list'"
                                :class="viewMode === 'list' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-400 hover:text-slate-700'"
                                title="Tampilan Memanjang (List)"
                                class="p-1.5 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <button type="button"
                                @click="viewMode = 'grid'"
                                :class="viewMode === 'grid' ? 'bg-white text-slate-900 shadow-2xs' : 'text-slate-400 hover:text-slate-700'"
                                title="Tampilan Kotak-Kotak (Grid)"
                                class="p-1.5 rounded-lg transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                                <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                                <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                                <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                            </svg>
                        </button>
                    </div>

                    <button type="button" @click="resetFilter()"
                            class="px-3.5 py-2 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-xs font-bold text-slate-700 transition">
                        Reset
                    </button>
                </div>
            </div>

            <!-- MODE 1: MEMANJANG (LIST / TABEL) -->
            <div x-show="viewMode === 'list'" class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/80 border-b border-slate-200/80 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                            <th class="py-3.5 px-6 w-20">No</th>
                            <th class="py-3.5 px-4">Supplier</th>
                            <th class="py-3.5 px-4">Kontak / Telepon</th>
                            <th class="py-3.5 px-4">Alamat</th>
                            <th class="py-3.5 px-6 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($suppliers as $index => $supplier)
                            @php
                                $hasPhone = !empty($supplier->phone) ? '1' : '0';
                                $hasAddress = !empty($supplier->address) ? '1' : '0';
                                $initials = collect(explode(' ', trim($supplier->name)))
                                    ->filter()
                                    ->take(2)
                                    ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))
                                    ->implode('');
                                $searchBlob = strtolower(($supplier->name ?? '') . ' ' . ($supplier->phone ?? '') . ' ' . ($supplier->address ?? ''));
                            @endphp
                            <tr x-show="(search === '' || '{{ addslashes($searchBlob) }}'.includes(search.toLowerCase())) && (contactFilter === 'all' || (contactFilter === 'has_phone' && {{ $hasPhone }} === 1) || (contactFilter === 'has_address' && {{ $hasAddress }} === 1))"
                                class="hover:bg-slate-50/80 transition-colors">
                                <td class="py-4 px-6 font-mono text-xs font-semibold text-slate-400">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="py-4 px-4">
                                    <div class="inline-flex items-center gap-3">
                                        @if(!empty($supplier->logo))
                                            <img src="{{ asset('storage/' . $supplier->logo) }}" alt="{{ $supplier->name }}"
                                                 class="w-9 h-9 rounded-xl object-cover border border-slate-200/80 shrink-0">
                                        @else
                                            <span class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 text-xs font-extrabold flex items-center justify-center shrink-0">
                                                {{ $initials ?: 'SP' }}
                                            </span>
                                        @endif
                                        <span class="font-bold text-slate-900">{{ $supplier->name }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    @if(!empty($supplier->phone))
                                        <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 font-mono text-xs font-semibold">
                                            {{ $supplier->phone }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="py-4 px-4 text-xs sm:text-sm text-slate-600">
                                    {{ $supplier->address ?: '-' }}
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <div class="inline-flex items-center gap-2 text-xs font-bold">
                                        <a href="{{ route('admin.suppliers.edit', $supplier) }}"
                                           class="text-slate-700 hover:text-slate-900 px-2.5 py-1.5 rounded-lg hover:bg-slate-100 transition">
                                            Edit
                                        </a>
                                        <span class="text-slate-200">|</span>
                                        <form action="{{ route('admin.suppliers.destroy', $supplier) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Hapus supplier {{ $supplier->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-rose-600 hover:text-rose-700 px-2.5 py-1.5 rounded-lg hover:bg-rose-50/70 font-bold transition">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-14 text-center">
                                    <p class="text-sm font-semibold text-slate-600">Belum ada data supplier</p>
                                    <p class="text-xs text-slate-400 mt-1">Klik <span class="text-indigo-600 font-semibold">Add Supplier</span> untuk menambahkan mitra pemasok baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- MODE 2: KOTAK-KOTAK (GRID) -->
            <div x-show="viewMode === 'grid'" class="p-5 sm:p-6">
                @if($suppliers->isEmpty())
                    <div class="py-14 text-center">
                        <p class="text-sm font-semibold text-slate-600">Belum ada data supplier</p>
                        <p class="text-xs text-slate-400 mt-1">Klik <span class="text-indigo-600 font-semibold">Add Supplier</span> untuk menambahkan mitra pemasok baru.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                        @foreach($suppliers as $index => $supplier)
                            @php
                                $hasPhone = !empty($supplier->phone) ? '1' : '0';
                                $hasAddress = !empty($supplier->address) ? '1' : '0';
                                $initials = collect(explode(' ', trim($supplier->name)))
                                    ->filter()
                                    ->take(2)
                                    ->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))
                                    ->implode('');
                                $searchBlob = strtolower(($supplier->name ?? '') . ' ' . ($supplier->phone ?? '') . ' ' . ($supplier->address ?? ''));
                            @endphp
                            <div x-show="(search === '' || '{{ addslashes($searchBlob) }}'.includes(search.toLowerCase())) && (contactFilter === 'all' || (contactFilter === 'has_phone' && {{ $hasPhone }} === 1) || (contactFilter === 'has_address' && {{ $hasAddress }} === 1))"
                                 class="rounded-2xl border border-slate-200/80 bg-white hover:border-slate-300 hover:bg-slate-50/40 p-4 flex flex-col justify-between gap-4 transition">

                                <div class="space-y-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="flex items-center gap-3 min-w-0">
                                            @if(!empty($supplier->logo))
                                                <img src="{{ asset('storage/' . $supplier->logo) }}" alt="{{ $supplier->name }}"
                                                     class="w-10 h-10 rounded-xl object-cover border border-slate-200/80 shrink-0">
                                            @else
                                                <span class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 text-xs font-extrabold flex items-center justify-center shrink-0">
                                                    {{ $initials ?: 'SP' }}
                                                </span>
                                            @endif
                                            <div class="min-w-0">
                                                <p class="text-sm font-bold text-slate-900 truncate">{{ $supplier->name }}</p>
                                                <p class="text-xs font-mono text-slate-500 mt-0.5">{{ $supplier->phone ?: 'Tanpa kontak' }}</p>
                                            </div>
                                        </div>
                                        <span class="font-mono text-[11px] font-semibold text-slate-400 shrink-0">
                                            #{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </div>

                                    <p class="text-xs text-slate-500 line-clamp-2">
                                        {{ $supplier->address ?: 'Alamat belum dicatat.' }}
                                    </p>
                                </div>

                                <div class="pt-3 border-t border-slate-100 flex items-center justify-end gap-1.5 text-xs font-bold">
                                    <a href="{{ route('admin.suppliers.edit', $supplier) }}"
                                       class="text-slate-700 hover:text-slate-900 px-2 py-1 rounded-lg hover:bg-slate-100 transition">
                                        Edit
                                    </a>
                                    <span class="text-slate-200">|</span>
                                    <form action="{{ route('admin.suppliers.destroy', $supplier) }}" method="POST" class="inline"
                                          onsubmit="return confirm('Hapus supplier {{ $supplier->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="text-rose-600 hover:text-rose-700 px-2 py-1 rounded-lg hover:bg-rose-50/70 font-bold transition">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

    </div>
</x-app-layout>