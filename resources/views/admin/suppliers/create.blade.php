<x-app-layout>
    <div class="min-h-screen bg-slate-50/70 pb-12">
        <div class="bg-white border-b border-slate-200/80">
            <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex items-center gap-4">
                <a href="{{ route('admin.suppliers.index') }}" class="w-10 h-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 flex items-center justify-center transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <p class="text-xs font-semibold text-sky-600">Mitra Pemasok</p>
                    <h1 class="text-xl font-extrabold text-slate-900">Tambah Supplier Baru</h1>
                </div>
            </div>
        </div>

        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
                <form action="{{ route('admin.suppliers.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Supplier / Perusahaan <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: PT Indofood Sukses Makmur"
                               class="w-full px-4 py-2.5 bg-slate-50 border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition" required autofocus>
                        @error('name')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">No. Telepon / WhatsApp</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 081234567890"
                               class="w-full px-4 py-2.5 bg-slate-50 border-slate-200 rounded-xl text-sm font-mono focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition">
                        @error('phone')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Lengkap</label>
                        <textarea name="address" rows="3" placeholder="Masukkan alamat lengkap kantor atau gudang pemasok..."
                                  class="w-full px-4 py-2.5 bg-slate-50 border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition">{{ old('address') }}</textarea>
                        @error('address')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                    </div>

                    <div class="pt-5 border-t border-slate-100 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.suppliers.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-semibold transition">Batal</a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm shadow-indigo-200 transition">Simpan Supplier</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>