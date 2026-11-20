<section x-data="{ photoPreview: null }">
    <header class="pb-4 border-b border-slate-100">
        <h3 class="text-base font-extrabold text-slate-900">Informasi Profil</h3>
        <p class="mt-1 text-xs text-slate-500">
            Perbarui foto profil, nama tampilan, dan alamat email akun operasional kamu.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <!-- Form Terpisah untuk Hapus Foto Profil di Database -->
    @if($user->avatar)
        <form id="delete-avatar-form" method="post" action="{{ route('profile.avatar.destroy') }}">
            @csrf
            @method('delete')
        </form>
    @endif

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-5 space-y-4">
        @csrf
        @method('patch')

        <!-- Upload, Pratinjau & Hapus Foto Profil -->
        <div class="flex items-center space-x-4 pb-2">
            <div class="shrink-0">
                <template x-if="photoPreview">
                    <img :src="photoPreview" class="w-16 h-16 rounded-2xl object-cover ring-2 ring-indigo-600">
                </template>
                <template x-if="!photoPreview">
                    @if($user->avatar)
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                             class="w-16 h-16 rounded-2xl object-cover ring-2 ring-slate-200">
                    @else
                        <div class="w-16 h-16 rounded-2xl bg-indigo-50 text-indigo-600 font-extrabold text-xl flex items-center justify-center border border-indigo-100">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </template>
            </div>

            <div class="space-y-1.5">
                <div class="flex flex-wrap items-center gap-2">
                    <label for="avatar" class="inline-flex items-center px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-indigo-50 text-slate-700 hover:text-indigo-600 font-bold text-xs cursor-pointer transition">
                        Pilih Foto Profil
                    </label>

                    <!-- Batal jika baru memilih file (belum disimpan) -->
                    <button type="button" x-show="photoPreview" style="display: none;"
                            @click="photoPreview = null; document.getElementById('avatar').value = ''"
                            class="inline-flex items-center px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs transition">
                        Batal
                    </button>

                    <!-- Hapus foto yang sudah tersimpan di server -->
                    @if($user->avatar)
                        <button type="submit" form="delete-avatar-form" x-show="!photoPreview"
                                class="inline-flex items-center px-3 py-2 rounded-xl bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white border border-rose-200/70 font-bold text-xs transition">
                            Hapus Foto
                        </button>
                    @endif
                </div>

                <input id="avatar" name="avatar" type="file" accept="image/png,image/jpeg,image/webp" class="hidden"
                       @change="
                           const file = $event.target.files[0];
                           if (file) {
                               const reader = new FileReader();
                               reader.onload = (e) => photoPreview = e.target.result;
                               reader.readAsDataURL(file);
                           }
                       ">
                <p class="text-[11px] text-slate-400">JPG, PNG, atau WEBP. Maksimal 2 MB.</p>
                <x-input-error class="text-xs text-rose-600" :messages="$errors->get('avatar')" />
            </div>
        </div>

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

            @if (session('status') === 'avatar-deleted')
                <span x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2500)"
                      class="text-xs font-bold text-rose-600 bg-rose-50 px-3 py-1 rounded-full">
                    Foto profil dihapus!
                </span>
            @endif
        </div>
    </form>
</section>