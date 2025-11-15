<div class="bg-white rounded-lg shadow-lg p-6">
            <!-- KDS Header with Controls -->
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-primary flex items-center gap-2">
                <i class="fas fa-tv"></i>
                <span x-text="translations.kds"></span>
            </h2>
                
                <div class="flex gap-2">
                    <!-- KDS Controls -->
                    <div class="flex gap-2">
                        <select x-model="kdsView" class="border rounded-lg px-3 py-1 text-sm">
                            <option value="all" x-text="translations.kdsAllOrders"></option>
                            <option value="new" x-text="translations.newOrders"></option>
                            <option value="preparing" x-text="translations.preparing"></option>
                            <option value="ready" x-text="translations.ready"></option>
                        </select>
                        
                        <select x-model="kdsFilter" class="border rounded-lg px-3 py-1 text-sm">
                            <option value="all" x-text="translations.kdsAllTypes"></option>
                            <option value="dine-in" x-text="translations.dineIn"></option>
                            <option value="takeaway" x-text="translations.takeaway"></option>
                            <option value="delivery" x-text="translations.delivery"></option>
                        </select>
                        
                        <select x-model="kdsSort" class="border rounded-lg px-3 py-1 text-sm">
                            <option value="time" x-text="translations.kdsSortTime"></option>
                            <option value="priority" x-text="translations.kdsSortPriority"></option>
                            <option value="table" x-text="translations.kdsSortTable"></option>
                        </select>
                        <!-- Station Filter -->
                        <select x-model="kdsStationFilter" class="border rounded-lg px-3 py-1 text-sm">
                            <option value="all" x-text="translations.kdsAllStations"></option>
                            <template x-for="station in stations" :key="station.name">
                                <option :value="station.name" x-text="station.name"></option>
                            </template>
                        </select>
                    </div>
                    
                    <button @click="showKdsSettings = true" 
                            class="bg-gray-500 text-white px-3 py-1 rounded-lg text-sm hover:bg-gray-600 transition">
                        <i class="fas fa-cog"></i>
                    </button>
                    <!-- <button @click="showChefStationManager = true"
                            class="bg-primary text-white px-3 py-1 rounded-lg text-sm hover:bg-teal-700 transition">
                        <i class="fas fa-users-cog"></i>
                        <span class="hidden md:inline" x-text="translations.kdsManageChefs"></span>
                    </button> -->
                </div>
            </div>
            
            <!-- Kitchen Statistics -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-center">
                    <div class="text-2xl font-bold text-blue-600" x-text="getKitchenStats().totalOrders"></div>
                    <div class="text-sm text-blue-800" x-text="translations.kdsStatsTotal"></div>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-lg p-3 text-center">
                    <div class="text-2xl font-bold text-green-600" x-text="getKitchenStats().completedOrders"></div>
                    <div class="text-sm text-green-800" x-text="translations.kdsStatsCompleted"></div>
                </div>
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-3 text-center">
                    <div class="text-2xl font-bold text-orange-600" x-text="getKitchenStats().pendingOrders"></div>
                    <div class="text-sm text-orange-800" x-text="translations.kdsStatsPending"></div>
                </div>
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-3 text-center">
                    <div class="text-2xl font-bold text-purple-600" x-text="getKitchenStats().avgPrepTime + 'm'"></div>
                    <div class="text-sm text-purple-800" x-text="translations.kdsStatsAvgPrep"></div>
                </div>
                <div class="bg-teal-50 border border-teal-200 rounded-lg p-3 text-center">
                    <div class="text-2xl font-bold text-teal-600" x-text="getKitchenStats().efficiency + '%'"></div>
                    <div class="text-sm text-teal-800" x-text="translations.kdsStatsEfficiency"></div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- New Orders -->
                <div>
                    <h3 class="text-lg font-bold mb-4 text-center bg-blue-100 py-2 rounded">
                        <i class="fas fa-bell mr-2"></i>
                        <span x-text="translations.newOrders"></span>
                        <span class="ml-2 bg-blue-500 text-white px-2 py-1 rounded-full text-xs" 
                              x-text="getFilteredOrders().filter(o => o.status === 'new').length"></span>
                    </h3>
                    
                    <div class="space-y-4 max-h-96 overflow-y-auto">
                        <template x-for="order in getFilteredOrders()" :key="order.id">
                            <template x-if="order.status === 'new'">
                                <div class="bg-white border rounded-lg p-4 shadow-lg" 
                                     :class="getOrderStatusColor(order)">
                                    <!-- Order Header -->
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <div class="font-bold text-lg" x-text="'#' + order.id"></div>
                                                <div x-show="order.urgent" class="bg-red-500 text-white px-2 py-1 rounded-full text-xs animate-pulse">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    <span x-text="translations.kdsUrgent"></span>
                                                </div>
                                            </div>
                                            <div class="text-sm" x-text="formatDateTime(order.timestamp)"></div>
                                            <div x-show="order.tableNumber" class="text-xs" x-text="(translations.kdsTableLabel || translations.tableNumber || 'Table') + ' ' + order.tableNumber"></div>
                                            <div class="text-xs font-bold" x-text="(translations.kdsAgeLabel || 'Age') + ': ' + getOrderAgeText(order)"></div>
                                        </div>
                                        <div class="text-right">
                                            <div class="px-2 py-1 rounded-full text-xs mb-1" 
                                                 :class="{
                                                     'bg-blue-100 text-blue-800': order.type === 'dine-in',
                                                     'bg-green-100 text-green-800': order.type === 'takeaway',
                                                     'bg-red-100 text-red-800': order.type === 'delivery'
                                                 }"
                                                 x-text="getOrderTypeLabel(order.type)"></div>
                                            <div class="text-xs" x-text="(translations.kdsEstimateLabel || 'Est') + ': ' + getEstimatedPrepTime(order) + 'm'"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Order Items -->
                                    <div class="border-t pt-3 mb-3">
                                        <template x-for="item in order.items" :key="item.id">
                                            <div class="flex justify-between text-sm py-1">
                                                <div class="flex items-center">
                                                    <span class="font-bold mr-2" x-text="item.quantity + '×'"></span>
                                                    <span x-text="item.name"></span>
                                                </div>
                                                <div class="text-xs text-gray-600" x-text="formatPrice(item.price * item.quantity)"></div>
                                            </div>
                                        </template>
                                    </div>
                                    
                                    <!-- Order Actions -->
                                    <div class="flex justify-between items-center">
                                        <div class="text-xs">
                                            <span x-text="(translations.kdsPriorityLabel || 'Priority') + ': ' + getOrderPriority(order)"></span>
                                        </div>
                                        <div class="flex gap-2">
                                            <button @click="generateReceipt(order)"
                                                    class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600 transition">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        <button @click="updateOrderStatus(order.id, 'preparing')"
                                                    class="bg-green-500 text-white px-3 py-1 rounded text-xs hover:bg-green-600 transition">
                                            <i class="fas fa-play mr-1"></i>
                                            <span x-text="translations.startPreparing"></span>
                                        </button>
                                    </div>
                                    </div>
                                    
                                    <!-- Assigned Chef/Station -->
                                    <!-- <div class="flex items-center gap-2 mt-2">
                                        <template x-if="order.assignedChef">
                                            <div class="bg-gray-100 px-2 py-1 rounded text-xs flex items-center gap-1">
                                                <i class="fas fa-user-tie text-primary"></i>
                                                <span x-text="translations.kdsAssignedTo + ': ' + order.assignedChef"></span>
                                                <span x-show="order.assignedStation">| <span x-text="order.assignedStation"></span></span>
                                                <button @click="unassignOrderChefStation(order.id)" class="ml-2 text-red-500 hover:text-red-700" title="Unassign">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </template>
                                        <template x-if="!order.assignedChef">
                                            <button @click="showChefAssignModal = true; chefAssignOrderId = order.id; chefAssignName = ''; chefAssignStation = '';" 
                                                    class="bg-primary text-white px-2 py-1 rounded text-xs hover:bg-teal-700 transition flex items-center gap-1">
                                                <i class="fas fa-user-plus"></i>
                                                <span x-text="translations.kdsAssignChef"></span>
                                            </button>
                                        </template>
                                    </div> -->
                                </div>
                            </template>
                        </template>
                        
                        <template x-if="getFilteredOrders().filter(o => o.status === 'new').length === 0">
                            <div class="text-center text-gray-500 py-8" x-text="translations.noNewOrders"></div>
                        </template>
                    </div>
                </div>
                
                <!-- Preparing Orders -->
                <div>
                    <h3 class="text-lg font-bold mb-4 text-center bg-orange-100 py-2 rounded">
                        <i class="fas fa-clock mr-2"></i>
                        <span x-text="translations.preparing"></span>
                        <span class="ml-2 bg-orange-500 text-white px-2 py-1 rounded-full text-xs" 
                              x-text="getFilteredOrders().filter(o => o.status === 'preparing').length"></span>
                    </h3>
                    
                    <div class="space-y-4 max-h-96 overflow-y-auto">
                        <template x-for="order in getFilteredOrders()" :key="order.id">
                            <template x-if="order.status === 'preparing'">
                                <div class="bg-white border rounded-lg p-4 shadow-lg" 
                                     :class="getOrderStatusColor(order)">
                                    <!-- Order Header -->
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <div class="font-bold text-lg" x-text="'#' + order.id"></div>
                                                <div x-show="order.urgent" class="bg-red-500 text-white px-2 py-1 rounded-full text-xs animate-pulse">
                                                    <i class="fas fa-exclamation-triangle mr-1"></i>
                                                    <span x-text="translations.kdsUrgent"></span>
                                                </div>
                                            </div>
                                            <div class="text-sm" x-text="formatDateTime(order.timestamp)"></div>
                                            <div x-show="order.tableNumber" class="text-xs" x-text="(translations.kdsTableLabel || translations.tableNumber || 'Table') + ' ' + order.tableNumber"></div>
                                            <div class="text-xs font-bold" x-text="(translations.kdsAgeLabel || 'Age') + ': ' + getOrderAgeText(order)"></div>
                                            <div class="text-xs" x-text="(translations.kdsStatusLabel || 'Status') + ': ' + translations[getPrepTimeStatus(order)]"></div>
                                            <!-- Progress Bar for Preparing Orders -->
                                            <div x-show="order.status === 'preparing'" class="mt-2">
                                                <div class="w-full bg-gray-200 rounded-full h-2">
                                                    <div class="bg-green-600 h-2 rounded-full transition-all duration-300" 
                                                         :style="'width: ' + getOrderPrepProgress(order.id) + '%'"></div>
                                        </div>
                                                <div class="text-xs text-gray-600 mt-1" x-text="(translations.kdsProgressLabel || 'Progress') + ': ' + Math.round(getOrderPrepProgress(order.id)) + '%'"></div>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="px-2 py-1 rounded-full text-xs mb-1" 
                                                 :class="{
                                                     'bg-blue-100 text-blue-800': order.type === 'dine-in',
                                                     'bg-green-100 text-green-800': order.type === 'takeaway',
                                                     'bg-red-100 text-red-800': order.type === 'delivery'
                                                 }"
                                                 x-text="getOrderTypeLabel(order.type)"></div>
                                            <div class="text-xs" x-text="(translations.kdsEstimateLabel || 'Est') + ': ' + getEstimatedPrepTime(order) + 'm'"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Order Items -->
                                    <div class="border-t pt-3 mb-3">
                                        <template x-for="item in order.items" :key="item.id">
                                            <div class="flex justify-between text-sm py-1">
                                                <div class="flex items-center">
                                                    <span class="font-bold mr-2" x-text="item.quantity + '×'"></span>
                                                    <span x-text="item.name"></span>
                                                </div>
                                                <div class="text-xs text-gray-600" x-text="formatPrice(item.price * item.quantity)"></div>
                                            </div>
                                        </template>
                                    </div>
                                    
                                    <!-- Order Actions -->
                                    <div class="flex justify-between items-center">
                                        <div class="text-xs">
                                            <span x-text="(translations.kdsTimeLabel || 'Time') + ': ' + getOrderAgeText(order)"></span>
                                        </div>
                                        <div class="flex gap-2">
                                            <button @click="generateReceipt(order)"
                                                    class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600 transition">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        <button @click="updateOrderStatus(order.id, 'ready')"
                                                    class="bg-green-500 text-white px-3 py-1 rounded text-xs hover:bg-green-600 transition">
                                            <i class="fas fa-check mr-1"></i>
                                            <span x-text="translations.markReady"></span>
                                        </button>
                                    </div>
                                    </div>
                                    
                                    <!-- Assigned Chef/Station -->
                                    <div class="flex items-center gap-2 mt-2">
                                        <template x-if="order.assignedChef">
                                            <div class="bg-gray-100 px-2 py-1 rounded text-xs flex items-center gap-1">
                                                <i class="fas fa-user-tie text-primary"></i>
                                                <span x-text="translations.kdsAssignedTo + ': ' + order.assignedChef"></span>
                                                <span x-show="order.assignedStation">| <span x-text="order.assignedStation"></span></span>
                                                <button @click="unassignOrderChefStation(order.id)" class="ml-2 text-red-500 hover:text-red-700" title="Unassign">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </template>
                                        <!-- <template x-if="!order.assignedChef">
                                            <button @click="showChefAssignModal = true; chefAssignOrderId = order.id; chefAssignName = ''; chefAssignStation = '';" 
                                                    class="bg-primary text-white px-2 py-1 rounded text-xs hover:bg-teal-700 transition flex items-center gap-1">
                                                <i class="fas fa-user-plus"></i>
                                                <span x-text="translations.kdsAssignChef"></span>
                                            </button>
                                        </template> -->
                                    </div>
                                </div>
                            </template>
                        </template>
                        
                        <template x-if="getFilteredOrders().filter(o => o.status === 'preparing').length === 0">
                            <div class="text-center text-gray-500 py-8" x-text="translations.noPreparingOrders"></div>
                        </template>
                    </div>
                </div>
                
                <!-- Ready Orders -->
                <div>
                    <h3 class="text-lg font-bold mb-4 text-center bg-green-100 py-2 rounded">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span x-text="translations.ready"></span>
                        <span class="ml-2 bg-green-500 text-white px-2 py-1 rounded-full text-xs" 
                              x-text="getFilteredOrders().filter(o => o.status === 'ready').length"></span>
                    </h3>
                    
                    <div class="space-y-4 max-h-96 overflow-y-auto">
                        <template x-for="order in getFilteredOrders()" :key="order.id">
                            <template x-if="order.status === 'ready'">
                                <div class="bg-white border rounded-lg p-4 shadow-lg" 
                                     :class="getOrderStatusColor(order)">
                                    <!-- Order Header -->
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <div class="font-bold text-lg" x-text="'#' + order.id"></div>
                                            <div class="text-sm" x-text="formatDateTime(order.timestamp)"></div>
                                            <div x-show="order.tableNumber" class="text-xs" x-text="(translations.kdsTableLabel || translations.tableNumber || 'Table') + ' ' + order.tableNumber"></div>
                                            <div class="text-xs font-bold" x-text="(translations.kdsAgeLabel || 'Age') + ': ' + getOrderAgeText(order)"></div>
                                        </div>
                                        <div class="text-right">
                                            <div class="px-2 py-1 rounded-full text-xs mb-1" 
                                                 :class="{
                                                     'bg-blue-100 text-blue-800': order.type === 'dine-in',
                                                     'bg-green-100 text-green-800': order.type === 'takeaway',
                                                     'bg-red-100 text-red-800': order.type === 'delivery'
                                                 }"
                                                 x-text="getOrderTypeLabel(order.type)"></div>
                                            <div class="text-xs" x-text="(translations.kdsTotalLabel || translations.total || 'Total') + ': ' + formatPrice(order.total)"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Order Items -->
                                    <div class="border-t pt-3 mb-3">
                                        <template x-for="item in order.items" :key="item.id">
                                            <div class="flex justify-between text-sm py-1">
                                                <div class="flex items-center">
                                                    <span class="font-bold mr-2" x-text="item.quantity + '×'"></span>
                                                    <span x-text="item.name"></span>
                                                </div>
                                                <div class="text-xs text-gray-600" x-text="formatPrice(item.price * item.quantity)"></div>
                                            </div>
                                        </template>
                                    </div>
                                    
                                    <!-- Order Actions -->
                                    <div class="flex justify-between items-center">
                                        <div class="text-xs">
                                            <span x-text="(translations.kdsReadyLabel || translations.ready) + ': ' + getOrderAgeText(order)"></span>
                                        </div>
                                        <div class="flex gap-2">
                                            <button @click="generateReceipt(order)"
                                                    class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600 transition">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        <button @click="completeOrder(order.id)"
                                                    class="bg-gray-500 text-white px-3 py-1 rounded text-xs hover:bg-gray-600 transition">
                                            <i class="fas fa-times mr-1"></i>
                                            <span x-text="translations.complete"></span>
                                        </button>
                                    </div>
                                    </div>
                                    
                                    <!-- Assigned Chef/Station -->
                                    <div class="flex items-center gap-2 mt-2">
                                        <template x-if="order.assignedChef">
                                            <div class="bg-gray-100 px-2 py-1 rounded text-xs flex items-center gap-1">
                                                <i class="fas fa-user-tie text-primary"></i>
                                                <span x-text="translations.kdsAssignedTo + ': ' + order.assignedChef"></span>
                                                <span x-show="order.assignedStation">| <span x-text="order.assignedStation"></span></span>
                                                <button @click="unassignOrderChefStation(order.id)" class="ml-2 text-red-500 hover:text-red-700" title="Unassign">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </template>
                                        <!-- <template x-if="!order.assignedChef">
                                            <button @click="showChefAssignModal = true; chefAssignOrderId = order.id; chefAssignName = ''; chefAssignStation = '';" 
                                                    class="bg-primary text-white px-2 py-1 rounded text-xs hover:bg-teal-700 transition flex items-center gap-1">
                                                <i class="fas fa-user-plus"></i>
                                                <span x-text="translations.kdsAssignChef"></span>
                                            </button>
                                        </template> -->
                                    </div>
                                </div>
                            </template>
                        </template>
                        
                        <template x-if="getFilteredOrders().filter(o => o.status === 'ready').length === 0">
                            <div class="text-center text-gray-500 py-8" x-text="translations.noReadyOrders"></div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

