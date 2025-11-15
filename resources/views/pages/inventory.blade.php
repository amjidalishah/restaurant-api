<div class="bg-white rounded-lg shadow-lg p-6" x-init="if (inventory.length === 0) { loadInventory(); }">
            <h2 class="text-2xl font-bold mb-6 text-primary flex items-center gap-2">
                <i class="fas fa-boxes"></i>
                <span x-text="translations.inventoryManagement"></span>
            </h2>
            
            <!-- Inventory Tabs -->
            <div class="flex flex-wrap gap-2 mb-6 border-b">
                <button @click="inventoryTab = 'inventory'; onInventoryTabChange()" 
                        class="px-4 py-2 rounded-t-lg font-medium transition"
                        :class="{'bg-primary text-white': inventoryTab === 'inventory', 'bg-gray-100 text-gray-700 hover:bg-gray-200': inventoryTab !== 'inventory'}">
                    <i class="fas fa-boxes mr-2"></i>
                    <span x-text="translations.inventory"></span>
                </button>
                <button @click="inventoryTab = 'purchases'; onInventoryTabChange()" 
                        class="px-4 py-2 rounded-t-lg font-medium transition"
                        :class="{'bg-primary text-white': inventoryTab === 'purchases', 'bg-gray-100 text-gray-700 hover:bg-gray-200': inventoryTab !== 'purchases'}">
                    <i class="fas fa-shopping-cart mr-2"></i>
                    <span x-text="translations.purchases"></span>
                </button>
                <button @click="inventoryTab = 'suppliers'; onInventoryTabChange()" 
                        class="px-4 py-2 rounded-t-lg font-medium transition"
                        :class="{'bg-primary text-white': inventoryTab === 'suppliers', 'bg-gray-100 text-gray-700 hover:bg-gray-200': inventoryTab !== 'suppliers'}">
                    <i class="fas fa-truck mr-2"></i>
                    <span x-text="translations.suppliers"></span>
                </button>
                <button @click="inventoryTab = 'waste'; onInventoryTabChange()" 
                        class="px-4 py-2 rounded-t-lg font-medium transition"
                        :class="{'bg-primary text-white': inventoryTab === 'waste', 'bg-gray-100 text-gray-700 hover:bg-gray-200': inventoryTab !== 'waste'}">
                    <i class="fas fa-trash mr-2"></i>
                    <span x-text="translations.waste"></span>
                </button>
                <button @click="inventoryTab = 'reports'; onInventoryTabChange()" 
                        class="px-4 py-2 rounded-t-lg font-medium transition"
                        :class="{'bg-primary text-white': inventoryTab === 'reports', 'bg-gray-100 text-gray-700 hover:bg-gray-200': inventoryTab !== 'reports'}">
                    <i class="fas fa-chart-line mr-2"></i>
                    <span x-text="translations.inventoryReports"></span>
                </button>
            </div>
            
            <!-- Inventory Alerts -->
            <div x-show="inventoryAlerts.length > 0" class="mb-6">
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <h3 class="text-lg font-bold text-red-800 mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Low Stock Alerts
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                        <template x-for="item in inventoryAlerts" :key="item.id">
                            <div class="bg-white border border-red-200 rounded p-2">
                                <div class="font-medium text-red-800" x-text="item.name"></div>
                                <div class="text-sm text-red-600">
                                    <span x-text="'Stock: ' + item.currentStock + ' ' + item.unit"></span>
                                    <span x-text="' | Min: ' + item.minStock + ' ' + item.unit"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            
            <!-- Inventory Tab Content -->
            <div x-show="inventoryTab === 'inventory'">
                <!-- Inventory Controls -->
                <div class="flex flex-wrap gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                    <div class="flex-1 min-w-64">
                        <input type="text" x-model="inventorySearchTerm" 
                               @keyup.enter="applySearch()"
                               @input.debounce.500ms="applySearch()"
                               placeholder="Search inventory..."
                               class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <select x-model="inventoryFilterCategory" 
                            @change="applyFilter()"
                            class="border rounded-lg px-3 py-2">
                        <option value="all">All Categories</option>
                        <template x-for="category in getInventoryCategories()" :key="category">
                            <option :value="category" x-text="category"></option>
                        </template>
                    </select>
                    <select x-model="inventorySortBy" 
                            @change="applySort()"
                            class="border rounded-lg px-3 py-2">
                        <option value="name">Sort by Name</option>
                        <option value="category">Sort by Category</option>
                        <option value="current_stock">Sort by Stock</option>
                        <option value="cost_per_unit">Sort by Cost</option>
                    </select>
                    <button @click="inventorySortOrder = inventorySortOrder === 'asc' ? 'desc' : 'asc'; applySort()"
                            class="border rounded-lg px-3 py-2 hover:bg-gray-100 transition">
                        <i class="fas" :class="inventorySortOrder === 'asc' ? 'fa-sort-up' : 'fa-sort-down'"></i>
                    </button>
                    <select x-model="inventoryPerPage" 
                            @change="changePerPage($event.target.value)"
                            class="border rounded-lg px-3 py-2">
                        <option value="10">10 per page</option>
                        <option value="15">15 per page</option>
                        <option value="25">25 per page</option>
                        <option value="50">50 per page</option>
                        <option value="100">100 per page</option>
                    </select>
                    <button @click="addInventory()"
                            class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
                        <i class="fas fa-plus mr-1"></i>
                        <span x-text="translations.addInventory"></span>
                    </button>
                </div>
                
                <!-- Pagination Info -->
                <div class="mb-4 text-sm text-gray-600" x-show="inventoryTotalItems > 0">
                    Showing <span x-text="((inventoryCurrentPage - 1) * inventoryPerPage + 1)"></span> to 
                    <span x-text="Math.min(inventoryCurrentPage * inventoryPerPage, inventoryTotalItems)"></span> of 
                    <span x-text="inventoryTotalItems"></span> items
                </div>
                
                <!-- Inventory Grid -->
                <div x-show="inventory.length === 0 && inventoryTotalItems === 0" class="text-center py-12 text-gray-500">
                    <i class="fas fa-boxes text-4xl mb-4 text-gray-300"></i>
                    <p class="text-lg">No inventory items found.</p>
                    <p class="text-sm mt-2">Click "Add Inventory" to create your first item.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" x-show="inventory.length > 0">
                    <template x-for="item in inventory" :key="item.id">
                        <div class="bg-gray-50 border rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1">
                                    <div class="font-bold text-lg" x-text="item.name"></div>
                                    <div class="text-sm text-gray-600" x-text="item.category"></div>
                                    <div class="text-sm" x-text="item.supplier"></div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-primary" x-text="formatPrice(item.cost) + '/' + item.unit"></div>
                                    <div class="text-sm" x-text="'Location: ' + item.location"></div>
                                </div>
                            </div>
                            
                            <!-- Stock Information -->
                            <div class="mt-3 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span>Current Stock:</span>
                                    <span class="font-bold" 
                                          :class="{
                                              'text-red-600': item.currentStock <= item.minStock,
                                              'text-green-600': item.currentStock > item.minStock
                                          }"
                                          x-text="item.currentStock + ' ' + item.unit"></span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Min Stock:</span>
                                    <span x-text="item.minStock + ' ' + item.unit"></span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Max Stock:</span>
                                    <span x-text="item.maxStock + ' ' + item.unit"></span>
                                </div>
                                
                                <!-- Stock Progress Bar -->
                                <div class="mt-2">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="h-2 rounded-full transition-all duration-300"
                                             :class="{
                                                 'bg-red-500': item.currentStock <= item.minStock,
                                                 'bg-yellow-500': item.currentStock > item.minStock && item.currentStock < item.maxStock * 0.5,
                                                 'bg-green-500': item.currentStock >= item.maxStock * 0.5
                                             }"
                                             :style="'width: ' + Math.min((item.currentStock / item.maxStock) * 100, 100) + '%'"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="mt-4 flex gap-2">
                                <button @click="editInventory(item)"
                                        class="flex-1 bg-blue-500 text-white px-2 py-1 rounded text-sm hover:bg-blue-600 transition">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button @click="updateStock(item.id, 1, 'add', 'Manual adjustment')"
                                        class="flex-1 bg-green-500 text-white px-2 py-1 rounded text-sm hover:bg-green-600 transition">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button @click="updateStock(item.id, 1, 'subtract', 'Manual adjustment')"
                                        class="flex-1 bg-yellow-500 text-white px-2 py-1 rounded text-sm hover:bg-yellow-600 transition">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button @click="deleteInventory(item.id)"
                                        class="flex-1 bg-red-500 text-white px-2 py-1 rounded text-sm hover:bg-red-600 transition">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                    
                </div>
                
                <!-- Pagination Controls -->
                <div class="mt-6 flex items-center justify-between" x-show="inventoryTotalPages > 1">
                    <div class="flex items-center gap-2">
                        <button @click="prevPage()" 
                                :disabled="inventoryCurrentPage === 1"
                                :class="inventoryCurrentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'"
                                class="border rounded-lg px-4 py-2 transition">
                            <i class="fas fa-chevron-left mr-1"></i> Previous
                        </button>
                        
                        <div class="flex items-center gap-1">
                            <template x-for="pageNum in (() => {
                                const total = inventoryTotalPages;
                                const current = inventoryCurrentPage;
                                const pages = [];
                                let start = Math.max(1, current - 2);
                                let end = Math.min(total, start + 4);
                                if (end - start < 4) {
                                    start = Math.max(1, end - 4);
                                }
                                for (let i = start; i <= end; i++) {
                                    pages.push(i);
                                }
                                return pages;
                            })()" :key="pageNum">
                                <button @click="goToPage(pageNum)"
                                        :class="pageNum === inventoryCurrentPage ? 'bg-primary text-white' : 'border hover:bg-gray-100'"
                                        class="rounded-lg px-3 py-2 min-w-[40px] transition"
                                        x-text="pageNum">
                                </button>
                            </template>
                        </div>
                        
                        <button @click="nextPage()" 
                                :disabled="inventoryCurrentPage === inventoryTotalPages"
                                :class="inventoryCurrentPage === inventoryTotalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-100'"
                                class="border rounded-lg px-4 py-2 transition">
                            Next <i class="fas fa-chevron-right ml-1"></i>
                        </button>
                    </div>
                    
                    <div class="text-sm text-gray-600">
                        Page <span x-text="inventoryCurrentPage"></span> of <span x-text="inventoryTotalPages"></span>
                    </div>
                </div>
            </div>
            
            <!-- Purchases Tab -->
            <div x-show="inventoryTab === 'purchases'">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">Purchase Orders</h3>
                    <button @click="addPurchase()"
                            class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
                        <i class="fas fa-plus mr-1"></i>
                        <span x-text="translations.addPurchase"></span>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <template x-for="purchase in purchases" :key="purchase.id">
                        <div class="bg-gray-50 border rounded-lg p-4">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <div class="font-bold" x-text="'Purchase #' + purchase.id"></div>
                                    <div class="text-sm text-gray-600" x-text="purchase.supplier"></div>
                                    <div class="text-sm" x-text="'Date: ' + purchase.purchaseDate"></div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-primary" x-text="formatPrice(purchase.totalCost)"></div>
                                    <div class="px-2 py-1 rounded-full text-xs" 
                                         :class="{
                                             'bg-yellow-100 text-yellow-800': purchase.status === 'pending',
                                             'bg-green-100 text-green-800': purchase.status === 'received'
                                         }"
                                         x-text="translations[purchase.status]"></div>
                                </div>
                            </div>
                            
                            <div class="border-t pt-3">
                                <template x-for="item in purchase.items" :key="item.inventoryId">
                                    <div class="flex justify-between text-sm py-1">
                                        <span x-text="item.name"></span>
                                        <span x-text="item.quantity + ' ' + item.unit + ' - ' + formatPrice(item.cost * item.quantity)"></span>
                                    </div>
                                </template>
                            </div>
                            
                            <div class="mt-3 flex gap-2">
                                <button @click="editPurchase(purchase)"
                                        class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </button>
                                <button x-show="purchase.status === 'pending'"
                                        @click="receivePurchase(purchase.id)"
                                        class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600 transition">
                                    <i class="fas fa-check mr-1"></i>Receive
                                </button>
                            </div>
                        </div>
                    </template>
                    
                    <template x-if="purchases.length === 0">
                        <div class="text-center py-12 text-gray-500">
                            <i class="fas fa-shopping-cart text-4xl mb-4"></i>
                            <p>No purchase orders found</p>
                        </div>
                    </template>
                </div>
            </div>
            
            <!-- Suppliers Tab -->
            <div x-show="inventoryTab === 'suppliers'">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">Suppliers</h3>
                    <button @click="addSupplier()"
                            class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
                        <i class="fas fa-plus mr-1"></i>
                        <span x-text="translations.addSupplier"></span>
                    </button>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <template x-for="supplier in suppliers" :key="supplier.id">
                        <div class="bg-gray-50 border rounded-lg p-4">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex-1">
                                    <div class="font-bold text-lg" x-text="supplier.name"></div>
                                    <div class="text-sm text-gray-600" x-text="supplier.contact"></div>
                                </div>
                                <div class="flex gap-1">
                                    <button @click="editSupplier(supplier)"
                                            class="text-blue-500 hover:text-blue-700">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button @click="deleteSupplier(supplier.id)"
                                            class="text-red-500 hover:text-red-700">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="space-y-1 text-sm text-gray-600">
                                <div x-text="supplier.phone"></div>
                                <div x-text="supplier.email"></div>
                                <div x-text="supplier.paymentTerms"></div>
                            </div>
                            
                            <div x-show="supplier.notes" class="mt-2 text-sm italic text-gray-500" x-text="supplier.notes"></div>
                        </div>
                    </template>
                    
                    <template x-if="suppliers.length === 0">
                        <div class="col-span-full text-center py-12 text-gray-500">
                            <i class="fas fa-truck text-4xl mb-4"></i>
                            <p>No suppliers found</p>
                        </div>
                    </template>
                </div>
            </div>
            
            <!-- Waste Tab -->
            <div x-show="inventoryTab === 'waste'">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">Waste Management</h3>
                    <button @click="addWaste()"
                            class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
                        <i class="fas fa-plus mr-1"></i>
                        <span x-text="translations.addWaste"></span>
                    </button>
                </div>
                
                <div class="space-y-4">
                    <template x-for="wasteItem in waste" :key="wasteItem.id">
                        <div class="bg-gray-50 border rounded-lg p-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="font-bold" x-text="wasteItem.item"></div>
                                    <div class="text-sm text-gray-600" x-text="wasteItem.reason"></div>
                                    <div class="text-sm" x-text="'Date: ' + wasteItem.date"></div>
                                </div>
                                <div class="text-right">
                                    <div class="font-bold text-red-600" x-text="wasteItem.quantity"></div>
                                </div>
                            </div>
                            
                            <div x-show="wasteItem.notes" class="mt-2 text-sm italic text-gray-500" x-text="wasteItem.notes"></div>
                        </div>
                    </template>
                    
                    <template x-if="waste.length === 0">
                        <div class="text-center py-12 text-gray-500">
                            <i class="fas fa-trash text-4xl mb-4"></i>
                            <p>No waste records found</p>
                        </div>
                    </template>
                </div>
            </div>
            
            <!-- Reports Tab -->
            <div x-show="inventoryTab === 'reports'">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-blue-600" x-text="generateInventoryReport().totalItems"></div>
                        <div class="text-sm text-blue-800">Total Items</div>
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-green-600" x-text="formatPrice(generateInventoryReport().totalValue)"></div>
                        <div class="text-sm text-green-800">Total Value</div>
                    </div>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-yellow-600" x-text="generateInventoryReport().lowStockItems"></div>
                        <div class="text-sm text-yellow-800">Low Stock Items</div>
                    </div>
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-red-600" x-text="generateInventoryReport().outOfStockItems"></div>
                        <div class="text-sm text-red-800">Out of Stock</div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-bold mb-4">Top Suppliers</h3>
                        <div class="space-y-2">
                            <template x-for="supplier in generateInventoryReport().topSuppliers" :key="supplier.name">
                                <div class="bg-white p-2 rounded border">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium" x-text="supplier.name"></span>
                                        <span class="font-bold text-primary" x-text="formatPrice(supplier.value)"></span>
                                    </div>
                                    <div class="text-sm text-gray-600" x-text="supplier.count + ' items'"></div>
                                </div>
                            </template>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-bold mb-4">Recent Transactions</h3>
                        <div class="space-y-2">
                            <template x-for="transaction in generateInventoryReport().recentTransactions" :key="transaction.id">
                                <div class="bg-white p-2 rounded border">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium" x-text="transaction.itemName"></span>
                                        <span class="text-sm" 
                                              :class="{
                                                  'text-green-600': transaction.change > 0,
                                                  'text-red-600': transaction.change < 0
                                              }"
                                              x-text="(transaction.change > 0 ? '+' : '') + transaction.change"></span>
                                    </div>
                                    <div class="text-xs text-gray-600" x-text="transaction.reason"></div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 flex flex-wrap justify-center gap-4">
                    <button @click="exportInventoryData()" 
                            class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-teal-700 transition flex items-center gap-2">
                        <i class="fas fa-download"></i>
                        <span x-text="translations.exportInventory"></span>
                    </button>
                    <input type="file"
                           x-ref="inventoryImportInput"
                           @change="importInventoryData($event.target.files[0])"
                           accept=".json"
                           class="hidden">
                    <button type="button"
                            @click="$refs.inventoryImportInput?.click()"
                            class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition flex items-center gap-2">
                        <i class="fas fa-upload"></i>
                        <span x-text="translations.importInventory"></span>
                    </button>
                </div>
            </div>
        </div>
    </main>

