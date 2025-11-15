<div class="bg-white rounded-lg shadow-lg p-6">
    <h2 class="text-2xl font-bold mb-6 text-primary flex items-center gap-2">
        <i class="fas fa-cash-register"></i>
        <span x-text="translations.pos"></span>
    </h2>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Menu Items Section -->
        <div class="lg:col-span-3">
            <!-- Advanced Search and Filters -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                    <!-- Search Bar -->
                    <div class="md:col-span-2">
                        <div class="relative">
                            <input type="text" x-model="posSearchTerm" placeholder="Search items by name, category, or tags..."
                                   class="w-full border rounded-lg pl-10 pr-4 py-2 focus:ring-2 focus:ring-primary focus:border-transparent">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <select x-model="posFilterCategory" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="all">All Categories</option>
                            <template x-for="category in recipeCategories" :key="category">
                                <option :value="category" x-text="category"></option>
                            </template>
                        </select>
                    </div>

                    <!-- Sort Options -->
                    <div>
                        <select x-model="posSortBy" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="name">Sort by Name</option>
                            <option value="price">Sort by Price</option>
                            <option value="popularity">Sort by Popularity</option>
                            <option value="category">Sort by Category</option>
                        </select>
                    </div>
                </div>

                <!-- Quick Filters -->
                <div class="flex flex-wrap gap-2">
                    <button @click="posQuickFilter = 'all'"
                            class="px-3 py-1 rounded-full text-sm border transition"
                            :class="{'bg-primary text-white border-primary': posQuickFilter === 'all', 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50': posQuickFilter !== 'all'}">
                        All Items
                    </button>
                    <button @click="posQuickFilter = 'popular'"
                            class="px-3 py-1 rounded-full text-sm border transition"
                            :class="{'bg-primary text-white border-primary': posQuickFilter === 'popular', 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50': posQuickFilter !== 'popular'}">
                        <i class="fas fa-fire mr-1"></i>Popular
                    </button>
                    <button @click="posQuickFilter = 'vegetarian'"
                            class="px-3 py-1 rounded-full text-sm border transition"
                            :class="{'bg-primary text-white border-primary': posQuickFilter === 'vegetarian', 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50': posQuickFilter !== 'vegetarian'}">
                        <i class="fas fa-leaf mr-1"></i>Vegetarian
                    </button>
                    <button @click="posQuickFilter = 'spicy'"
                            class="px-3 py-1 rounded-full text-sm border transition"
                            :class="{'bg-primary text-white border-primary': posQuickFilter === 'spicy', 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50': posQuickFilter !== 'spicy'}">
                        <i class="fas fa-pepper-hot mr-1"></i>Spicy
                    </button>
                    <button @click="posQuickFilter = 'healthy'"
                            class="px-3 py-1 rounded-full text-sm border transition"
                            :class="{'bg-primary text-white border-primary': posQuickFilter === 'healthy', 'bg-white text-gray-600 border-gray-300 hover:bg-gray-50': posQuickFilter !== 'healthy'}">
                        <i class="fas fa-heart mr-1"></i>Healthy
                    </button>
                </div>
            </div>

            <!-- Category Tabs -->
            <div class="mb-6">
                <div class="flex flex-wrap gap-2 border-b">
                    <button @click="posActiveCategory = 'all'"
                            class="px-4 py-2 rounded-t-lg font-medium transition"
                            :class="{'bg-primary text-white': posActiveCategory === 'all', 'bg-gray-100 text-gray-700 hover:bg-gray-200': posActiveCategory !== 'all'}">
                        All
                    </button>
                    <template x-for="category in recipeCategories" :key="category">
                        <button @click="posActiveCategory = category"
                                class="px-4 py-2 rounded-t-lg font-medium transition"
                                :class="{'bg-primary text-white': posActiveCategory === category, 'bg-gray-100 text-gray-700 hover:bg-gray-200': posActiveCategory !== category}">
                            <span x-text="category"></span>
                            <span class="ml-2 bg-white bg-opacity-20 px-2 py-0.5 rounded-full text-xs"
                                  x-text="getFilteredRecipes().filter(r => r.category === category).length"></span>
                        </button>
                    </template>
                </div>
            </div>

            <!-- Menu Items Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-6">
                <template x-for="recipe in getFilteredRecipes()" :key="recipe.id">
                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-all duration-200 cursor-pointer group relative overflow-hidden"
                         @click="addToOrder(recipe)">
                        <!-- Recipe Image Placeholder -->
                        <div class="w-full h-32 bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg mb-3 flex items-center justify-center">
                            <i class="fas fa-utensils text-3xl text-gray-400 group-hover:text-primary transition-colors"></i>
                        </div>

                        <!-- Recipe Info -->
                        <div class="space-y-2">
                            <div class="font-bold text-sm leading-tight" x-text="recipe.name"></div>
                            <div class="text-primary font-bold text-lg" x-text="formatPrice(recipe.price)"></div>
                            <div class="text-xs text-gray-500" x-text="recipe.category"></div>

                            <!-- Tags -->
                            <div x-show="recipe.tags && recipe.tags.length > 0" class="flex flex-wrap gap-1">
                                <template x-for="tag in (recipe.tags || []).slice(0, 2)" :key="tag">
                                    <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded" x-text="tag"></span>
                                </template>
                                <span x-show="(recipe.tags || []).length > 2" class="text-xs text-gray-400">+<span x-text="(recipe.tags || []).length - 2"></span></span>
                            </div>

                            <!-- Quick Info -->
                            <div class="flex justify-between text-xs text-gray-500">
                                <span x-text="recipe.prepTime + 'm prep'"></span>
                                <span x-text="recipe.difficulty"></span>
                            </div>
                        </div>

                        <!-- Add Button Overlay -->
                        <div class="absolute inset-0 bg-primary bg-opacity-90 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none">
                            <div class="text-white text-center">
                                <i class="fas fa-plus text-2xl mb-2"></i>
                                <div class="font-bold text-sm">Add to Order</div>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Empty State -->
                <template x-if="getFilteredRecipes().length === 0">
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-500 mb-2">No items found</h3>
                        <p class="text-gray-400">Try adjusting your search or filters</p>
                    </div>
                </template>
            </div>

            <!-- Order Type and Table Selection -->
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <h3 class="font-bold mb-3" x-text="translations.orderType"></h3>
                <div class="flex gap-4 mb-4">
                    <button @click="orderType = 'dine-in'; currentOrder.tableNumber = null"
                            class="px-4 py-2 rounded-lg border transition-all duration-200"
                            :class="{'bg-primary text-white border-primary shadow-md': orderType === 'dine-in', 'bg-white border-gray-300 hover:bg-gray-50': orderType !== 'dine-in'}">
                        <i class="fas fa-utensils mr-2"></i>
                        <span x-text="translations.dineIn"></span>
                    </button>
                    <button @click="orderType = 'takeaway'; currentOrder.tableNumber = null"
                            class="px-4 py-2 rounded-lg border transition-all duration-200"
                            :class="{'bg-primary text-white border-primary shadow-md': orderType === 'takeaway', 'bg-white border-gray-300 hover:bg-gray-50': orderType !== 'takeaway'}">
                        <i class="fas fa-box mr-2"></i>
                        <span x-text="translations.takeaway"></span>
                    </button>
                    <!-- <button @click="orderType = 'delivery'; currentOrder.tableNumber = null"
                            class="px-4 py-2 rounded-lg border transition-all duration-200"
                            :class="{'bg-primary text-white border-primary shadow-md': orderType === 'delivery', 'bg-white border-gray-300 hover:bg-gray-50': orderType !== 'delivery'}">
                        <i class="fas fa-truck mr-2"></i>
                        <span x-text="translations.delivery"></span>
                    </button> -->
                </div>

                <!-- Table Selection for Dine-in -->
                <div x-show="orderType === 'dine-in'" class="mt-4">
                    <h4 class="font-bold mb-2" x-text="translations.selectTable"></h4>
                    <div class="grid grid-cols-4 md:grid-cols-6 gap-2">
                        <template x-for="table in tables" :key="table.id">
                            <button @click="selectTable(table.number)"
                                    class="p-3 rounded-lg border text-center transition-all duration-200 relative"
                                    :class="{
                                        'bg-primary text-white border-primary shadow-md': currentOrder.tableNumber === table.number,
                                        'bg-white border-gray-300 hover:bg-gray-50': currentOrder.tableNumber !== table.number,
                                        'opacity-50 cursor-not-allowed': table.status === 'occupied'
                                    }"
                                    :disabled="table.status === 'occupied'">
                                <div class="font-bold" x-text="'Table ' + table.number"></div>
                                <div class="text-xs" x-text="table.capacity + ' seats'"></div>
                                <div class="text-xs mt-1 px-2 py-1 rounded-full"
                                     :class="getTableStatusColor(table.status)"
                                     x-text="translations[table.status]"></div>

                                <!-- Status Indicator -->
                                <div class="absolute top-1 right-1 w-2 h-2 rounded-full"
                                     :class="{
                                         'bg-green-500': table.status === 'available',
                                         'bg-yellow-500': table.status === 'reserved',
                                         'bg-red-500': table.status === 'occupied',
                                         'bg-blue-500': table.status === 'cleaning',
                                         'bg-purple-500': table.status === 'maintenance'
                                     }"></div>
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Order Panel -->
        <div class="lg:col-span-1">
            <div class="bg-gray-50 rounded-lg p-6 sticky top-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-lg" x-text="translations.currentOrder"></h3>
                    <button @click="clearCurrentOrder()"
                            class="text-red-500 hover:text-red-700 transition"
                            x-show="currentOrder.items.length > 0">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>

                <!-- Order Items -->
                <div class="mb-4 max-h-96 overflow-y-auto">
                    <div class="flex justify-between font-bold mb-2 text-sm text-gray-600">
                        <span x-text="translations.item"></span>
                        <span x-text="translations.total"></span>
                    </div>

                    <div class="space-y-2">
                        <template x-for="(item, index) in currentOrder.items" :key="index">
                            <div class="bg-white rounded-lg p-3 shadow-sm">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex-1">
                                        <div class="font-medium text-sm" x-text="item.name"></div>
                                        <div class="text-xs text-gray-500" x-text="formatPrice(item.price) + ' each'"></div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-bold text-sm" x-text="formatPrice(item.price * item.quantity)"></div>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <button @click="updateItemQuantity(index, item.quantity - 1)"
                                                class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300 transition"
                                                :disabled="item.quantity <= 1">
                                            <i class="fas fa-minus text-xs"></i>
                                        </button>
                                        <span class="font-bold text-sm w-8 text-center" x-text="item.quantity"></span>
                                        <button @click="updateItemQuantity(index, item.quantity + 1)"
                                                class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center hover:bg-gray-300 transition">
                                            <i class="fas fa-plus text-xs"></i>
                                        </button>
                                    </div>
                                    <button @click="removeItem(index)" class="text-red-500 hover:text-red-700 transition">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <template x-if="currentOrder.items.length === 0">
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-shopping-cart text-3xl mb-2"></i>
                                <p class="text-sm" x-text="translations.emptyOrder"></p>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="border-t pt-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span x-text="translations.subtotal"></span>
                        <span x-text="formatPrice(currentOrder.subtotal)"></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span x-text="translations.tax + ' (' + ((settings.taxRate ?? settings.tax_rate ?? 0)) + '%)'"></span>
                        <span x-text="formatPrice(currentOrder.tax)"></span>
                    </div>
                    <!-- <div x-show="orderType === 'delivery'" class="flex justify-between text-sm">
                        <span x-text="translations.deliveryFee"></span>
                        <span x-text="formatPrice(currentOrder.deliveryFee)"></span>
                    </div> -->
                    <div class="flex justify-between font-bold text-lg pt-2 border-t">
                        <span x-text="translations.total"></span>
                        <span x-text="formatPrice(currentOrder.total)"></span>
                    </div>
                </div>

                <!-- Order Info -->
                <div class="mt-4 p-3 bg-white rounded-lg border">
                    <div class="text-sm space-y-1">
                        <div class="flex justify-between">
                            <span class="text-gray-600" x-text="translations.orderType"></span>
                            <span class="font-medium" x-text="translations[orderType]"></span>
                        </div>
                        <div x-show="orderType === 'dine-in' && currentOrder.tableNumber" class="flex justify-between">
                            <span class="text-gray-600" x-text="translations.tableNumber"></span>
                            <span class="font-medium" x-text="'Table ' + currentOrder.tableNumber"></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Items</span>
                            <span class="font-medium" x-text="currentOrder.items.length"></span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 space-y-3">
                    <button @click="placeOrder()"
                            :disabled="currentOrder.items.length === 0"
                            class="w-full bg-primary text-white py-3 rounded-lg font-bold hover:bg-teal-700 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg"
                            x-text="translations.placeOrder">
                    </button>

                    <div class="grid grid-cols-2 gap-2">
                        <button @click="generateReceipt(currentOrder)"
                                :disabled="currentOrder.items.length === 0"
                                class="bg-blue-500 text-white py-2 rounded-lg text-sm hover:bg-blue-600 transition disabled:opacity-50">
                            <i class="fas fa-print mr-1"></i>Preview
                        </button>
                        <button @click="saveOrderAsDraft()"
                                :disabled="currentOrder.items.length === 0"
                                class="bg-gray-500 text-white py-2 rounded-lg text-sm hover:bg-gray-600 transition disabled:opacity-50">
                            <i class="fas fa-save mr-1"></i>Save Draft
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
