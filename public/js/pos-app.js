window.posApp = function posApp({ products, categories, recentTransactions, cashierName, checkoutUrl, csrfToken }) {
    return {
        products: products || [],
        categories: categories || [],
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
            cashier: cashierName || 'Kasir',
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
            const paid = this.paymentAmount;
            const method = this.paymentMethod;

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