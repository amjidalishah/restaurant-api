<div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-6 text-primary flex items-center gap-2">
                <i class="fas fa-chair"></i>
                <span x-text="translations.tableManagement"></span>
            </h2>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Table List -->
                <div class="lg:col-span-2">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold" x-text="translations.tables"></h3>
                        <button @click="addTable()"
                                class="bg-primary text-white px-4 py-2 rounded-lg text-sm hover:bg-teal-700 transition">
                            <i class="fas fa-plus mr-1"></i>
                            <span x-text="translations.addTable"></span>
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                        <template x-for="table in tables" :key="table.id">
                            <div class="bg-gray-50 border rounded-lg p-4 hover:shadow-md transition">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <div class="font-bold text-lg" x-text="'Table ' + table.number"></div>
                                        <div class="text-sm text-gray-600" x-text="table.capacity + ' seats'"></div>
                                        <div x-show="table.location" class="text-xs text-gray-500" x-text="table.location"></div>
                                        <div x-show="table.notes" class="text-xs text-gray-500 italic" x-text="table.notes"></div>
                                    </div>
                                    <div class="px-2 py-1 rounded-full text-xs" 
                                         :class="getTableStatusColor(table.status)"
                                         x-text="translations[table.status]"></div>
                                </div>
                                
                                <!-- Table Stats -->
                                <div class="mt-2 space-y-1 text-xs text-gray-600">
                                    <div class="flex justify-between">
                                        <span x-text="translations.revenue"></span>
                                        <span x-text="formatPrice(getTableRevenue(table.number))"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span x-text="translations.averageOrderValue"></span>
                                        <span x-text="formatPrice(getTableAverageOrder(table.number))"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span x-text="translations.occupancyTime"></span>
                                        <span x-text="getTableOccupancyTime(table.number) + ' min'"></span>
                                    </div>
                                </div>
                                
                                <div class="mt-3 space-y-2">
                                    <div class="flex gap-1">
                                        <button @click="editTable(table)"
                                                class="flex-1 bg-blue-500 text-white px-2 py-1 rounded text-xs hover:bg-blue-600 transition">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button @click="selectedTable = table.number"
                                                class="flex-1 bg-green-500 text-white px-2 py-1 rounded text-xs hover:bg-green-600 transition">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button @click="deleteTable(table.id)"
                                                class="flex-1 bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600 transition">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <div class="flex gap-1">
                                        <button @click="openReservationForm(table)"
                                                class="flex-1 bg-yellow-500 text-white px-2 py-1 rounded text-xs hover:bg-yellow-600 transition">
                                            <i class="fas fa-calendar mr-1"></i>
                                            <span x-text="translations.makeReservation"></span>
                                        </button>
                                        <button x-show="table.status === 'reserved'" 
                                                @click="cancelReservation(table.id)"
                                                class="flex-1 bg-gray-500 text-white px-2 py-1 rounded text-xs hover:bg-gray-600 transition">
                                            <i class="fas fa-times mr-1"></i>
                                            <span x-text="translations.cancelReservation"></span>
                                        </button>
                                        <button x-show="table.status === 'occupied'" 
                                                @click="closeTable(table.number)"
                                                class="flex-1 bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600 transition">
                                            <i class="fas fa-times mr-1"></i>
                                            <span x-text="translations.closeTable"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                
                <!-- Table Orders -->
                <div>
                    <div x-show="selectedTable" class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-bold mb-4" x-text="'Table ' + selectedTable + ' - ' + translations.tableOrders"></h3>
                        
                        <div class="space-y-3">
                            <template x-for="order in getTableOrders(selectedTable)" :key="order.id">
                                <div class="bg-white border rounded-lg p-3">
                                    <div class="flex justify-between items-start mb-2">
                                        <div>
                                            <div class="font-bold" x-text="'Order #' + order.id"></div>
                                            <div class="text-sm text-gray-600" x-text="formatDateTime(order.timestamp)"></div>
                                        </div>
                                        <div class="px-2 py-1 rounded-full text-xs" 
                                             :class="{
                                                 'bg-blue-100 text-blue-800': order.status === 'new',
                                                 'bg-orange-100 text-orange-800': order.status === 'preparing',
                                                 'bg-green-100 text-green-800': order.status === 'ready'
                                             }"
                                             x-text="translations[order.status]"></div>
                                    </div>
                                    
                                    <div class="border-t pt-2">
                                        <template x-for="item in order.items" :key="item.id">
                                            <div class="flex justify-between text-sm py-1">
                                                <div>
                                                    <span x-text="item.quantity + '×'"></span>
                                                    <span x-text="item.name"></span>
                                                </div>
                                                <div x-text="formatPrice(item.price * item.quantity)"></div>
                                            </div>
                                        </template>
                                    </div>
                                    
                                    <div class="mt-3 pt-2 border-t">
                                        <div class="flex justify-between text-sm">
                                            <span x-text="translations.total"></span>
                                            <span class="font-bold" x-text="formatPrice(order.total)"></span>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            
                            <template x-if="getTableOrders(selectedTable).length === 0">
                                <div class="text-center text-gray-500 py-8">
                                    <i class="fas fa-utensils text-2xl mb-2"></i>
                                    <p x-text="translations.noNewOrders"></p>
                                </div>
                            </template>
                        </div>
                    </div>
                    
                    <div x-show="!selectedTable" class="text-center py-12 text-gray-500">
                        <i class="fas fa-chair text-4xl mb-4"></i>
                        <p x-text="translations.selectTable"></p>
                    </div>
                </div>
            </div>
        </div>

