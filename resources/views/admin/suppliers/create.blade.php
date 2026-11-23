<x-app-layout>
    <x-slot name="header">
        <h1 class="text-base sm:text-lg font-extrabold text-slate-900">Tambah Supplier</h1>
    </x-slot>

    <div x-data="{
             supplierName: '{{ old('name', '') }}',
             phone: '{{ old('phone', '') }}',
             address: '{{ old('address', '') }}',
             previewUrl: null,
             handleFile(event) {
                 const file = event.target.files[0];
                 if (!file) {
                     this.previewUrl = null;
                     return;
                 }
                 this.previewUrl = URL.createObjectURL(file);
             },
             get initials() {
                 const parts = (this.supplierName || '').trim().split(/\s+/).filter(Boolean);
                 if (parts.length === 0) return 'SP';
                 return parts.slice(0, 2).map(p => p[0].toUpperCase()).join('');
             }
         }"
         class="w-full px-4 sm:px-6 lg:px-8 py-6 space-y-6">

        <!-- Header Halaman -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3.5">
                <a href="{{ route('admin.suppliers.index') }}"
                   class="w-10 h-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 flex items-center justify-center transition shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-2 text-xs font-medium text-slate-400">
                        <span>Manajemen Toko</span>
                        <span>/</span>
                        <a href="{{ route('admin.suppliers.index') }}" class="hover:text-indigo-600 transition">Manage Suppliers</a>
                        <span>/</span>
                        <span class="text-indigo-600 font-semibold">Tambah</span>
                    </div>
                    <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight mt-0.5">Tambah Supplier Baru</h2>
                </div>
            </div>
        </div>

        <!-- Grid 12 Kolom -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

            <!-- Kolom Kiri: Form -->
            <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 sm:p-8">
                <form action="{{ route('admin.suppliers.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    <div>
                        <label for="supplier_name" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            Nama Supplier / Perusahaan <span class="text-rose-500">*</span>
                        </label>
                        <input id="supplier_name"
                               type="text"
                               name="name"
                               x-model="supplierName"
                               placeholder="Contoh: PT Sumber Makmur Jaya"
                               class="w-full px-4 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition"
                               required
                               autofocus>
                        @error('name')
                            <p class="text-rose-600 text-xs font-semibold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            Foto / Logo Supplier (Opsional)
                        </label>
                        <div class="flex items-center gap-4 p-3.5 rounded-xl border border-slate-200 bg-slate-50/50">
                            <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 flex items-center justify-center overflow-hidden shrink-0">
                                <template x-if="previewUrl">
                                    <img :src="previewUrl" alt="Preview Logo" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!previewUrl">
                                    <span class="text-xs font-extrabold text-slate-500" x-text="initials"></span>
                                </template>
                            </div>
                            <div class="min-w-0 flex-1">
                                <input type="file" name="logo" accept="image/jpeg,image/png,image/webp" @change="handleFile($event)"
                                       class="block w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border file:border-slate-200 file:text-xs file:font-bold file:bg-white file:text-slate-700 hover:file:bg-slate-50">
                                <p class="text-[11px] text-slate-400 mt-1">Format JPG, PNG, atau WEBP. Maksimal 2 MB.</p>
                            </div>
                        </div>
                        @error('logo')
                            <p class="text-rose-600 text-xs font-semibold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="supplier_phone" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            No. Telepon / WhatsApp
                        </label>
                        <input id="supplier_phone"
                               type="text"
                               name="phone"
                               x-model="phone"
                               placeholder="Contoh: 081234567890"
                               class="w-full px-4 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition">
                        @error('phone')
                            <p class="text-rose-600 text-xs font-semibold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="supplier_address" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2">
                            Alamat Lengkap
                        </label>
                        <textarea id="supplier_address"
                                  name="address"
                                  rows="3"
                                  x-model="address"
                                  placeholder="Masukkan alamat lengkap kantor atau gudang pemasok..."
                                  class="w-full px-4 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-sm text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition"></textarea>
                        @error('address')
                            <p class="text-rose-600 text-xs font-semibold mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="pt-5 border-t border-slate-100 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.suppliers.index') }}"
                           class="px-4 py-2.5 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 text-xs sm:text-sm font-bold transition">
                            Batal
                        </a>
                        <button type="submit"
                                class="btn-primary-theme px-5 py-2.5 rounded-xl text-xs sm:text-sm font-bold shadow-xs">
                            Simpan Supplier
                        </button>
                    </div>
                </form>
            </div>

            <!-- Kolom Kanan: Preview Kartu Supplier -->
            <div class="lg:col-span-5 space-y-5">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs p-6 space-y-4">
                    <h3 class="text-sm font-extrabold text-slate-900">Preview Data Supplier</h3>

                    <div class="rounded-2xl border border-slate-200/80 bg-slate-50/40 p-4 space-y-3">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 border border-slate-200/70 text-slate-700 text-xs font-extrabold flex items-center justify-center overflow-hidden shrink-0">
                                <template x-if="previewUrl">
                                    <img :src="previewUrl" alt="Preview" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!previewUrl">
                                    <span x-text="initials"></span>
                                </template>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-slate-900 truncate" x-text="supplierName || 'Nama Supplier / Perusahaan'"></p>
                                <p class="text-xs font-mono text-slate-500 mt-0.5" x-text="phone || '08xxxxxxxxxx'"></p>
                            </div>
                        </div>

                        <p class="text-xs text-slate-500 leading-relaxed" x-text="address || 'Alamat kantor atau gudang pemasok akan tampil di sini.'"></p>
                    </div>

                    <p class="text-[11px] text-slate-400 leading-relaxed">
                        Data supplier yang tersimpan akan dapat dipilih saat mencatat restok barang pada menu <strong class="text-slate-600">Restock &amp; Log Stok</strong>.
                    </p>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>