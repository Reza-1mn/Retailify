<section class="space-y-4" x-data="{ confirmDelete: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }">
    <header>
        <h3 class="text-base font-extrabold text-rose-600">Zona Bahaya — Hapus Akun</h3>
        <p class="mt-1 text-xs text-slate-500 leading-relaxed">
            Setelah akun dihapus, seluruh akses login untuk akun ini akan dinonaktifkan secara permanen.
        </p>
    </header>

    <button type="button" @click="confirmDelete = true"
            class="px-4 py-2.5 rounded-xl bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white border border-rose-200 font-bold text-xs transition">
        Hapus Akun Ini
    </button>

    <!-- Modal Konfirmasi -->
    <div x-show="confirmDelete" style="display: none;"
         class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 p-4">
        <div @click.away="confirmDelete = false"
             class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl border border-slate-200 space-y-4">
            <h4 class="text-base font-extrabold text-slate-900">Konfirmasi Penghapusan Akun</h4>
            <p class="text-xs text-slate-500 leading-relaxed">
                Masukkan kata sandi akun kamu untuk mengonfirmasi bahwa kamu ingin menghapus akun ini secara permanen.
            </p>

            <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
                @csrf
                @method('delete')

                <div>
                    <label for="delete_password" class="sr-only">Password</label>
                    <input id="delete_password" name="password" type="password" placeholder="Masukkan kata sandi kamu"
                           class="w-full px-4 py-2.5 rounded-xl bg-slate-50 border border-slate-300 text-sm text-slate-900 focus:bg-white focus:border-rose-600 focus:ring-2 focus:ring-rose-600/20 outline-none" />
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1.5 text-xs text-rose-600" />
                </div>

                <div class="flex justify-end space-x-2.5 pt-2">
                    <button type="button" @click="confirmDelete = false"
                            class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-bold text-slate-600 hover:bg-slate-50">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold shadow-sm">
                        Ya, Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>