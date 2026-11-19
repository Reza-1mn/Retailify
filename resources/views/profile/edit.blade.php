<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <h1 class="text-base sm:text-lg font-extrabold text-slate-900">Pengaturan Akun</h1>
            <span class="hidden sm:inline-block text-slate-300">|</span>
            <span class="hidden sm:inline-block text-xs font-medium text-slate-500">Profil &amp; Keamanan</span>
        </div>
    </x-slot>

    <div class="p-5 sm:p-6 max-w-6xl mx-auto space-y-6">
        
        <!-- Kartu Identitas Singkat -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 sm:p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-indigo-600 text-white font-extrabold text-xl flex items-center justify-center shadow-sm shadow-indigo-600/20 shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center space-x-2.5">
                        <h2 class="text-lg font-extrabold text-slate-900">{{ Auth::user()->name }}</h2>
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase bg-indigo-50 text-indigo-600 border border-indigo-100">
                            {{ Auth::user()->role }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 mt-0.5">{{ Auth::user()->email }}</p>
                </div>
            </div>
            <span class="text-xs text-slate-400">
                Bergabung sejak {{ Auth::user()->created_at->format('d M Y') }}
            </span>
        </div>

        <!-- Grid 2 Kolom: Informasi Profil & Keamanan Password -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
            
            <!-- Kolom Kiri: Update Informasi Profil & Hapus Akun -->
            <div class="space-y-6">
                <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="bg-white rounded-2xl border border-rose-200/70 shadow-sm p-6">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

            <!-- Kolom Kanan: Ubah Password -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                @include('profile.partials.update-password-form')
            </div>

        </div>
    </div>
</x-app-layout>