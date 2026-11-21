<x-app-layout>
    <div class="min-h-screen bg-slate-50/70 pb-12">
        <div class="bg-white border-b border-slate-200/80">
            <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-6 flex items-center gap-4">
                <a href="{{ route('admin.suppliers.index') }}" class="w-10 h-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 flex items-center justify-center transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div>
                    <p class="text-xs font-semibold text-amber-600">Edit Data</p>
                    <h1 class="text-xl font-extrabold text-slate-900">Perbarui Data Supplier</h1>
                </div>
            </div>
        </div>

        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
                <form action="{{ route('admin.suppliers.update', $supplier) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Supplier / Perusahaan <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $supplier->name) }}"
                               class="w-full px-4 py-2.5 bg-slate-50 border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition" required>
                        @error('name')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                    </div>

                    <!-- Upload & Live Preview Foto / Logo Supplier -->
                    <div x-data="{ photoPreview: '{{ $supplier->image ? asset('storage/' . $supplier->image) : '' }}' }" class="space-y-2">
                        <label class="block text-sm font-bold text-slate-700">Foto / Logo Supplier (Opsional)</label>
                        
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 rounded-2xl border border-slate-200 bg-slate-50 overflow-hidden flex items-center justify-center shrink-0">
                                <template x-if="photoPreview">
                                    <img :src="photoPreview" class="w-full h-full object-cover" alt="Preview">
                                </template>
                                <template x-if="!photoPreview">
                                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </template>
                            </div>

                            <div class="flex-1">
                                <input type="file" name="image" accept="image/png,image/jpeg,image/webp"
                                       @change="const file = $event.target.files[0]; if (file) { photoPreview = URL.createObjectURL(file); }"
                                       class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 cursor-pointer">
                                <p class="text-[11px] text-slate-400 mt-1">Format JPG, PNG, atau WEBP. Maksimal 2 MB. Kosongkan jika tidak ingin mengganti foto.</p>
                            </div>
                        </div>
                        @error('image')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">No. Telepon / WhatsApp</label>
                        <input type="text" name="phone" value="{{ old('phone', $supplier->phone) }}"
                               class="w-full px-4 py-2.5 bg-slate-50 border-slate-200 rounded-xl text-sm font-mono focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition">
                        @error('phone')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Lengkap</label>
                        <textarea name="address" rows="3"
                                  class="w-full px-4 py-2.5 bg-slate-50 border-slate-200 rounded-xl text-sm focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 transition">{{ old('address', $supplier->address) }}</textarea>
                        @error('address')<p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>@enderror
                    </div>

                    <div class="pt-5 border-t border-slate-100 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.suppliers.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-semibold transition">Batal</a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold shadow-sm shadow-indigo-200 transition">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>