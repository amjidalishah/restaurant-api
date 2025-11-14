<div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-6 text-primary flex items-center gap-2">
                <i class="fas fa-chart-bar"></i>
                <span x-text="translations.salesReport"></span>
            </h2>
            
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Daily Date</label>
                        <input type="date"
                               x-model="reportFilters.dailyDate"
                               @change="generateReports()"
                               class="w-full border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Monthly Period</label>
                        <div class="flex gap-2">
                            <select x-model.number="reportFilters.monthlyMonth"
                                    @change="generateReports()"
                                    class="flex-1 border rounded-lg px-3 py-2">
                                <template x-for="month in 12" :key="month">
                                    <option :value="month" x-text="month"></option>
                                </template>
                            </select>
                            <input type="number"
                                   min="2000"
                                   x-model.number="reportFilters.monthlyYear"
                                   @change="generateReports()"
                                   class="w-24 border rounded-lg px-3 py-2">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Breakdown Period</label>
                        <select x-model="reportFilters.topItemsPeriod"
                                @change="reportFilters.categoryPeriod = reportFilters.topItemsPeriod; reportFilters.orderTypePeriod = reportFilters.topItemsPeriod; generateReports()"
                                class="w-full border rounded-lg px-3 py-2">
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="all">All Time</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Hourly Date</label>
                        <input type="date"
                               x-model="reportFilters.hourlyDate"
                               @change="generateReports()"
                               class="w-full border rounded-lg px-3 py-2">
                    </div>
                </div>
            </div>

            <div x-show="reportLoading" class="mb-4 flex items-center gap-2 text-primary">
                <i class="fas fa-spinner fa-spin"></i>
                <span>Loading reports...</span>
            </div>

            <div x-show="reportsError" class="mb-4 text-red-600">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <span x-text="reportsError"></span>
            </div>

            <div x-show="reportsLastUpdated && !reportLoading" class="mb-4 text-xs text-gray-500">
                <span x-text="'Last updated ' + new Date(reportsLastUpdated).toLocaleString()"></span>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Daily Report -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-bold mb-4" x-text="translations.dailyReport"></h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span x-text="translations.totalSales"></span>
                            <span class="font-bold" x-text="formatPrice(reports.dailySales.totalSales || 0)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span x-text="translations.totalOrders"></span>
                            <span class="font-bold" x-text="reports.dailySales.totalOrders || 0"></span>
                        </div>
                        <div class="flex justify-between">
                            <span x-text="translations.completedOrders"></span>
                            <span class="font-bold" x-text="reports.dailySales.completedOrders || 0"></span>
                        </div>
                        <div class="flex justify-between">
                            <span x-text="translations.averageOrder"></span>
                            <span class="font-bold" x-text="formatPrice(reports.dailySales.averageOrder || 0)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Net Sales</span>
                            <span class="font-bold" x-text="formatPrice(reports.dailySales.netSales || 0)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Completion Rate</span>
                            <span class="font-bold" x-text="Math.round(reports.dailySales.completionRate || 0) + '%'"></span>
                        </div>
                    </div>
                </div>
                
                <!-- Monthly Report -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-bold mb-4" x-text="translations.monthlyReport"></h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span x-text="translations.totalSales"></span>
                            <span class="font-bold" x-text="formatPrice(reports.monthlySales.totalSales || 0)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span x-text="translations.totalOrders"></span>
                            <span class="font-bold" x-text="reports.monthlySales.totalOrders || 0"></span>
                        </div>
                        <div class="flex justify-between">
                            <span x-text="translations.completedOrders"></span>
                            <span class="font-bold" x-text="reports.monthlySales.completedOrders || 0"></span>
                        </div>
                        <div class="flex justify-between">
                            <span x-text="translations.averageOrder"></span>
                            <span class="font-bold" x-text="formatPrice(reports.monthlySales.averageOrder || 0)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Net Sales</span>
                            <span class="font-bold" x-text="formatPrice(reports.monthlySales.netSales || 0)"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Avg Daily Sales</span>
                            <span class="font-bold" x-text="formatPrice(reports.monthlySales.averageDailySales || 0)"></span>
                        </div>
                    </div>
                </div>
                
                <!-- Top Items -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-bold mb-4" x-text="translations.topItems"></h3>
                    <div class="space-y-2">
                        <template x-for="(item, index) in reports.topItems" :key="index">
                            <div class="bg-white p-2 rounded border">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-medium" x-text="(index + 1) + '. ' + item.name"></span>
                                    <span class="font-bold text-primary" x-text="item.quantity + ' sold'"></span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span x-text="'Revenue: ' + formatPrice(item.revenue)"></span>
                                    <span x-text="'Avg: ' + formatPrice(item.averagePrice)"></span>
                                </div>
                            </div>
                        </template>
                        <template x-if="reports.topItems.length === 0">
                            <div class="text-center text-gray-500 py-4" x-text="translations.noNewOrders"></div>
                        </template>
                    </div>
                </div>
                
                <!-- Category Report -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-bold mb-4" x-text="translations.categoryReport"></h3>
                    <div class="space-y-2">
                        <template x-for="(category, index) in reports.categorySales" :key="index">
                            <div class="bg-white p-2 rounded border">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-medium" x-text="category.category"></span>
                                    <span class="font-bold text-primary" x-text="formatPrice(category.sales)"></span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span x-text="category.quantity + ' items'"></span>
                                    <span x-text="'Avg: ' + formatPrice(category.averageOrderValue)"></span>
                                </div>
                            </div>
                        </template>
                        <template x-if="reports.categorySales.length === 0">
                            <div class="text-center text-gray-500 py-4" x-text="translations.noNewOrders"></div>
                        </template>
                    </div>
                </div>
                
                <!-- Order Type Report -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-bold mb-4">Order Type Analysis</h3>
                    <div class="space-y-2">
                        <template x-for="(type, index) in reports.orderTypeSales" :key="index">
                            <div class="bg-white p-2 rounded border">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-medium capitalize" x-text="type.type"></span>
                                    <span class="font-bold text-primary" x-text="formatPrice(type.sales)"></span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span x-text="type.orders + ' orders'"></span>
                                    <span x-text="'Avg: ' + formatPrice(type.averageOrder)"></span>
                                </div>
                            </div>
                        </template>
                        <template x-if="!reports.orderTypeSales || reports.orderTypeSales.length === 0">
                            <div class="text-center text-gray-500 py-4">No order type data available</div>
                        </template>
                    </div>
                </div>
                
                <!-- Hourly Report -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-bold mb-4">Peak Hours Analysis</h3>
                    <div class="space-y-2">
                        <template x-for="(hour, index) in reports.hourlySales" :key="index">
                            <div class="bg-white p-2 rounded border">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="font-medium" x-text="hour.hour + ':00'"></span>
                                    <span class="font-bold text-primary" x-text="formatPrice(hour.sales)"></span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span x-text="hour.orders + ' orders'"></span>
                                    <span x-text="'Avg: ' + formatPrice(hour.averageOrder)"></span>
                                </div>
                            </div>
                        </template>
                        <template x-if="!reports.hourlySales || reports.hourlySales.length === 0">
                            <div class="text-center text-gray-500 py-4">No hourly data available</div>
                        </template>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 flex flex-wrap justify-center gap-4">
                <div class="flex items-center gap-2">
                    <select x-model="reportFilters.exportType" class="border rounded-lg px-3 py-2">
                        <option value="orders">Orders</option>
                        <option value="recipes">Recipes</option>
                        <option value="tables">Tables</option>
                        <option value="chefs">Chefs</option>
                        <option value="stations">Stations</option>
                    </select>
                    <select x-model="reportFilters.exportPeriod" class="border rounded-lg px-3 py-2">
                        <option value="all">All Time</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                    </select>
                </div>
                <button @click="exportData()" 
                        class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-teal-700 transition">
                    <i class="fas fa-download mr-2"></i>
                    <span x-text="translations.exportData"></span>
                </button>
                <button @click="generateReports()" 
                        class="bg-green-500 text-white px-6 py-3 rounded-lg hover:bg-green-600 transition">
                    <i class="fas fa-sync mr-2"></i>
                    Refresh Reports
                </button>
            </div>
        </div>

