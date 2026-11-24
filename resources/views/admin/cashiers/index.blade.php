<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="text-lg font-extrabold text-slate-900 tracking-tight">Manajemen Akun Kasir</h1>
            <p class="text-xs text-slate-500">Kelola akun akses kasir yang beroperasi di sistem POS</p>
        </div>
    </x-slot>

    <div class="p-4 sm:p-6 lg:p-8 max-w-6xl mx-auto space-y-6">
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-semibold flex items-center justify-between">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs">
            <div>
                <h2 class="text-base font-extrabold text-slate-900">Daftar Kasir Terdaftar ({{ $cashiers->count() }})</h2>
                <p class="text-xs text-slate-500 mt-0.5">Registrasi publik dinonaktifkan. Hanya Admin yang dapat menambah atau mereset password kasir.</p>
            </div>
            <a href="{{ route('admin.cashiers.create') }}"
               class="inline-flex items-center justify-center space-x-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold shadow-xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Tambah Akun Kasir</span>
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50/70 text-[11px] font-extrabold uppercase tracking-wider text-slate-400">
                            <th class="py-3.5 px-5">Nama Kasir</th>
                            <th class="py-3.5 px-5">Email Login</th>
                            <th class="py-3.5 px-5">Role</th>
                            <th class="py-3.5 px-5">Dibuat Pada</th>
                            <th class="py-3.5 px-5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($cashiers as $cashier)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3.5 px-5">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-9 h-9 rounded-full bg-indigo-50 text-indigo-600 font-extrabold text-xs flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($cashier->name, 0, 1)) }}
                                        </div>
                                        <span class="font-bold text-slate-900">{{ $cashier->name }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-5 text-slate-600 font-medium">{{ $cashier->email }}</td>
                                <td class="py-3.5 px-5">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-sky-50 text-sky-700 border border-sky-200/60 uppercase">
                                        {{ $cashier->role }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-5 text-slate-500 text-xs">
                                    {{ $cashier->created_at?->format('d M Y, H:i') }}
                                </td>
                                <td class="py-3.5 px-5 text-right">
                                    <div class="inline-flex items-center space-x-2">
                                        <a href="{{ route('admin.cashiers.edit', $cashier) }}"
                                           class="px-3 py-1.5 rounded-lg text-xs font-bold bg-amber-50 text-amber-700 hover:bg-amber-100 transition">
                                            Edit / Reset Password
                                        </a>
                                        <form action="{{ route('admin.cashiers.destroy', $cashier) }}" method="POST"
                                              onsubmit="return confirm('Hapus akun kasir {{ $cashier->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="px-3 py-1.5 rounded-lg text-xs font-bold bg-rose-50 text-rose-600 hover:bg-rose-100 transition">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400 text-sm">
                                    Belum ada akun kasir. Klik <strong>Tambah Akun Kasir</strong> untuk membuat akun baru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>