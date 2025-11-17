    <!-- Settings Modal -->
    <div x-cloak x-show="showSettings" 
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         @click="showSettings = false">
        <div class="bg-white rounded-lg p-6 w-full max-w-2xl mx-4 max-h-screen overflow-y-auto" @click.stop>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold" x-text="translations.settings"></h3>
                <button @click="showSettings = false" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form @submit.prevent="saveSettings(); showSettings = false">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Restaurant Information -->
                    <div>
                        <h4 class="font-bold mb-3" x-text="translations.restaurantInfo"></h4>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium mb-1" x-text="translations.restaurantName"></label>
                                <input type="text" x-model="settings.restaurantName" required
                                       class="w-full border rounded-lg px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1" x-text="translations.address"></label>
                                <textarea x-model="settings.address" rows="2"
                                          class="w-full border rounded-lg px-3 py-2"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1" x-text="translations.phone"></label>
                                <input type="text" x-model="settings.phone"
                                       class="w-full border rounded-lg px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1" x-text="translations.email"></label>
                                <input type="email" x-model="settings.email"
                                       class="w-full border rounded-lg px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1" x-text="translations.website"></label>
                                <input type="text" x-model="settings.website"
                                       class="w-full border rounded-lg px-3 py-2">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Receipt Settings -->
                    <div>
                        <h4 class="font-bold mb-3" x-text="translations.receiptSettings"></h4>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium mb-1" x-text="translations.taxRate"></label>
                                <input type="number" x-model="settings.taxRate" min="0" max="100" step="0.1" required
                                       class="w-full border rounded-lg px-3 py-2">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-1" x-text="translations.currency"></label>
                                <select x-model="settings.currency" class="w-full border rounded-lg px-3 py-2">
                                    <option value="PHP">PHP (₱)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1" x-text="translations.receiptFooter"></label>
                                <textarea x-model="settings.receiptFooter" rows="2"
                                          class="w-full border rounded-lg px-3 py-2"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Print Settings -->
                    <div>
                        <h4 class="font-bold mb-3" x-text="translations.printSettings"></h4>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" x-model="settings.printHeader" id="printHeader"
                                       class="mr-2">
                                <label for="printHeader" class="text-sm" x-text="translations.printHeader"></label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" x-model="settings.printFooter" id="printFooter"
                                       class="mr-2">
                                <label for="printFooter" class="text-sm" x-text="translations.printFooter"></label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" x-model="settings.autoPrint" id="autoPrint"
                                       class="mr-2">
                                <label for="autoPrint" class="text-sm" x-text="translations.autoPrint"></label>
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1" x-text="translations.receiptWidth"></label>
                                <input type="number" x-model="settings.receiptWidth" min="50" max="120" step="1"
                                       class="w-full border rounded-lg px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1" x-text="translations.fontSize"></label>
                                <input type="number" x-model="settings.fontSize" min="8" max="16" step="1"
                                       class="w-full border rounded-lg px-3 py-2">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="showSettings = false"
                            class="border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                        <span x-text="translations.cancel"></span>
                    </button>
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
                        <span x-text="translations.saveSettings"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Receipt Modal -->
    <div x-cloak x-show="showReceipt" 
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         @click="showReceipt = false">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4 max-h-screen overflow-y-auto" @click.stop>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold" x-text="translations.receipt"></h3>
                <button @click="showReceipt = false" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="receipt-preview mb-4 p-4 border rounded-lg bg-gray-50" 
                 style="font-family: 'Courier New', monospace; font-size: 12px;">
                <style>
                    .receipt-preview .receipt { text-align: center; }
                    .receipt-preview .header { margin-bottom: 10px; }
                    .receipt-preview .logo { max-width: 60px; max-height: 60px; margin: 0 auto 10px; }
                    .receipt-preview .title { font-size: 16pt; font-weight: bold; margin: 5px 0; }
                    .receipt-preview .info { font-size: 10pt; margin: 2px 0; }
                    .receipt-preview .divider { border-top: 1px dashed #000; margin: 10px 0; }
                    .receipt-preview .items-table { width: 100%; border-collapse: collapse; margin: 10px 0; }
                    .receipt-preview .items-table th { text-align: left; padding: 5px 0; border-bottom: 1px solid #000; font-weight: bold; }
                    .receipt-preview .items-table td { padding: 3px 0; border-bottom: 1px dashed #ccc; }
                    .receipt-preview .items-table .qty { width: 10%; text-align: left; }
                    .receipt-preview .items-table .item-desc { width: 60%; text-align: left; }
                    .receipt-preview .items-table .unit-price { width: 30%; text-align: right; }
                    .receipt-preview .totals { margin: 10px 0; }
                    .receipt-preview .totals-table { width: 100%; border-collapse: collapse; }
                    .receipt-preview .totals-table td { padding: 3px 0; }
                    .receipt-preview .totals-table .description { text-align: left; }
                    .receipt-preview .totals-table .amount { text-align: right; }
                    .receipt-preview .total-line { font-weight: bold; }
                    .receipt-preview .grand-total { font-weight: bold; font-size: 14pt; border-top: 1px solid #000; padding-top: 5px; }
                    .receipt-preview .payment-section { margin-top: 15px; }
                    .receipt-preview .payment-title { font-weight: bold; margin: 10px 0 5px 0; }
                    .receipt-preview .payment-table { width: 100%; border-collapse: collapse; }
                    .receipt-preview .payment-table td { padding: 3px 0; }
                    .receipt-preview .payment-table .description { text-align: left; }
                    .receipt-preview .payment-table .amount { text-align: right; }
                    .receipt-preview .footer { margin-top: 15px; font-size: 10pt; }
                </style>
                <div x-html="generateReceiptHTML()"></div>
            </div>
            
            <div class="flex justify-end gap-3">
                <button @click="showReceipt = false"
                        class="border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                    <span x-text="translations.cancel"></span>
                </button>
                <button @click="printReceipt()" 
                        class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
                    <i class="fas fa-print mr-2"></i>
                    <span x-text="translations.print"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Table Form Modal -->
    <div x-cloak x-show="showTableForm" 
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         @click="showTableForm = false">
         
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4" @click.stop>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold" x-text="editingTable ? translations.editTable : translations.addTable"></h3>
                <button @click="showTableForm = false" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form @submit.prevent="saveTable()">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1" x-text="translations.tableNumber"></label>
                        <input type="number" x-model="tableForm.number" min="1" required
                               class="w-full border rounded-lg px-3 py-2">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1" x-text="translations.capacity"></label>
                        <input type="number" x-model="tableForm.capacity" min="1" max="20" required
                               class="w-full border rounded-lg px-3 py-2">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1" x-text="translations.location"></label>
                        <input type="text" x-model="tableForm.location" placeholder="e.g., Window, Patio, Bar"
                               class="w-full border rounded-lg px-3 py-2">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1" x-text="translations.tableStatus"></label>
                        <select x-model="tableForm.status" class="w-full border rounded-lg px-3 py-2">
                            <option value="available" x-text="translations.available"></option>
                            <option value="reserved" x-text="translations.reserved"></option>
                            <option value="occupied" x-text="translations.occupied"></option>
                            <option value="cleaning" x-text="translations.cleaning"></option>
                            <option value="maintenance" x-text="translations.maintenance"></option>
                        </select>
                    </div>
                    
                    <div x-show="tableForm.status === 'reserved'">
                        <label class="block text-sm font-medium mb-1" x-text="translations.customerName"></label>
                        <input type="text" x-model="tableForm.customerName" placeholder="Customer name"
                               class="w-full border rounded-lg px-3 py-2">
                    </div>
                    
                    <div x-show="tableForm.status === 'reserved'">
                        <label class="block text-sm font-medium mb-1" x-text="translations.customerPhone"></label>
                        <input type="text" x-model="tableForm.customerPhone" placeholder="Customer phone"
                               class="w-full border rounded-lg px-3 py-2">
                    </div>
                    
                    <div x-show="tableForm.status === 'reserved'">
                        <label class="block text-sm font-medium mb-1" x-text="translations.reservationTime"></label>
                        <input type="datetime-local" x-model="tableForm.reservationTime"
                               class="w-full border rounded-lg px-3 py-2">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium mb-1" x-text="translations.notes"></label>
                        <textarea x-model="tableForm.notes" rows="3" placeholder="Additional notes about the table"
                                  class="w-full border rounded-lg px-3 py-2"></textarea>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="showTableForm = false"
                            class="border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                        <span x-text="translations.cancel"></span>
                    </button>
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
                        <span x-text="translations.saveRecipe"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Enhanced Backup Modal -->
    <div x-cloak x-show="showBackup" 
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         @click="showBackup = false">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4" @click.stop>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold" x-text="translations.backup"></h3>
                <button @click="showBackup = false" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <h4 class="font-bold mb-2" x-text="translations.exportBackup"></h4>
                    <p class="text-sm text-gray-600 mb-3">Export all data including recipes, orders, tables, settings, chefs, and stations.</p>
                    <button @click="exportAllData()" 
                            class="w-full bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition">
                        <i class="fas fa-download mr-2"></i>
                        <span x-text="translations.exportBackup"></span>
                    </button>
                </div>
                
                <div class="border-t pt-4">
                    <h4 class="font-bold mb-2" x-text="translations.importBackup"></h4>
                    <p class="text-sm text-gray-600 mb-3">Import backup file to restore all data. This will replace current data.</p>
                    <input type="file" @change="importSettings($event.target.files[0])" accept=".json"
                           class="w-full border rounded-lg px-3 py-2">
                </div>
                
                <div class="border-t pt-4">
                    <h4 class="font-bold mb-2">Data Management</h4>
                    <div class="space-y-2">
                        <button @click="validateAndCleanData(); alert('Data cleaned successfully!')" 
                                class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                            <i class="fas fa-broom mr-2"></i>
                            Clean & Validate Data
                        </button>
                        <button @click="clearAllData()" 
                                class="w-full bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition">
                            <i class="fas fa-trash mr-2"></i>
                            Clear All Data
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end mt-6">
                <button @click="showBackup = false"
                        class="border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                    <span x-text="translations.cancel"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- KDS Settings Modal -->
    <div x-cloak x-show="showKdsSettings" 
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         @click="showKdsSettings = false">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4" @click.stop>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold" x-text="translations.kdsSettings"></h3>
                <button @click="showKdsSettings = false" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1" x-text="translations.kdsRefreshInterval"></label>
                    <input type="number" x-model="kdsRefreshInterval" min="5" max="300" step="5"
                           class="w-full border rounded-lg px-3 py-2">
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" x-model="kdsAutoRefresh" id="kdsAutoRefresh"
                           class="mr-2">
                    <label for="kdsAutoRefresh" class="text-sm" x-text="translations.kdsAutoRefresh"></label>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" x-model="kdsSoundEnabled" id="kdsSoundEnabled"
                           class="mr-2">
                    <label for="kdsSoundEnabled" class="text-sm" x-text="translations.kdsSoundEnabled"></label>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" x-model="kdsNotifications" id="kdsNotifications"
                           class="mr-2">
                    <label for="kdsNotifications" class="text-sm" x-text="translations.kdsNotifications"></label>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6">
                <button @click="showKdsSettings = false"
                        class="border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                    <span x-text="translations.cancel"></span>
                </button>
                <button @click="showKdsSettings = false; startKdsAutoRefresh()" 
                        class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
                    <span x-text="translations.saveSettings"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Chef Assignment Modal -->
    <div x-cloak x-show="showChefAssignModal" 
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         @click="showChefAssignModal = false">
        <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4" @click.stop>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold" x-text="translations.kdsAssignChef"></h3>
                <button @click="showChefAssignModal = false" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form @submit.prevent="assignOrderToChefStation(chefAssignOrderId, chefAssignName, chefAssignStation)">
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1" x-text="translations.kdsSelectChef"></label>
                    <select x-model="chefAssignName" class="w-full border rounded-lg px-3 py-2" required>
                        <option value="" disabled selected>Select</option>
                        <template x-for="chef in chefs" :key="chef.name">
                            <option :value="chef.name" x-text="chef.name"></option>
                        </template>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1" x-text="translations.kdsSelectStation"></label>
                    <select x-model="chefAssignStation" class="w-full border rounded-lg px-3 py-2" required>
                        <option value="" disabled selected>Select</option>
                        <template x-for="station in stations" :key="station.name">
                            <option :value="station.name" x-text="station.name"></option>
                        </template>
                    </select>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" @click="showChefAssignModal = false"
                            class="border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                        <span x-text="translations.cancel"></span>
                    </button>
                    <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
                        <span x-text="translations.kdsSave"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Chef/Station Management Modal -->
    <div x-cloak x-show="showChefStationManager" 
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         @click="showChefStationManager = false">
        <div class="bg-white rounded-lg p-6 w-full max-w-2xl mx-4 max-h-screen overflow-y-auto" @click.stop>
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold" x-text="translations.kdsManageChefs"></h3>
                <button @click="showChefStationManager = false" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div>
                <div class="flex gap-4 mb-6">
                    <button @click="chefStationTab = 'chefs'" :class="{'bg-primary text-white': chefStationTab === 'chefs', 'bg-gray-100': chefStationTab !== 'chefs'}" class="px-4 py-2 rounded-lg font-bold">
                        <span x-text="translations.kdsManageChefs"></span>
                    </button>
                    <button @click="chefStationTab = 'stations'" :class="{'bg-primary text-white': chefStationTab === 'stations', 'bg-gray-100': chefStationTab !== 'stations'}" class="px-4 py-2 rounded-lg font-bold">
                        <span x-text="translations.kdsManageStations"></span>
                    </button>
                </div>
                <!-- Chefs Tab -->
                <div x-show="chefStationTab === 'chefs'">
                    <div class="mb-4 flex gap-2">
                        <input type="text" x-model="newChefName" placeholder="Add chef..." class="border rounded-lg px-3 py-2 flex-1">
                        <button @click="addChef()" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition" x-text="translations.kdsAddChef"></button>
                    </div>
                    <ul>
                        <template x-for="(chef, idx) in chefs" :key="chef.name">
                            <li class="flex items-center gap-2 mb-2">
                                <input type="text" x-model="chef.name" class="border rounded-lg px-3 py-1 flex-1">
                                <button @click="removeChef(idx)" class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                            </li>
                        </template>
                    </ul>
                </div>
                <!-- Stations Tab -->
                <div x-show="chefStationTab === 'stations'">
                    <div class="mb-4 flex gap-2">
                        <input type="text" x-model="newStationName" placeholder="Add station..." class="border rounded-lg px-3 py-2 flex-1">
                        <button @click="addStation()" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition" x-text="translations.kdsAddStation"></button>
                    </div>
                    <ul>
                        <template x-for="(station, idx) in stations" :key="station.name">
                            <li class="flex items-center gap-2 mb-2">
                                <input type="text" x-model="station.name" class="border rounded-lg px-3 py-1 flex-1">
                                <button @click="removeStation(idx)" class="text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button @click="showChefStationManager = false"
                        class="border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                    <span x-text="translations.cancel"></span>
                </button>
            </div>
        </div>
    </div>

        <!-- Inventory Form Modal -->
        <div x-cloak x-show="showInventoryForm" 
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
             @click="showInventoryForm = false">
            <div class="bg-white rounded-lg p-6 w-full max-w-2xl mx-4 max-h-screen overflow-y-auto" @click.stop>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold" x-text="editingInventory ? 'Edit Inventory Item' : 'Add Inventory Item'"></h3>
                    <button @click="showInventoryForm = false" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form @submit.prevent="saveInventoryItem()">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Item Name</label>
                            <input type="text" x-model="inventoryForm.name" required
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Category</label>
                            <input type="text" x-model="inventoryForm.category" required
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Unit</label>
                            <input type="text" x-model="inventoryForm.unit" required
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Current Stock</label>
                            <input type="number" x-model="inventoryForm.currentStock" min="0" step="0.01" required
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Min Stock</label>
                            <input type="number" x-model="inventoryForm.minStock" min="0" step="0.01" required
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Max Stock</label>
                            <input type="number" x-model="inventoryForm.maxStock" min="0" step="0.01" required
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Cost per Unit</label>
                            <input type="number" x-model="inventoryForm.cost" min="0" step="0.01" required
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Supplier ID</label>
                            <select x-model="inventoryForm.supplier_id" class="w-full border rounded-lg px-3 py-2">
                                <option value="">Select Supplier</option>
                                <template x-for="supplier in suppliers" :key="supplier.id">
                                    <option :value="supplier.id" x-text="supplier.name"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Location</label>
                            <input type="text" x-model="inventoryForm.location"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Expiry Date</label>
                            <input type="date" x-model="inventoryForm.expiryDate"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label class="block text-sm font-medium mb-1">Notes</label>
                        <textarea x-model="inventoryForm.notes" rows="3"
                                  class="w-full border rounded-lg px-3 py-2"></textarea>
                    </div>
                    
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="showInventoryForm = false"
                                class="border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                            Cancel
                        </button>
                        <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Purchase Form Modal -->
        <div x-cloak x-show="showPurchaseForm" 
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
             @click="showPurchaseForm = false">
            <div class="bg-white rounded-lg p-6 w-full max-w-2xl mx-4 max-h-screen overflow-y-auto" @click.stop>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold" x-text="editingPurchase ? translations.editPurchase : translations.addPurchase"></h3>
                    <button @click="showPurchaseForm = false" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form @submit.prevent="savePurchase()">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium mb-1" x-text="translations.supplier"></label>
                            <input type="text" x-model="purchaseForm.supplier" required
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" x-text="translations.purchaseDate"></label>
                            <input type="date" x-model="purchaseForm.purchaseDate" required
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" x-text="translations.expectedDelivery"></label>
                            <input type="date" x-model="purchaseForm.expectedDelivery"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" x-text="translations.totalCost"></label>
                            <input type="number" x-model="purchaseForm.totalCost" min="0" step="0.01" required
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Purchase Items</label>
                        <div class="space-y-2">
                            <template x-for="(item, index) in purchaseForm.items" :key="index">
                                <div class="grid grid-cols-12 gap-2">
                                    <select x-model="item.inventoryId" class="col-span-4 border rounded-lg px-3 py-2">
                                        <option value="">Select Item</option>
                                        <template x-for="invItem in inventory" :key="invItem.id">
                                            <option :value="invItem.id" x-text="invItem.name"></option>
                                        </template>
                                    </select>
                                    <input type="text" x-model="item.name" placeholder="Item Name"
                                           class="col-span-3 border rounded-lg px-3 py-2">
                                    <input type="number" x-model="item.quantity" step="0.01" min="0" placeholder="Qty"
                                           class="col-span-2 border rounded-lg px-3 py-2">
                                    <input type="text" x-model="item.unit" placeholder="Unit"
                                           class="col-span-2 border rounded-lg px-3 py-2">
                                    <button type="button" @click="removePurchaseItem(index)"
                                            class="col-span-1 text-red-500 hover:text-red-700">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </template>
                        </div>
                        <button type="button" @click="addPurchaseItem()"
                                class="mt-2 text-sm text-primary">
                            <i class="fas fa-plus mr-1"></i>Add Item
                        </button>
                    </div>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1" x-text="translations.notes"></label>
                        <textarea x-model="purchaseForm.notes" rows="3"
                                  class="w-full border rounded-lg px-3 py-2"></textarea>
                    </div>
                    
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="showPurchaseForm = false"
                                class="border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                            <span x-text="translations.cancel"></span>
                        </button>
                        <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
                            <span x-text="translations.savePurchase"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Supplier Form Modal -->
        <div x-cloak x-show="showSupplierForm" 
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
             @click="showSupplierForm = false">
            <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4" @click.stop>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold" x-text="editingSupplier ? translations.editSupplier : translations.addSupplier"></h3>
                    <button @click="showSupplierForm = false" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form @submit.prevent="saveSupplier()">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1" x-text="translations.supplierName"></label>
                            <input type="text" x-model="supplierForm.name" required
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" x-text="translations.contact"></label>
                            <input type="text" x-model="supplierForm.contact"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" x-text="translations.phone"></label>
                            <input type="text" x-model="supplierForm.phone"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" x-text="translations.email"></label>
                            <input type="email" x-model="supplierForm.email"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" x-text="translations.address"></label>
                            <textarea x-model="supplierForm.address" rows="2"
                                      class="w-full border rounded-lg px-3 py-2"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" x-text="translations.paymentTerms"></label>
                            <input type="text" x-model="supplierForm.paymentTerms"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" x-text="translations.notes"></label>
                            <textarea x-model="supplierForm.notes" rows="2"
                                      class="w-full border rounded-lg px-3 py-2"></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="showSupplierForm = false"
                                class="border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                            <span x-text="translations.cancel"></span>
                        </button>
                        <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
                            <span x-text="translations.saveSupplier"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Waste Form Modal -->
        <div x-cloak x-show="showWasteForm" 
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
             @click="showWasteForm = false">
            <div class="bg-white rounded-lg p-6 w-full max-w-md mx-4" @click.stop>
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold" x-text="translations.addWaste"></h3>
                    <button @click="showWasteForm = false" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form @submit.prevent="saveWaste()">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Item</label>
                            <select x-model="wasteForm.item" required class="w-full border rounded-lg px-3 py-2">
                                <option value="">Select Item</option>
                                <template x-for="item in inventory" :key="item.id">
                                    <option :value="item.id" x-text="item.name"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" x-text="translations.wasteQuantity"></label>
                            <input type="number" x-model="wasteForm.quantity" min="0" step="0.01" required
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" x-text="translations.wasteReason"></label>
                            <input type="text" x-model="wasteForm.reason" required
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" x-text="translations.wasteDate"></label>
                            <input type="date" x-model="wasteForm.date" required
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1" x-text="translations.notes"></label>
                            <textarea x-model="wasteForm.notes" rows="2"
                                      class="w-full border rounded-lg px-3 py-2"></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" @click="showWasteForm = false"
                                class="border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                            <span x-text="translations.cancel"></span>
                        </button>
                        <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
                            <span x-text="translations.saveWaste"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Audio element for new order notification -->
    <audio id="newOrderSound" preload="auto">
        <source src="https://assets.mixkit.co/sfx/preview/mixkit-alarm-digital-clock-beep-989.mp3" type="audio/mpeg">
    </audio>
</body>
</html>
