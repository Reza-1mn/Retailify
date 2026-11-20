<section x-data="{ showPass: false }">
    <header class="pb-4 border-b border-slate-100 flex items-center justify-between">
        <div>
            <h3 class="text-base font-extrabold text-slate-900">Perbarui Kata Sandi</h3>
            <p class="mt-1 text-xs text-slate-500">
                Gunakan kombinasi kata sandi yang kuat untuk menjaga keamanan akses kasir.
            </p>
        </div>
        <button type="button" @click="showPass = !showPass"
                class="text-[11px] font-bold text-indigo-600 hover:underline shrink-0">
            <span x-text="showPass ? 'Sembunyikan' : 'Tampilkan'"></span>
        </button>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-5 space-y-4">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi Saat Ini</label>
            <input id="update_password_current_password" name="current_password" :type="showPass ? 'text' : 'password'" autocomplete="current-password"
                   placeholder="••••••••"
                   class="w-full px-4 py-2.5 rounded-xl bg-slate-50/80 border border-slate-300 text-sm text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 outline-none transition" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1.5 text-xs text-rose-600" />
        </div>

        <div>
            <label for="update_password_password" class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi Baru</label>
            <input id="update_password_password" name="password" :type="showPass ? 'text' : 'password'" autocomplete="new-password"
                   placeholder="Minimal 8 karakter"
                   class="w-full px-4 py-2.5 rounded-xl bg-slate-50/80 border border-slate-300 text-sm text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 outline-none transition" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1.5 text-xs text-rose-600" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-xs font-bold text-slate-700 mb-1.5">Konfirmasi Kata Sandi Baru</label>
            <input id="update_password_password_confirmation" name="password_confirmation" :type="showPass ? 'text' : 'password'" autocomplete="new-password"
                   placeholder="Ulangi kata sandi baru"
                   class="w-full px-4 py-2.5 rounded-xl bg-slate-50/80 border border-slate-300 text-sm text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 outline-none transition" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1.5 text-xs text-rose-600" />
        </div>

        <div class="pt-2 flex items-center gap-3">
            <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm shadow-indigo-600/20 transition">
                Perbarui Kata Sandi
            </button>

            @if (session('status') === 'password-updated')
                <span x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                      class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">
                    Kata sandi diperbarui!
                </span>
            @endif
        </div>
    </form>
</section>