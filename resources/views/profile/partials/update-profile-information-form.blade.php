<section>
    <header class="pb-4 border-b border-slate-100">
        <h3 class="text-base font-extrabold text-slate-900">Informasi Profil</h3>
        <p class="mt-1 text-xs text-slate-500">
            Perbarui nama tampilan dan alamat email akun operasional kamu.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-5 space-y-4">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                   class="w-full px-4 py-2.5 rounded-xl bg-slate-50/80 border border-slate-300 text-sm text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 outline-none transition" />
            <x-input-error class="mt-1.5 text-xs text-rose-600" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5">Alamat Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                   class="w-full px-4 py-2.5 rounded-xl bg-slate-50/80 border border-slate-300 text-sm text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20 outline-none transition" />
            <x-input-error class="mt-1.5 text-xs text-rose-600" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 p-3 rounded-xl bg-amber-50 border border-amber-200 text-xs text-amber-800">
                    Alamat email kamu belum terverifikasi.
                    <button form="send-verification" class="underline font-bold hover:text-amber-900 ml-1">
                        Kirim ulang email verifikasi.
                    </button>
                </div>
            @endif
        </div>

        <div class="pt-2 flex items-center gap-3">
            <button type="submit"
                    class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-sm shadow-indigo-600/20 transition">
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <span x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                      class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full">
                    Tersimpan!
                </span>
            @endif
        </div>
    </form>
</section>