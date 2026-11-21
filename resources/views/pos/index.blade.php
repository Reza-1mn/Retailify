<x-app-layout>
    <x-slot name="header">
        <h1 class="text-base sm:text-lg font-extrabold text-slate-900">Point of Sale (Kasir)</h1>
    </x-slot>

    <div class="flex flex-col xl:flex-row min-h-[calc(100vh-4rem)] xl:h-[calc(100vh-4rem)] xl:overflow-hidden bg-slate-50/70"
         x-data="posApp({{ $products->toJson() }}, {{ $categories->toJson() }})">
        
        <!-- KOLOM KIRI / TENGAH: Riwayat Singkat, Kategori & Grid Produk -->
        <div class="flex-1 p-5 sm:p-6 space-y-6 overflow-y-auto">
            
            <!-- Bagian 1: Transaksi Terakhir (Clean Neutral Cards) -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-sm font-bold text-slate-800">Transaksi Terakhir</h2>
                    <span class="text-xs font-medium text-slate-400">Real-Time POS</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    @forelse($recentTransactions as $trx)
                        <div class="p-3.5 rounded-2xl bg-white border border-slate-200/80 shadow-xs flex flex-col justify-between transition hover:border-slate-300">
                            <div class="flex items-center justify-between text-xs font-semibold text-slate-500">
                                <span class="font-mono truncate">{{ $trx->invoice_number }}</span>
                            </div>
                            <p class="text-base font-extrabold text-slate-900 mt-1">
                                Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                            </p>
                            <div class="flex items-center justify-between mt-2.5 pt-2 border-t border-slate-100">
                                <span class="text-[11px] text-slate-400">{{ $trx->created_at->diffForHumans() }}</span>
                                <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-slate-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Selesai
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full p-4 rounded-2xl bg-white border border-slate-200/80 text-xs text-slate-500">
                            Belum ada transaksi tercatat hari ini. Silakan pilih produk di bawah untuk memulai transaksi.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Bagian 2: Pencarian & Filter Kategori Produk -->
            <div class="space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <h2 class="text-sm font-bold text-slate-800">Katalog Produk</h2>
                    
                    <!-- Search Bar -->
                    <div class="relative w-full sm:w-80">
                        <input type="text" x-model="search" placeholder="Cari nama produk atau kode SKU..."
                               class="w-full pl-9 pr-4 py-2 rounded-xl bg-white border border-slate-200 text-xs sm:text-sm focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition">
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="M21 21l-4.35-4.35"/>
                        </svg>
                    </div>
                </div>

                <!-- Horizontal Category Pills -->
                <div class="flex items-center space-x-2.5 overflow-x-auto pb-1">
                    <button type="button" @click="selectedCategory = 'all'"
                            :class="selectedCategory === 'all' ? 'border-indigo-600 bg-indigo-50/70 text-slate-900' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50'"
                            class="px-4 py-2.5 rounded-xl border text-left shrink-0 transition">
                        <p class="text-xs font-bold">Semua Produk</p>
                        <p class="text-[11px] text-slate-400 mt-0.5" x-text="products.length + ' item'"></p>
                    </button>

                    <template x-for="cat in categories" :key="cat.id">
                        <button type="button" @click="selectedCategory = cat.id"
                                :class="selectedCategory === cat.id ? 'border-indigo-600 bg-indigo-50/70 text-slate-900' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50'"
                                class="px-4 py-2.5 rounded-xl border text-left shrink-0 transition">
                            <p class="text-xs font-bold" x-text="cat.name"></p>
                            <p class="text-[11px] text-slate-400 mt-0.5" x-text="cat.products_count + ' item'"></p>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Bagian 3: Grid Kartu Produk -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 2xl:grid-cols-5 gap-4">
                <template x-for="product in filteredProducts" :key="product.id">
                    <div :class="getQtyInCart(product.id) > 0 ? 'border-indigo-600 ring-1 ring-indigo-600/30' : 'border-slate-200/80 hover:border-slate-300'"
                         class="bg-white rounded-2xl p-3.5 border transition flex flex-col justify-between shadow-xs group">
                        
                        <div @click="if (product.stock > 0) addToCart(product)" class="cursor-pointer">
                            <!-- Visual Foto Produk / Placeholder Netral + Badge Stok -->
                            <div class="h-32 rounded-xl bg-slate-100 flex items-center justify-center relative mb-3 overflow-hidden border border-slate-200/60">
                                <template x-if="product.image">
                                    <img :src="'/storage/' + product.image" :alt="product.name"
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                </template>
                                <template x-if="!product.image">
                                    <span class="text-xl font-extrabold text-slate-400 tracking-wider" x-text="product.name.substring(0, 2).toUpperCase()"></span>
                                </template>
                                
                                <span class="absolute top-2 right-2 text-[10px] font-bold px-2 py-0.5 rounded-md bg-white/95 border border-slate-200/80 shadow-2xs"
                                      :class="product.stock > product.min_stock ? 'text-slate-700' : (product.stock > 0 ? 'text-amber-600' : 'text-rose-600')">
                                    Stok: <span x-text="product.stock"></span>
                                </span>
                            </div>

                            <div class="flex items-center justify-between gap-2">
                                <span class="text-[11px] font-medium text-slate-400 truncate" x-text="product.category ? product.category.name : 'Umum'"></span>
                                <span class="text-[10px] font-mono text-slate-400 shrink-0" x-text="product.sku"></span>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900 line-clamp-1 mt-0.5" x-text="product.name"></h3>
                        </div>

                        <!-- Baris Bawah: Harga & Tombol Stepper -->
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

        <!-- PANEL KANAN: Struk Pesanan & Ringkasan Pembayaran -->
        <div class="w-full xl:w-96 bg-white border-t xl:border-t-0 xl:border-l border-slate-200/80 p-5 sm:p-6 flex flex-col justify-between shrink-0">
            
            <div class="space-y-5 flex-1 flex flex-col min-h-0">
                <!-- Header Panel Struk -->
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 shrink-0">
                    <div>
                        <h2 class="text-base font-extrabold text-slate-900">Struk Transaksi</h2>
                        <p class="text-xs text-slate-400 mt-0.5">Kasir: {{ Auth::user()->name }}</p>
                    </div>
                    <button type="button" @click="clearCart()" x-show="cart.length > 0"
                            class="btn-danger-soft px-2.5 py-1.5 rounded-xl text-xs font-bold inline-flex items-center gap-1.5" title="Kosongkan Keranjang">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span>Reset</span>
                    </button>
                </div>

                <!-- Daftar Item Terpilih -->
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

            <!-- Ringkasan Pembayaran & Tombol Proses -->
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

                <!-- Pilihan Metode Bayar -->
                <div>
                    <p class="text-xs font-bold text-slate-700 mb-2">Metode Pembayaran</p>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" @click="paymentMethod = 'cash'"
                                :class="paymentMethod === 'cash' ? 'border-indigo-600 bg-indigo-50/70 text-slate-900' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50'"
                                class="py-2 rounded-xl border text-xs font-bold transition">
                            Tunai
                        </button>
                        <button type="button" @click="paymentMethod = 'qris'; paymentAmount = totalAmount"
                                :class="paymentMethod === 'qris' ? 'border-indigo-600 bg-indigo-50/70 text-slate-900' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50'"
                                class="py-2 rounded-xl border text-xs font-bold transition">
                            QRIS
                        </button>
                        <button type="button" @click="paymentMethod = 'debit'; paymentAmount = totalAmount"
                                :class="paymentMethod === 'debit' ? 'border-indigo-600 bg-indigo-50/70 text-slate-900' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50'"
                                class="py-2 rounded-xl border text-xs font-bold transition">
                            Debit
                        </button>
                    </div>
                </div>

                <!-- Input Nominal Diterima -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-xs font-bold text-slate-700">Uang Diterima (Rp)</label>
                        <button type="button" @click="paymentAmount = totalAmount" x-show="totalAmount > 0"
                                class="text-[11px] font-bold text-indigo-600 hover:underline">
                            Uang Pas
                        </button>
                    </div>
                    <input type="number" x-model.number="paymentAmount" min="0" placeholder="0"
                           class="w-full px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200 text-sm font-extrabold text-slate-900 focus:bg-white focus:border-indigo-600 focus:ring-1 focus:ring-indigo-600 outline-none transition">
                </div>

                <!-- Info Kembalian -->
                <div class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-slate-50 border border-slate-200/80 text-xs">
                    <span class="font-semibold text-slate-600">Kembalian</span>
                    <span class="text-sm font-extrabold text-slate-900" x-text="formatRupiah(Math.max(0, paymentAmount - totalAmount))"></span>
                </div>

                <!-- Notifikasi Error / Sukses -->
                <template x-if="errorMessage">
                    <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold" x-text="errorMessage"></div>
                </template>
                <template x-if="successMessage">
                    <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold" x-text="successMessage"></div>
                </template>

                <!-- Tombol Eksekusi Transaksi -->
                <button type="button" @click="checkout()" :disabled="cart.length === 0 || isLoading"
                        class="btn-primary-theme w-full py-3.5 px-4 rounded-xl font-extrabold text-sm shadow-xs disabled:opacity-45 disabled:cursor-not-allowed">
                    <span x-text="isLoading ? 'Memproses Transaksi...' : 'Selesaikan Pembayaran'"></span>
                </button>
            </div>

        </div>
    </div>

    <script>
        function posApp(initialProducts, initialCategories) {
            return {
                products: initialProducts,
                categories: initialCategories,
                selectedCategory: 'all',
                search: '',
                cart: [],
                paymentMethod: 'cash',
                paymentAmount: 0,
                errorMessage: '',
                successMessage: '',
                isLoading: false,

                get filteredProducts() {
                    return this.products.filter(p => {
                        const matchCat = this.selectedCategory === 'all' || p.category_id === this.selectedCategory;
                        const q = this.search.toLowerCase();
                        const matchSearch = !q || p.name.toLowerCase().includes(q) || p.sku.toLowerCase().includes(q);
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
                    return 'Rp ' + Number(value).toLocaleString('id-ID');
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
                            qty: 1,
                            max_stock: product.stock
                        });
                    }
                    if (this.paymentMethod !== 'cash') {
                        this.paymentAmount = this.totalAmount;
                    }
                },

                decrementProduct(product) {
                    const idx = this.cart.findIndex(i => i.product_id === product.id);
                    if (idx !== -1) {
                        if (this.cart[idx].qty > 1) {
                            this.cart[idx].qty--;
                        } else {
                            this.cart.splice(idx, 1);
                        }
                    }
                },

                clearCart() {
                    this.cart = [];
                    this.paymentAmount = 0;
                    this.errorMessage = '';
                },

                async checkout() {
                    this.errorMessage = '';
                    this.successMessage = '';
                    this.isLoading = true;

                    try {
                        const response = await fetch("{{ route('pos.store') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                items: this.cart.map(i => ({ product_id: i.product_id, qty: i.qty })),
                                payment_amount: this.paymentAmount
                            })
                        });

                        const data = await response.json();

                        if (!response.ok || !data.success) {
                            this.errorMessage = data.message || 'Gagal memproses transaksi.';
                        } else {
                            this.successMessage = `Sukses! Invoice ${data.invoice_number} | Kembalian: ${this.formatRupiah(data.change_amount)}`;
                            this.cart.forEach(c => {
                                const p = this.products.find(prod => prod.id === c.product_id);
                                if (p) p.stock -= c.qty;
                            });
                            this.cart = [];
                            this.paymentAmount = 0;
                        }
                    } catch (err) {
                        this.errorMessage = 'Terjadi kesalahan jaringan.';
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>
</x-app-layout>