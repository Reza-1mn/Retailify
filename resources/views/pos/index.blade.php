<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Retailify — Point of Sale (Kasir)
        </h2>
    </x-slot>

    <div class="py-6" x-data="posApp({{ $products->toJson() }})">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Kolom Kiri: Daftar Produk -->
            <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow">
                <div class="mb-4">
                    <input type="text" x-model="search" placeholder="Cari nama produk atau SKU..."
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div class="border rounded-lg p-4 flex flex-col justify-between hover:border-indigo-500 transition">
                            <div>
                                <span class="text-xs text-gray-500" x-text="product.sku"></span>
                                <h3 class="font-bold text-gray-800" x-text="product.name"></h3>
                                <p class="text-indigo-600 font-semibold mt-1" x-text="formatRupiah(product.sell_price)"></p>
                                <p class="text-xs mt-1" :class="product.stock > 0 ? 'text-green-600' : 'text-red-600'">
                                    Stok: <span x-text="product.stock"></span>
                                </p>
                            </div>
                            <button @click="addToCart(product)" :disabled="product.stock <= 0"
                                class="mt-3 w-full py-2 px-3 rounded text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 disabled:bg-gray-300 disabled:cursor-not-allowed">
                                + Tambah
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Kolom Kanan: Keranjang & Pembayaran -->
            <div class="bg-white p-6 rounded-lg shadow flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-lg border-b pb-2 mb-4">Keranjang Belanja</h3>

                    <template x-if="cart.length === 0">
                        <p class="text-sm text-gray-500 text-center py-8">Belum ada item dipilih.</p>
                    </template>

                    <div class="space-y-3 max-h-80 overflow-y-auto">
                        <template x-for="(item, index) in cart" :key="item.product_id">
                            <div class="flex justify-between items-center border-b pb-2">
                                <div>
                                    <p class="font-medium text-sm" x-text="item.name"></p>
                                    <p class="text-xs text-gray-500" x-text="formatRupiah(item.price) + ' x ' + item.qty"></p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button @click="updateQty(index, -1)" class="px-2 py-0.5 bg-gray-200 rounded">-</button>
                                    <span class="text-sm font-semibold" x-text="item.qty"></span>
                                    <button @click="updateQty(index, 1)" class="px-2 py-0.5 bg-gray-200 rounded">+</button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="border-t pt-4 mt-4 space-y-3">
                    <div class="flex justify-between font-bold text-lg">
                        <span>Total:</span>
                        <span x-text="formatRupiah(totalAmount)"></span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Uang Bayar (Rp)</label>
                        <input type="number" x-model.number="paymentAmount" min="0"
                            class="mt-1 w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div class="flex justify-between text-sm font-semibold text-gray-700">
                        <span>Kembalian:</span>
                        <span x-text="formatRupiah(Math.max(0, paymentAmount - totalAmount))"></span>
                    </div>

                    <template x-if="errorMessage">
                        <div class="p-2 bg-red-100 text-red-700 text-xs rounded" x-text="errorMessage"></div>
                    </template>

                    <template x-if="successMessage">
                        <div class="p-2 bg-green-100 text-green-700 text-xs rounded" x-text="successMessage"></div>
                    </template>

                    <button @click="checkout()" :disabled="cart.length === 0 || isLoading"
                        class="w-full py-2.5 px-4 bg-green-600 hover:bg-green-700 text-white font-bold rounded-md disabled:bg-gray-300">
                        <span x-text="isLoading ? 'Memproses...' : 'Proses Pembayaran'"></span>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <script>
        function posApp(initialProducts) {
            return {
                products: initialProducts,
                search: '',
                cart: [],
                paymentAmount: 0,
                errorMessage: '',
                successMessage: '',
                isLoading: false,

                get filteredProducts() {
                    if (!this.search) return this.products;
                    const q = this.search.toLowerCase();
                    return this.products.filter(p =>
                        p.name.toLowerCase().includes(q) || p.sku.toLowerCase().includes(q)
                    );
                },

                get totalAmount() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
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
                },

                updateQty(index, delta) {
                    const item = this.cart[index];
                    const newQty = item.qty + delta;
                    if (newQty <= 0) {
                        this.cart.splice(index, 1);
                    } else if (newQty <= item.max_stock) {
                        item.qty = newQty;
                    } else {
                        this.errorMessage = 'Stok maksimal tercapai.';
                    }
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
                            this.successMessage = `Sukses! No. Invoice: ${data.invoice_number} | Kembalian: ${this.formatRupiah(data.change_amount)}`;
                            // Kurangi stok di tampilan lokal
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