// js/inventory.js
// Inventory management module for Alpine.js

import api from './api.js';

export const createInventoryModule = (state) => ({
    // Inventory state
    inventory: [],
    suppliers: [],
    purchases: [],
    waste: [],
    inventoryAlerts: [],
    inventoryReport: {
        totalItems: 0,
        totalValue: 0,
        lowStockItems: 0,
        outOfStockItems: 0,
        topSuppliers: [],
        recentTransactions: []
    },
    
    // Form states
    showInventoryForm: false,
    showPurchaseForm: false,
    showSupplierForm: false,
    showWasteForm: false,
    editingInventory: null,
    editingPurchase: null,
    editingSupplier: null,
    
    // Inventory form
    inventoryForm: {
        name: '',
        category: '',
        unit: '',
        currentStock: 0,
        minStock: 0,
        maxStock: 0,
        cost: 0,
        supplier_id: '',
        location: '',
        expiryDate: null,
        notes: ''
    },
    
    // Purchase form
    purchaseForm: {
        supplier_id: '',
        items: [],
        totalCost: 0,
        purchaseDate: new Date().toISOString().split('T')[0],
        expectedDelivery: null,
        notes: ''
    },
    
    // Supplier form
    supplierForm: {
        name: '',
        contact_person: '',
        phone: '',
        email: '',
        address: '',
        payment_terms: '',
        notes: ''
    },
    
    // Waste form
    wasteForm: {
        inventory_id: '',
        quantity: 0,
        reason: '',
        waste_date: new Date().toISOString().split('T')[0],
        notes: ''
    },
    
    // Filters and search
    inventoryTab: 'inventory',
    inventorySearchTerm: '',
    inventoryFilterCategory: 'all',
    inventorySortBy: 'name',
    inventorySortOrder: 'asc',
    
    // Pagination
    inventoryCurrentPage: 1,
    inventoryPerPage: 15,
    inventoryTotalPages: 1,
    inventoryTotalItems: 0,
    
    // Load all inventory-related data from API
    async loadInventory(page = null) {
        // Load each resource independently so one failure doesn't break everything
        try {
            // Inventory - with pagination
            const currentPage = page !== null ? page : this.inventoryCurrentPage;
            const params = new URLSearchParams({
                page: currentPage.toString(),
                per_page: this.inventoryPerPage.toString(),
                sort_by: this.inventorySortBy,
                sort_order: this.inventorySortOrder
            });
            
            // Add filters
            if (this.inventorySearchTerm) {
                params.append('search', this.inventorySearchTerm);
            }
            if (this.inventoryFilterCategory && this.inventoryFilterCategory !== 'all') {
                params.append('category', this.inventoryFilterCategory);
            }
            
            const invRes = await api.request('get', `/inventory?${params.toString()}`);
            if (invRes.success) {
                // Handle paginated response
                const responseData = invRes.data.data || invRes.data;
                let data = [];
                
                // Check if it's a paginated response
                if (responseData && typeof responseData === 'object') {
                    if (Array.isArray(responseData)) {
                        // Non-paginated array
                        data = responseData;
                        this.inventoryTotalItems = data.length;
                        this.inventoryTotalPages = 1;
                    } else if (responseData.data && Array.isArray(responseData.data)) {
                        // Paginated response
                        data = responseData.data;
                        this.inventoryCurrentPage = responseData.current_page || currentPage;
                        this.inventoryTotalPages = responseData.last_page || 1;
                        this.inventoryTotalItems = responseData.total || 0;
                    } else if (responseData.data && Array.isArray(responseData)) {
                        // Another possible structure
                        data = responseData;
                        this.inventoryTotalItems = data.length;
                        this.inventoryTotalPages = 1;
                    }
                }
                
                // Convert snake_case to camelCase for frontend
                this.inventory = Array.isArray(data) ? data.map(item => this.normalizeInventoryItem(item)) : [];
            } else {
                this.inventory = [];
                this.inventoryTotalItems = 0;
                this.inventoryTotalPages = 1;
            }
        } catch (error) {
            console.error('Error loading inventory:', error);
            this.inventory = [];
            this.inventoryTotalItems = 0;
            this.inventoryTotalPages = 1;
        }
        
        try {
            // Suppliers
            const supRes = await api.request('get', '/suppliers');
            this.suppliers = supRes.success ? (supRes.data.data || supRes.data || []) : [];
        } catch (error) {
            console.error('Error loading suppliers:', error);
            this.suppliers = [];
        }
        
        try {
            // Purchases
            const purRes = await api.request('get', '/purchases');
            this.purchases = purRes.success ? (purRes.data.data || purRes.data || []) : [];
        } catch (error) {
            console.error('Error loading purchases:', error);
            this.purchases = [];
        }
        
        try {
            // Waste
            const wasteRes = await api.request('get', '/waste');
            this.waste = wasteRes.success ? (wasteRes.data.data || wasteRes.data || []) : [];
        } catch (error) {
            console.error('Error loading waste:', error);
            this.waste = [];
        }
        
        try {
            // Alerts
            await this.updateInventoryAlerts();
        } catch (error) {
            console.error('Error updating inventory alerts:', error);
        }

        await this.refreshInventoryReport();
    },
    
    // Inventory CRUD
    async addInventory() {
        this.editingInventory = null;
        this.resetInventoryForm();
        this.showInventoryForm = true;
    },
    async editInventory(item) {
        this.editingInventory = item;
        // Map item data to form format (handle both camelCase and snake_case)
        this.inventoryForm = {
            id: item.id,
            name: item.name || '',
            category: item.category || '',
            unit: item.unit || '',
            currentStock: item.currentStock || item.current_stock || 0,
            minStock: item.minStock || item.min_stock || 0,
            maxStock: item.maxStock || item.max_stock || 0,
            cost: item.cost || item.cost_per_unit || 0,
            supplier_id: item.supplier_id || null,
            location: item.location || '',
            expiryDate: item.expiryDate || item.expiry_date || null,
            notes: item.notes || '',
            sku: item.sku || '',
            description: item.description || ''
        };
        this.showInventoryForm = true;
    },
    async saveInventory() {
        try {
            // Convert camelCase form data to snake_case for API
            const formData = {
                name: this.inventoryForm.name,
                category: this.inventoryForm.category,
                unit: this.inventoryForm.unit,
                current_stock: this.inventoryForm.currentStock || 0,
                min_stock: this.inventoryForm.minStock || 0,
                max_stock: this.inventoryForm.maxStock || 0,
                cost_per_unit: this.inventoryForm.cost || 0,
                supplier_id: this.inventoryForm.supplier_id || null,
                location: this.inventoryForm.location || '',
                expiry_date: this.inventoryForm.expiryDate || null,
                notes: this.inventoryForm.notes || '',
                sku: this.inventoryForm.sku || '',
                description: this.inventoryForm.description || ''
            };

            let response;
            if (this.editingInventory && this.inventoryForm.id) {
                // Update
                response = await api.request('put', `/inventory/${this.inventoryForm.id}`, formData);
                
                if (response.success) {
                    // Update the item in the local list
                    const itemData = response.data.data || response.data;
                    if (itemData) {
                        const index = this.inventory.findIndex(item => item.id === itemData.id);
                        if (index !== -1) {
                            // Convert to camelCase and update
                            this.inventory[index] = {
                                id: itemData.id,
                                name: itemData.name,
                                category: itemData.category,
                                unit: itemData.unit,
                                currentStock: itemData.current_stock || itemData.currentStock || 0,
                                minStock: itemData.min_stock || itemData.minStock || 0,
                                maxStock: itemData.max_stock || itemData.maxStock || 0,
                                cost: itemData.cost_per_unit || itemData.cost || 0,
                                supplier_id: itemData.supplier_id,
                                supplier: itemData.supplier ? (itemData.supplier.name || itemData.supplier) : '',
                                location: itemData.location || '',
                                expiryDate: itemData.expiry_date || itemData.expiryDate,
                                notes: itemData.notes || '',
                                isActive: itemData.is_active !== undefined ? itemData.is_active : (itemData.isActive !== undefined ? itemData.isActive : true),
                                sku: itemData.sku || '',
                                description: itemData.description || ''
                            };
                        }
                    }
                }
            } else {
                // Create
                response = await api.request('post', '/inventory', formData);
                
                if (response.success) {
                    // Add the new item to the local list immediately
                    const itemData = response.data.data || response.data;
                    if (itemData) {
                        const newItem = {
                            id: itemData.id,
                            name: itemData.name,
                            category: itemData.category,
                            unit: itemData.unit,
                            currentStock: itemData.current_stock || itemData.currentStock || 0,
                            minStock: itemData.min_stock || itemData.minStock || 0,
                            maxStock: itemData.max_stock || itemData.maxStock || 0,
                            cost: itemData.cost_per_unit || itemData.cost || 0,
                            supplier_id: itemData.supplier_id,
                            supplier: itemData.supplier ? (itemData.supplier.name || itemData.supplier) : '',
                            location: itemData.location || '',
                            expiryDate: itemData.expiry_date || itemData.expiryDate,
                            notes: itemData.notes || '',
                            isActive: itemData.is_active !== undefined ? itemData.is_active : (itemData.isActive !== undefined ? itemData.isActive : true),
                            sku: itemData.sku || '',
                            description: itemData.description || ''
                        };
                        this.inventory.push(newItem);
                    }
                }
            }

            if (response.success) {
                this.showInventoryForm = false;
                this.editingInventory = null;
                this.resetInventoryForm();
                // Reload inventory to ensure we have the latest data from server
                await this.loadInventory();
            } else {
                alert(response.message || 'Failed to save inventory item');
            }
        } catch (error) {
            console.error('Error saving inventory:', error);
            alert('Error saving inventory item. Please try again.');
        }
    },
    async saveInventoryItem() {
        // Alias for saveInventory to match form submission
        return await this.saveInventory();
    },
    async deleteInventory(id) {
        if (confirm('Are you sure you want to delete this inventory item?')) {
            await api.request('delete', `/inventory/${id}`);
            await this.loadInventory();
        }
    },
    resetInventoryForm() {
        this.inventoryForm = {
            name: '',
            category: '',
            unit: '',
            currentStock: 0,
            minStock: 0,
            maxStock: 0,
            cost: 0,
            supplier_id: '',
            location: '',
            expiryDate: null,
            notes: ''
        };
    },
    
    // Stock operations
    async updateStock(id, quantity, operation = 'add', reason = 'Manual adjustment') {
        try {
            const adjustmentData = {
                quantity: quantity,
                adjustment_type: operation,
                reason: reason
            };
            
            await api.request('patch', `/inventory/${id}/adjust-stock`, adjustmentData);
            await this.loadInventory();
        } catch (error) {
            console.error('Error updating stock:', error);
            alert('Error updating stock. Please try again.');
        }
    },
    
    // Automatic stock deduction from orders
    async deductStockFromOrder(order) {
        try {
            for (const orderItem of order.items) {
                const recipe = state.recipes.find(r => r.id === orderItem.id);
                if (recipe && recipe.ingredients) {
                    for (const ingredient of recipe.ingredients) {
                        const inventoryItem = this.inventory.find(item => 
                            item.name.toLowerCase() === ingredient.name.toLowerCase()
                        );
                        
                        if (inventoryItem) {
                            const totalQuantity = ingredient.quantity * orderItem.quantity;
                            await this.updateStock(
                                inventoryItem.id, 
                                totalQuantity, 
                                'subtract', 
                                `Order #${order.id} - ${recipe.name}`
                            );
                        }
                    }
                }
            }
        } catch (error) {
            console.error('Error deducting stock from order:', error);
        }
    },
    
    // Get stock transactions
    async getStockTransactions(itemId = null) {
        try {
            if (itemId) {
                const response = await api.request('get', `/inventory/${itemId}/transactions`);
                return response.success ? (response.data.data || response.data) : [];
            } else {
                // For all transactions, we'll need to implement a general endpoint
                // For now, return empty array
                return [];
            }
        } catch (error) {
            console.error('Error getting stock transactions:', error);
            return [];
        }
    },
    
    // Purchase management
    async addPurchase() {
        this.editingPurchase = null;
        this.resetPurchaseForm();
        this.showPurchaseForm = true;
    },
    
    async editPurchase(purchase) {
        this.editingPurchase = purchase;
        this.purchaseForm = { ...purchase };
        this.showPurchaseForm = true;
    },
    
    async savePurchase() {
        try {
            if (this.editingPurchase && this.purchaseForm.id) {
                // Update
                await api.request('put', `/purchases/${this.purchaseForm.id}`, this.purchaseForm);
            } else {
                // Create
                await api.request('post', '/purchases', this.purchaseForm);
            }
            
            this.showPurchaseForm = false;
            this.editingPurchase = null;
            this.resetPurchaseForm();
            await this.loadInventory();
        } catch (error) {
            console.error('Error saving purchase:', error);
            alert('Error saving purchase. Please try again.');
        }
    },
    
    async receivePurchase(purchaseId) {
        try {
            const purchase = this.purchases.find(p => p.id === purchaseId);
            if (purchase) {
                // Update purchase status to delivered
                await api.request('patch', `/purchases/${purchaseId}/status`, {
                    status: 'delivered',
                    delivery_date: new Date().toISOString().split('T')[0]
                });
                
                await this.loadInventory();
            }
        } catch (error) {
            console.error('Error receiving purchase:', error);
            alert('Error receiving purchase. Please try again.');
        }
    },
    
    resetPurchaseForm() {
        this.purchaseForm = {
            supplier_id: '',
            items: [],
            totalCost: 0,
            purchaseDate: new Date().toISOString().split('T')[0],
            expectedDelivery: null,
            notes: ''
        };
    },
    
    addPurchaseItem() {
        this.purchaseForm.items.push({
            inventory_id: '',
            quantity: 0,
            unit_cost: 0,
            notes: ''
        });
    },
    
    removePurchaseItem(index) {
        this.purchaseForm.items.splice(index, 1);
    },
    
    calculatePurchaseTotal() {
        this.purchaseForm.totalCost = this.purchaseForm.items.reduce(
            (sum, item) => sum + (item.unit_cost * item.quantity), 0
        );
    },
    
    // Supplier management
    async addSupplier() {
        this.editingSupplier = null;
        this.resetSupplierForm();
        this.showSupplierForm = true;
    },
    
    async editSupplier(supplier) {
        this.editingSupplier = supplier;
        this.supplierForm = { ...supplier };
        this.showSupplierForm = true;
    },
    
    async saveSupplier() {
        try {
            if (this.editingSupplier && this.supplierForm.id) {
                // Update
                await api.request('put', `/suppliers/${this.supplierForm.id}`, this.supplierForm);
            } else {
                // Create
                await api.request('post', '/suppliers', this.supplierForm);
            }
            
            this.showSupplierForm = false;
            this.editingSupplier = null;
            this.resetSupplierForm();
            await this.loadInventory();
        } catch (error) {
            console.error('Error saving supplier:', error);
            alert('Error saving supplier. Please try again.');
        }
    },
    
    async deleteSupplier(id) {
        if (confirm('Are you sure you want to delete this supplier?')) {
            try {
                await api.request('delete', `/suppliers/${id}`);
                await this.loadInventory();
            } catch (error) {
                console.error('Error deleting supplier:', error);
                alert('Error deleting supplier. Please try again.');
            }
        }
    },
    
    resetSupplierForm() {
        this.supplierForm = {
            name: '',
            contact_person: '',
            phone: '',
            email: '',
            address: '',
            payment_terms: '',
            notes: ''
        };
    },
    
    // Waste management
    async addWaste() {
        this.resetWasteForm();
        this.showWasteForm = true;
    },
    
    async saveWaste() {
        try {
            await api.request('post', '/waste', this.wasteForm);
            
            this.showWasteForm = false;
            this.resetWasteForm();
            await this.loadInventory();
        } catch (error) {
            console.error('Error saving waste:', error);
            alert('Error saving waste. Please try again.');
        }
    },
    
    resetWasteForm() {
        this.wasteForm = {
            inventory_id: '',
            quantity: 0,
            reason: '',
            waste_date: new Date().toISOString().split('T')[0],
            notes: ''
        };
    },
    
    // Inventory alerts
    async updateInventoryAlerts() {
        try {
            const lowStockRes = await api.request('get', '/inventory/alerts/low-stock');
            const alerts = lowStockRes.success ? (lowStockRes.data.data || lowStockRes.data || []) : [];
            this.inventoryAlerts = Array.isArray(alerts)
                ? alerts.map(item => this.normalizeInventoryItem(item))
                : [];
        } catch (error) {
            console.error('Error updating inventory alerts:', error);
            // Fallback to local calculation
            this.inventoryAlerts = this.inventory.filter(item =>
                (item.currentStock ?? 0) <= (item.minStock ?? 0)
            );
        }
    },
    
    async getLowStockItems() {
        try {
            const response = await api.request('get', '/inventory/alerts/low-stock');
            return response.success ? (response.data.data || response.data) : [];
        } catch (error) {
            console.error('Error getting low stock items:', error);
            return this.inventory.filter(item => (item.currentStock ?? 0) <= (item.minStock ?? 0));
        }
    },
    
    async getOutOfStockItems() {
        try {
            const response = await api.request('get', '/inventory?status=out_of_stock');
            return response.success ? (response.data.data || response.data) : [];
        } catch (error) {
            console.error('Error getting out of stock items:', error);
            return this.inventory.filter(item => (item.currentStock ?? 0) <= 0);
        }
    },
    
    async getExpiringItems(days = 30) {
        try {
            const response = await api.request('get', `/inventory/alerts/expiring?days=${days}`);
            return response.success ? (response.data.data || response.data) : [];
        } catch (error) {
            console.error('Error getting expiring items:', error);
            return [];
        }
    },
    
    // Reports
    generateInventoryReport() {
        return this.inventoryReport;
    },

    async refreshInventoryReport() {
        try {
            const totalItems = this.inventory.length;
            const totalValue = this.inventory.reduce(
                (sum, item) => sum + ((item.currentStock ?? 0) * (item.cost ?? 0)),
                0
            );
            const lowStockItems = this.inventoryAlerts.length ||
                this.inventory.filter(item => (item.currentStock ?? 0) <= (item.minStock ?? 0)).length;
            const outOfStockItems = this.inventory.filter(item => (item.currentStock ?? 0) <= 0).length;

            let topSuppliers = [];
            try {
                const supplierRes = await api.request('get', '/suppliers/performance');
                topSuppliers = supplierRes.success
                    ? (supplierRes.data.data || supplierRes.data || [])
                    : [];
            } catch (error) {
                console.error('Error loading supplier performance:', error);
                topSuppliers = this.calculateTopSuppliersFallback();
            }

            let recentTransactions = [];
            try {
                recentTransactions = await this.getStockTransactions();
            } catch (error) {
                console.error('Error loading stock transactions:', error);
                recentTransactions = [];
            }

            this.inventoryReport = {
                totalItems,
                totalValue,
                lowStockItems,
                outOfStockItems,
                topSuppliers: Array.isArray(topSuppliers) ? topSuppliers.slice(0, 5) : [],
                recentTransactions: Array.isArray(recentTransactions) ? recentTransactions.slice(0, 5) : []
            };
        } catch (error) {
            console.error('Error refreshing inventory report:', error);
            this.inventoryReport = {
                totalItems: 0,
                totalValue: 0,
                lowStockItems: 0,
                outOfStockItems: 0,
                topSuppliers: [],
                recentTransactions: []
            };
        }
    },

    calculateTopSuppliersFallback() {
        const stats = {};
        this.inventory.forEach(item => {
            const supplierName = item.supplier || 'Unknown';
            if (!stats[supplierName]) {
                stats[supplierName] = { name: supplierName, value: 0, count: 0 };
            }
            stats[supplierName].count += 1;
            stats[supplierName].value += (item.cost ?? 0) * (item.currentStock ?? 0);
        });

        return Object.values(stats)
            .filter(supplier => supplier.name && supplier.name !== 'Unknown')
            .sort((a, b) => b.value - a.value);
    },

    normalizeInventoryItem(item = {}) {
        return {
            id: item.id,
            name: item.name || '',
            category: item.category || '',
            unit: item.unit || '',
            currentStock: Number(item.current_stock ?? item.currentStock ?? 0),
            minStock: Number(item.min_stock ?? item.minStock ?? 0),
            maxStock: Number(item.max_stock ?? item.maxStock ?? 0),
            cost: Number(item.cost_per_unit ?? item.cost ?? 0),
            supplier_id: item.supplier_id ?? null,
            supplier: item.supplier ? (item.supplier.name ?? item.supplier) : '',
            location: item.location || '',
            expiryDate: item.expiry_date ?? item.expiryDate ?? null,
            notes: item.notes || '',
            isActive: typeof item.is_active === 'boolean' ? item.is_active :
                (typeof item.isActive === 'boolean' ? item.isActive : true),
            sku: item.sku || '',
            description: item.description || ''
        };
    },
    
    async getTopSuppliers() {
        try {
            const response = await api.request('get', '/suppliers/performance');
            return response.success ? (response.data.data || response.data) : [];
        } catch (error) {
            console.error('Error getting top suppliers:', error);
            // Fallback to local calculation
            const supplierStats = {};
            this.inventory.forEach(item => {
                if (item.supplier_id) {
                    const supplier = this.suppliers.find(s => s.id === item.supplier_id);
                    if (supplier) {
                        if (!supplierStats[supplier.name]) {
                            supplierStats[supplier.name] = { count: 0, value: 0 };
                        }
                        supplierStats[supplier.name].count++;
                        supplierStats[supplier.name].value += item.current_stock * item.cost_per_unit;
                    }
                }
            });
            
            return Object.entries(supplierStats)
                .map(([name, stats]) => ({ name, ...stats }))
                .sort((a, b) => b.value - a.value)
                .slice(0, 5);
        }
    },
    
    // Export/Import
    async exportInventoryData() {
        try {
            const data = {
                inventory: this.inventory,
                suppliers: this.suppliers,
                purchases: this.purchases,
                waste: this.waste,
                transactions: await this.getStockTransactions(),
                exportDate: new Date().toISOString()
            };
            
            const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `inventory_backup_${new Date().toISOString().split('T')[0]}.json`;
            a.click();
            URL.revokeObjectURL(url);
        } catch (error) {
            console.error('Error exporting inventory data:', error);
            alert('Error exporting data. Please try again.');
        }
    },
    
    async importInventoryData(file) {
        const reader = new FileReader();
        reader.onload = async (e) => {
            try {
                const data = JSON.parse(e.target.result);
                
                // Import data through API
                if (data.inventory) {
                    for (const item of data.inventory) {
                        await api.request('post', '/inventory', item);
                    }
                }
                if (data.suppliers) {
                    for (const supplier of data.suppliers) {
                        await api.request('post', '/suppliers', supplier);
                    }
                }
                if (data.purchases) {
                    for (const purchase of data.purchases) {
                        await api.request('post', '/purchases', purchase);
                    }
                }
                if (data.waste) {
                    for (const waste of data.waste) {
                        await api.request('post', '/waste', waste);
                    }
                }
                
                await this.loadInventory();
                alert('Inventory data imported successfully!');
            } catch (error) {
                console.error('Error importing data:', error);
                alert('Error importing data: ' + error.message);
            }
        };
        reader.readAsText(file);
    },
    

    
    
    // Get inventory categories (synchronous - uses already loaded data)
    getInventoryCategories() {
        // Extract unique categories from loaded inventory
        const categories = [...new Set(this.inventory.map(item => item.category).filter(cat => cat))];
        return categories.sort();
    },
    
    // Get waste reasons
    async getWasteReasons() {
        try {
            const response = await api.request('get', '/waste/reasons');
            return response.success ? (response.data.data || response.data) : [];
        } catch (error) {
            console.error('Error getting waste reasons:', error);
            return ['Expired', 'Damaged', 'Quality Issue', 'Overstock', 'Other'];
        }
    },
    
    // Get waste categories
    async getWasteCategories() {
        try {
            const response = await api.request('get', '/waste/categories');
            return response.success ? (response.data.data || response.data) : [];
        } catch (error) {
            console.error('Error getting waste categories:', error);
            return ['Food Waste', 'Packaging', 'Equipment', 'Other'];
        }
    },
    
    // Get purchase statistics
    async getPurchaseStats() {
        try {
            const response = await api.request('get', '/purchases/stats');
            return response.success ? (response.data.data || response.data) : {};
        } catch (error) {
            console.error('Error getting purchase stats:', error);
            return {};
        }
    },
    
    // Get waste statistics
    async getWasteStats() {
        try {
            const response = await api.request('get', '/waste/stats');
            return response.success ? (response.data.data || response.data) : {};
        } catch (error) {
            console.error('Error getting waste stats:', error);
            return {};
        }
    },
    
    // Get overdue purchases
    async getOverduePurchases() {
        try {
            const response = await api.request('get', '/purchases/alerts/overdue');
            return response.success ? (response.data.data || response.data) : [];
        } catch (error) {
            console.error('Error getting overdue purchases:', error);
            return [];
        }
    },
    
    // Pagination functions
    async goToPage(page) {
        if (page >= 1 && page <= this.inventoryTotalPages) {
            this.inventoryCurrentPage = page;
            await this.loadInventory(page);
            // Scroll to top of inventory section
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    },
    
    async nextPage() {
        if (this.inventoryCurrentPage < this.inventoryTotalPages) {
            await this.goToPage(this.inventoryCurrentPage + 1);
        }
    },
    
    async prevPage() {
        if (this.inventoryCurrentPage > 1) {
            await this.goToPage(this.inventoryCurrentPage - 1);
        }
    },
    
    async changePerPage(perPage) {
        this.inventoryPerPage = parseInt(perPage);
        this.inventoryCurrentPage = 1;
        await this.loadInventory(1);
    },
    
    async applySearch() {
        this.inventoryCurrentPage = 1;
        await this.loadInventory(1);
    },
    
    async applyFilter() {
        this.inventoryCurrentPage = 1;
        await this.loadInventory(1);
    },
    
    async applySort() {
        this.inventoryCurrentPage = 1;
        await this.loadInventory(1);
    },
    
    // Get filtered inventory (now uses server-side filtering, but kept for compatibility)
    getFilteredInventory() {
        let filtered = [...this.inventory];
        
        // Apply search filter
        if (this.inventorySearchTerm) {
            const search = this.inventorySearchTerm.toLowerCase();
            filtered = filtered.filter(item => 
                (item.name && item.name.toLowerCase().includes(search)) ||
                (item.category && item.category.toLowerCase().includes(search)) ||
                (item.sku && item.sku.toLowerCase().includes(search))
            );
        }
        
        // Apply category filter
        if (this.inventoryFilterCategory !== 'all') {
            filtered = filtered.filter(item => item.category === this.inventoryFilterCategory);
        }
        
        // Apply sorting
        filtered.sort((a, b) => {
            let aVal, bVal;
            
            // Map frontend field names to backend field names for sorting
            const sortFieldMap = {
                'currentStock': 'currentStock',
                'name': 'name',
                'category': 'category',
                'cost': 'cost'
            };
            
            const sortField = sortFieldMap[this.inventorySortBy] || this.inventorySortBy;
            aVal = a[sortField];
            bVal = b[sortField];
            
            // Handle null/undefined values
            if (aVal == null) aVal = '';
            if (bVal == null) bVal = '';
            
            if (sortField === 'name' || sortField === 'category') {
                aVal = String(aVal).toLowerCase();
                bVal = String(bVal).toLowerCase();
            } else {
                aVal = Number(aVal) || 0;
                bVal = Number(bVal) || 0;
            }
            
            if (this.inventorySortOrder === 'asc') {
                return aVal > bVal ? 1 : (aVal < bVal ? -1 : 0);
            } else {
                return aVal < bVal ? 1 : (aVal > bVal ? -1 : 0);
            }
        });
        
        return filtered;
    },
    
    // Initialize inventory module
    async initInventory() {
        await this.loadInventory();
        await this.updateInventoryAlerts();
    },
    
    // Load inventory when tab is accessed
    async onInventoryTabChange() {
        if (this.inventory.length === 0) {
            await this.loadInventory();
        }
    }
});

// Initialize inventory module when the page loads
document.addEventListener('DOMContentLoaded', () => {
    if (window.inventory) {
        window.inventory.initInventory();
    }
}); 