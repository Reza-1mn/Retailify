<style>
    @media print {
        @page {
            size: auto;
            margin: 8mm;
        }
        html, body, #pos-wrapper, main {
            height: auto !important;
            min-height: 0 !important;
            overflow: visible !important;
            position: static !important;
        }
        body > *:not(#pos-print-root) {
            display: none !important;
        }
        #pos-print-root {
            display: block !important;
            position: static !important;
            width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
            background: #ffffff !important;
        }
        #pos-receipt-card {
            box-shadow: none !important;
            border: 1px solid #e2e8f0 !important;
            max-width: 100% !important;
            width: 100% !important;
            break-inside: avoid;
            page-break-inside: avoid;
        }
        .no-print {
            display: none !important;
        }
    }
</style>

<div id="pos-print-root"
     x-show="showReceiptModal"
     x-transition.opacity
     style="display: none;"
     class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 backdrop-blur-xs p-4">

    <div id="pos-receipt-card"
         @click.outside="closeReceiptModal()"
         class="w-full max-w-md bg-white rounded-2xl border border-slate-200/80 shadow-xl overflow-hidden">

        <!-- Header Struk -->
        <div class="px-6 pt-6 pb-4 border-b border-dashed border-slate-200 text-center">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-bold mb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Pembayaran Berhasil
            </span>
            <h3 class="text-lg font-extrabold text-slate-900 tracking-tight">Retailify</h3>
            <p class="text-[11px] text-slate-400 mt-0.5">Struk Pembayaran Resmi</p>

            <div class="mt-4 pt-3 border-t border-slate-100 text-left space-y-1 text-xs">
                <div class="flex justify-between">
                    <span class="text-slate-400">No. Invoice</span>
                    <span class="font-mono font-bold text-slate-800" x-text="receipt.invoice_number"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Waktu</span>
                    <span class="text-slate-700 font-medium" x-text="receipt.printed_at"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Kasir</span>
                    <span class="text-slate-700 font-medium" x-text="receipt.cashier"></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Metode Bayar</span>
                    <span class="uppercase font-bold text-slate-800" x-text="receipt.payment_method"></span>
                </div>
            </div>
        </div>

        <!-- Daftar Item -->
        <div class="px-6 py-4 max-h-60 overflow-y-auto space-y-2.5 border-b border-dashed border-slate-200">
            <template x-for="item in receipt.items" :key="item.product_id">
                <div class="flex items-start justify-between text-xs gap-3">
                    <div class="min-w-0">
                        <p class="font-bold text-slate-800 truncate" x-text="item.name"></p>
                        <p class="text-[11px] text-slate-400" x-text="item.qty + ' x ' + formatRupiah(item.price)"></p>
                    </div>
                    <span class="font-extrabold text-slate-900 shrink-0" x-text="formatRupiah(item.price * item.qty)"></span>
                </div>
            </template>
        </div>

        <!-- Ringkasan Pembayaran -->
        <div class="px-6 py-4 bg-slate-50/70 space-y-2 text-xs">
            <div class="flex justify-between text-sm font-extrabold text-slate-900">
                <span>Total Bayar</span>
                <span x-text="formatRupiah(receipt.total_amount)"></span>
            </div>
            <div class="flex justify-between text-slate-600 pt-1">
                <span>Uang Diterima</span>
                <span class="font-bold text-slate-800" x-text="formatRupiah(receipt.payment_amount)"></span>
            </div>
            <div class="flex justify-between text-sm font-extrabold pt-2 border-t border-slate-200/80">
                <span class="text-slate-700">Kembalian</span>
                <span class="text-emerald-600 dark:text-emerald-400" x-text="formatRupiah(receipt.change_amount)"></span>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="no-print px-6 py-4 bg-white border-t border-slate-100 flex items-center gap-3">
            <button type="button" @click="printReceipt()"
                    class="flex-1 py-2.5 px-4 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs inline-flex items-center justify-center gap-2 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span>Cetak / Simpan PDF</span>
            </button>

            <button type="button" @click="closeReceiptModal()"
                    class="btn-primary-theme flex-1 py-2.5 px-4 rounded-xl font-extrabold text-xs">
                Transaksi Baru
            </button>
        </div>

    </div>
</div>