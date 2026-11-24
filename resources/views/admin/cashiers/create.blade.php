<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-lg font-extrabold text-slate-900 tracking-tight">Tambah Akun Kasir Baru</h1>
            <p class="text-xs text-slate-500">Buat kredensial login untuk petugas kasir</p>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 max-w-2xl mx-auto">
        <form action="{{ route('admin.cashiers.store') }}" method="POST"
              class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-600 mb-2">Nama Lengkap Kasir</label>
                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                @error('name') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-600 mb-2">Email Login</label>
                <input type="email" name="email" value="{{ old('email') }}" required placeholder="kasir@retailify.com"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                @error('email') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-600 mb-2">Password</label>
                    <input type="password" name="password" required placeholder="Minimal 8 karakter"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    @error('password') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-extrabold uppercase tracking-wider text-slate-600 mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required placeholder="Ulangi password"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-200 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.cashiers.index') }}"
                   class="px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-sm font-bold transition">
                    Batal
                </a>
                <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold shadow-xs transition">
                    Simpan Akun Kasir
                </button>
            </div>
        </form>
    </div>
</x-app-layout>