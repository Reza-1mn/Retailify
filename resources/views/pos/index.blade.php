<x-app-layout>
    <x-slot name="header">
        <h1 class="text-base sm:text-lg font-extrabold text-slate-900">Point of Sale (Kasir)</h1>
    </x-slot>

    <div id="pos-wrapper"
         class="flex flex-col xl:flex-row min-h-[calc(100vh-4rem)] xl:h-[calc(100vh-4rem)] xl:overflow-hidden bg-slate-50/70"
         x-data="posApp()">

        <!-- KOLOM KIRI / TENGAH -->
        <div class="flex-1 p-5 sm:p-6 space-y-6 overflow-y-auto">
            <!-- Transaksi Terakhir -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-bold text-slate-800">Transaksi Terakhir</h2>
                    <span class="text-xs font-medium text-slate-400">Real-Time POS</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <template x-for="trx in recentTransactions" :key="trx.invoice_number">
                        <div class="p-3.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex flex-col justify-between transition hover:border-slate-300">
                            <span class="font-mono text-xs font-semibold text-slate-500 truncate" x-text="trx.invoice_number"></span>
                            <p class="text-base font-extrabold text-slate-900 mt-1" x-text="formatRupiah(trx.total_amount)"></p>
                            <div class="flex items-center justify-between mt-2.5 pt-2 border-t border-slate-100">
                                <span class="text-[11px] text-slate-400" x-text="trx.time_label"></span>
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-slate-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Selesai
                                </span>
                            </div>
                        </div>
                    </template>

                    <template x-if="recentTransactions.length === 0">
                        <div class="col-span-full p-4 rounded-2xl bg-white border border-slate-200/80 text-xs text-slate-500">
                            Belum ada transaksi tercatat hari ini. Silakan pilih produk di bawah untuk memulai transaksi.
                        </div>
                    </template>
                </div>
            </div>

            <!-- Pencarian & Filter Kategori -->
            <div class="space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <h2 class="text-sm font-bold text-slate-800">Katalog Produk</h2>
                    <div class="relative w-full sm:w-80">
                        <input type="text" x-model="search" placeholder="Cari nama produk atau kode SKU..."
                               class="w-full pl-9 pr-4 py-2 rounded-xl bg-white border border-slate-200 text-xs sm:text-sm focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
                        </svg>
                    </div>
                </div>

                <!-- Category Pills (Langsung Terlihat Tanpa Scroll Samping) -->
                <div class="flex flex-wrap items-center gap-2.5" x-data="{ showAllCats: true }">
                    <button type="button" @click="selectedCategory = 'all'"
                            :class="selectedCategory === 'all' ? 'border-indigo-600 bg-indigo-50/70 text-slate-900' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50'"
                            class="px-3.5 py-2 rounded-xl border text-left transition">
                        <p class="text-xs font-bold">Semua Produk</p>
                        <p class="text-[11px] text-slate-400 mt-0.5" x-text="products.length + ' item'"></p>
                    </button>

                    <template x-for="(cat, idx) in categories" :key="cat.id">
                        <button type="button"
                                x-show="showAllCats || idx < 4 || selectedCategory === cat.id"
                                @click="selectedCategory = cat.id"
                                :class="selectedCategory === cat.id ? 'border-indigo-600 bg-indigo-50/70 text-slate-900' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50'"
                                class="px-3.5 py-2 rounded-xl border text-left transition">
                            <p class="text-xs font-bold" x-text="cat.name"></p>
                            <p class="text-[11px] text-slate-400 mt-0.5" x-text="cat.products_count + ' item'"></p>
                        </button>
                    </template>

                    <button type="button"
                            x-show="categories.length > 4"
                            @click="showAllCats = !showAllCats"
                            class="px-3 py-2 rounded-xl border border-dashed border-slate-300 bg-slate-50/70 hover:bg-slate-100 text-[11px] font-bold text-slate-600 transition">
                        <span x-text="showAllCats ? 'Ringkas' : '+' + (categories.length - 4) + ' lainnya'"></span>
                    </button>
                </div>
            </div>

            <!-- Grid Kartu Produk -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 2xl:grid-cols-5 gap-4">
                <template x-for="product in filteredProducts" :key="product.id">
                    <div :class="getQtyInCart(product.id) > 0 ? 'border-indigo-600 ring-1 ring-indigo-600/30' : 'border-slate-200/80 hover:border-slate-300'"
                         class="bg-white rounded-2xl p-3.5 border transition flex flex-col justify-between shadow-xs group">

                        <div @click="if (product.stock > 0) addToCart(product)" class="cursor-pointer">
                            <div class="h-32 rounded-xl bg-slate-100 flex items-center justify-center relative mb-3 overflow-hidden border border-slate-200/60">
                                <template x-if="product.image">
                                    <img :src="'/storage/' + product.image" :alt="product.name"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </template>
                                <template x-if="!product.image">
                                    <span class="text-xl font-extrabold text-slate-400 tracking-wider" x-text="product.name.substring(0, 2).toUpperCase()"></span>
                                </template>

                                <span class="absolute top-2 right-2 text-[10px] font-extrabold px-2 py-0.5 rounded-md bg-slate-900/80 text-white border border-white/15 backdrop-blur-xs shadow-2xs">
                                    Stok: <span x-text="product.stock"></span>
                                </span>
                            </div>

                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[11px] font-medium text-slate-400 truncate" x-text="product.category ? product.category.name : 'Umum'"></span>
                                <span class="text-[10px] font-mono text-slate-400 shrink-0" x-text="product.sku"></span>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 line-clamp-1 mt-0.5" x-text="product.name"></h3>
                        </div>

                        <div class="flex items-center justify-between pt-3 mt-3 border-t border-slate-100">
                            <span class="text-sm font-extrabold text-slate-900" x-text="formatRupiah(product.sell_price)"></span>

                            <div class="flex items-center space-x-1.5">
                                <button type="button" @click="decrementProduct(product)"
                                        :disabled="getQtyInCart(product.id) === 0"
                                        class="w-7 h-7 rounded-lg border border-slate-200 bg-white flex items-center justify-center text-slate-600 hover:bg-slate-50 disabled:opacity-35 font-bold text-sm transition">
                                    &minus;
                                </button>
                                <span class="text-xs font-extrabold w-5 text-center text-slate-800" x-text="getQtyInCart(product.id)"></span>
                                <button type="button" @click="addToCart(product)"
                                        :disabled="product.stock <= 0 || getQtyInCart(product.id) >= product.stock"
                                        class="btn-primary-theme w-7 h-7 rounded-lg flex items-center justify-center disabled:opacity-35 font-bold text-sm">
                                    +
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- PANEL KANAN: Struk & Pembayaran -->
        <div class="w-full xl:w-96 bg-white border-t xl:border-t-0 xl:border-l border-slate-200/80 p-5 sm:p-6 flex flex-col justify-between shrink-0">
            <div class="space-y-5 flex-1 flex flex-col min-h-0">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 shrink-0">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900">Struk Transaksi</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Kasir: {{ Auth::user()->name }}</p>
                    </div>
                    <button type="button" @click="clearCart()" x-show="cart.length > 0"
                            class="btn-danger-soft px-2.5 py-1.5 rounded-xl text-xs font-bold inline-flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span>Reset</span>
                    </button>
                </div>

                <div class="flex-1 flex flex-col min-h-0">
                    <div class="flex items-center justify-between text-xs font-bold text-slate-500 mb-3 shrink-0">
                        <span>Item Dipilih</span>
                        <span class="px-2.5 py-0.5 rounded-md bg-slate-100 text-slate-700 font-bold" x-text="totalItems + ' pcs'"></span>
                    </div>

                    <template x-if="cart.length === 0">
                        <div class="py-10 text-center border border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                            <p class="text-xs font-semibold text-slate-500">Belum ada produk dipilih</p>
                            <p class="text-[11px] text-slate-400 mt-1">Klik produk atau tombol + di katalog</p>
                        </div>
                    </template>

                    <div class="space-y-2.5 overflow-y-auto pr-1 flex-1">
                        <template x-for="item in cart" :key="item.product_id">
                            <div class="flex items-center justify-between text-xs py-2 border-b border-slate-100">
                                <div class="pr-2 min-w-0">
                                    <p class="font-bold text-slate-800 truncate">
                                        <span class="text-indigo-600 font-extrabold mr-1" x-text="item.qty + 'x'"></span>
                                        <span x-text="item.name"></span>
                                    </p>
                                    <p class="text-[11px] text-slate-400 mt-0.5" x-text="'@ ' + formatRupiah(item.price)"></p>
                                </div>
                                <span class="font-extrabold text-slate-900 shrink-0" x-text="formatRupiah(item.price * item.qty)"></span>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200/80 space-y-4 mt-4 shrink-0">
                <div class="space-y-2 text-xs">
                    <div class="flex justify-between text-slate-500">
                        <span>Subtotal</span>
                        <span class="font-semibold text-slate-800" x-text="formatRupiah(totalAmount)"></span>
                    </div>
                    <div class="flex justify-between text-base font-extrabold text-slate-900 pt-2 border-t border-slate-100">
                        <span>Total Bayar</span>
                        <span class="text-indigo-600" x-text="formatRupiah(totalAmount)"></span>
                    </div>
                </div>

                <div>
                    <p class="text-xs font-bold text-slate-700 mb-2">Metode Pembayaran</p>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" @click="paymentMethod = 'cash'"
                                :class="paymentMethod === 'cash' ? 'border-indigo-600 bg-indigo-50/70 text-slate-900' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50'"
                                class="py-2 rounded-xl border text-xs font-bold transition">
                            Tunai
                        </button>
                        <button type="button" @click="paymentMethod = 'qris'; setPaymentAmount(totalAmount)"
                                :class="paymentMethod === 'qris' ? 'border-indigo-600 bg-indigo-50/70 text-slate-900' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50'"
                                class="py-2 rounded-xl border text-xs font-bold transition">
                            QRIS
                        </button>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-xs font-bold text-slate-700">Uang Diterima (Rp)</label>
                        <button type="button" @click="setPaymentAmount(totalAmount)" x-show="totalAmount > 0"
                                class="text-[11px] font-bold text-indigo-600 hover:underline">
                            Uang Pas
                        </button>
                    </div>
                    <div class="relative">
                        <span class="text-xs font-extrabold text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2">Rp</span>
                        <input type="text"
                               inputmode="numeric"
                               :value="formatNumber(paymentAmount)"
                               @input="handlePaymentInput($event)"
                               placeholder="0"
                               class="w-full pl-9 pr-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm font-extrabold text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition">
                    </div>
                </div>

                <div class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200/80 text-xs">
                    <span class="font-semibold text-slate-600">Kembalian</span>
                    <span class="text-sm font-extrabold text-emerald-600 dark:text-emerald-400"
                          x-text="formatRupiah(Math.max(0, paymentAmount - totalAmount))"></span>
                </div>

                <template x-if="errorMessage">
                    <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold" x-text="errorMessage"></div>
                </template>

                <button type="button" @click="checkout()" :disabled="cart.length === 0 || isLoading"
                        class="btn-primary-theme w-full py-3.5 px-4 rounded-xl font-extrabold text-sm shadow-xs disabled:opacity-45 disabled:cursor-not-allowed">
                    <span x-text="isLoading ? 'Memproses Transaksi...' : 'Selesaikan Pembayaran'"></span>
                </button>
            </div>
        </div>

        @include('pos.partials.receipt-modal')
    </div>

    <script type="application/json" id="pos-initial-data">
        {!! json_encode([
            'products' => $products,
            'categories' => $categories,
            'recentTransactions' => $recentTransactions,
            'cashierName' => Auth::user()->name,
            'checkoutUrl' => route('pos.store'),
            'csrfToken' => csrf_token(),
        ], JSON_UNESCAPED_UNICODE) !!}
    </script>

    <script>
        window.posApp = function posApp() {
            const rawEl = document.getElementById('pos-initial-data');
            const cfg = rawEl ? JSON.parse(rawEl.textContent || '{}') : {};
            const {
                products = [],
                categories = [],
                recentTransactions = [],
                cashierName = 'Kasir',
                checkoutUrl = '',
                csrfToken = ''
            } = cfg;

            return {
                products,
                categories,
                recentTransactions: (recentTransactions || []).slice(0, 4).map(trx => ({
                    invoice_number: trx.invoice_number,
                    total_amount: Number(trx.total_amount),
                    time_label: 'Tercatat'
                })),
                selectedCategory: 'all',
                search: '',
                cart: [],
                paymentMethod: 'cash',
                paymentAmount: 0,
                errorMessage: '',
                isLoading: false,
                showReceiptModal: false,
                receipt: {
                    invoice_number: '',
                    printed_at: '',
                    cashier: cashierName,
                    payment_method: 'cash',
                    items: [],
                    total_amount: 0,
                    payment_amount: 0,
                    change_amount: 0
                },

                get filteredProducts() {
                    const q = this.search.trim().toLowerCase();
                    return this.products.filter(p => {
                        const matchCat = this.selectedCategory === 'all' || p.category_id === this.selectedCategory;
                        const matchSearch = !q || p.name.toLowerCase().includes(q) || (p.sku && p.sku.toLowerCase().includes(q));
                        return matchCat && matchSearch;
                    });
                },

                get totalAmount() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                },

                get totalItems() {
                    return this.cart.reduce((sum, item) => sum + item.qty, 0);
                },

                getQtyInCart(productId) {
                    const found = this.cart.find(i => i.product_id === productId);
                    return found ? found.qty : 0;
                },

                formatRupiah(value) {
                    return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
                },

                formatNumber(value) {
                    const num = Number(value) || 0;
                    return num === 0 ? '' : num.toLocaleString('id-ID');
                },

                handlePaymentInput(event) {
                    const rawDigits = event.target.value.replace(/\D/g, '');
                    const numericValue = rawDigits ? parseInt(rawDigits, 10) : 0;
                    this.paymentAmount = numericValue;
                    event.target.value = numericValue === 0 ? '' : numericValue.toLocaleString('id-ID');
                },

                setPaymentAmount(amount) {
                    this.paymentAmount = Number(amount) || 0;
                },

                addToCart(product) {
                    this.errorMessage = '';
                    const existing = this.cart.find(i => i.product_id === product.id);
                    if (existing) {
                        if (existing.qty + 1 > product.stock) {
                            this.errorMessage = 'Jumlah melebihi stok tersedia!';
                            return;
                        }
                        existing.qty++;
                    } else {
                        this.cart.push({
                            product_id: product.id,
                            name: product.name,
                            price: Number(product.sell_price),
                            qty: 1
                        });
                    }
                    if (this.paymentMethod !== 'cash') {
                        this.setPaymentAmount(this.totalAmount);
                    }
                },

                decrementProduct(product) {
                    const idx = this.cart.findIndex(i => i.product_id === product.id);
                    if (idx === -1) return;
                    if (this.cart[idx].qty > 1) {
                        this.cart[idx].qty--;
                    } else {
                        this.cart.splice(idx, 1);
                    }
                },

                clearCart() {
                    this.cart = [];
                    this.paymentAmount = 0;
                    this.errorMessage = '';
                },

                closeReceiptModal() {
                    this.showReceiptModal = false;
                },

                printReceipt() {
                    window.print();
                },

                async checkout() {
                    this.errorMessage = '';
                    this.isLoading = true;

                    const items = this.cart.map(i => ({ ...i }));
                    const total = this.totalAmount;
                    const method = this.paymentMethod;
                    const paid = method === 'qris' ? total : this.paymentAmount;

                    try {
                        const response = await fetch(checkoutUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                items: items.map(i => ({ product_id: i.product_id, qty: i.qty })),
                                payment_method: method,
                                payment_amount: paid
                            })
                        });

                        const data = await response.json();

                        if (!response.ok || !data.success) {
                            this.errorMessage = data.message || 'Gagal memproses transaksi.';
                            return;
                        }

                        items.forEach(c => {
                            const p = this.products.find(prod => prod.id === c.product_id);
                            if (p) p.stock = Math.max(0, p.stock - c.qty);
                        });

                        this.recentTransactions.unshift({
                            invoice_number: data.invoice_number,
                            total_amount: total,
                            time_label: 'Baru saja'
                        });
                        if (this.recentTransactions.length > 4) this.recentTransactions.pop();

                        const now = new Date();
                        this.receipt = {
                            invoice_number: data.invoice_number,
                            printed_at: now.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' }) +
                                        ', ' + now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }),
                            cashier: cashierName,
                            payment_method: method,
                            items,
                            total_amount: total,
                            payment_amount: paid,
                            change_amount: Number(data.change_amount ?? Math.max(0, paid - total))
                        };

                        this.cart = [];
                        this.paymentAmount = 0;
                        this.paymentMethod = 'cash';
                        this.showReceiptModal = true;
                    } catch (err) {
                        this.errorMessage = 'Terjadi kesalahan jaringan.';
                    } finally {
                        this.isLoading = false;
                    }
                }
            };
        };
    </script>
</x-app-layout>