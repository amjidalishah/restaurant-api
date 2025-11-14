// js/state.js
// Main state management for Alpine.js app

import api from './api.js';

export const createAppState = () => ({
    // App state
    currentUser: window.__appUser || null,
    currentTab: 'pos',
    language: 'en',
    direction: 'ltr',
    orderType: 'dine-in',

    // POS State Variables
    posSearchTerm: '',
    posFilterCategory: 'all',
    posSortBy: 'name',
    posQuickFilter: 'all',
    posActiveCategory: 'all',
    showRecipeForm: false,
    editingRecipe: null,
    selectedRecipe: null,
    scalePortions: 4,
    showSettings: false,
    selectedTable: null,
    showReceipt: false,
    currentReceipt: null,
    showPrintOptions: false,
    showReports: false,
    showBackup: false,

    // KDS State
    kdsView: 'all',
    kdsFilter: 'all',
    kdsSort: 'time',
    showKdsSettings: false,
    kdsAutoRefresh: true,
    kdsSoundEnabled: true,
    kdsNotifications: true,
    kdsRefreshInterval: 30,
    kdsPriorityOrders: [],
    kdsUrgentOrders: [],
    kdsRefreshTimer: null,
    kdsStationFilter: 'all',

    // Chef/Station Management
    showChefAssignModal: false,
    chefAssignOrderId: null,
    chefAssignName: '',
    chefAssignStation: '',
    showChefStationManager: false,
    chefStationTab: 'chefs',
    newChefName: '',
    newStationName: '',

    // Data arrays (will be populated from API/localStorage)
    recipes: [],
    orders: [],
    tables: [],
    chefs: [],
    stations: [],
    settings: {},
    reports: {
        dailySales: {
            date: '',
            totalSales: 0,
            totalOrders: 0,
            completedOrders: 0,
            averageOrder: 0,
            completionRate: 0,
            totalTax: 0,
            totalDeliveryFees: 0,
            netSales: 0,
        },
        monthlySales: {
            year: new Date().getFullYear(),
            month: new Date().getMonth() + 1,
            totalSales: 0,
            totalOrders: 0,
            completedOrders: 0,
            averageOrder: 0,
            completionRate: 0,
            totalTax: 0,
            totalDeliveryFees: 0,
            netSales: 0,
            averageDailySales: 0,
        },
        topItems: [],
        categorySales: [],
        orderTypeSales: [],
        hourlySales: []
    },

    // Current order
    currentOrder: {
        items: [],
        subtotal: 0,
        tax: 0,
        total: 0,
        tableNumber: null,
        deliveryFee: 0
    },

    // Recipe Management
    recipeCategories: ['Pizza', 'Salad', 'Burgers', 'Appetizers', 'Pasta', 'Seafood', 'Mexican', 'Desserts', 'Sides', 'Soups', 'Beverages'],
    showRecipeCategories: false,
    recipeSearchTerm: '',
    recipeFilterCategory: 'all',
    recipeSortBy: 'name',
    recipeSortOrder: 'asc',

    // Recipe form
    recipeForm: {
        id: null,
        name: '',
        category: '',
        price: 0,
        basePortions: 4,
        prepTime: 15,
        cookTime: 20,
        difficulty: 'medium',
        allergens: [],
        tags: [],
        ingredients: [{ name: '', quantity: 0, unit: '', notes: '' }],
        instructions: '',
        notes: '',
        image: '',
        isActive: true,
        createdAt: null,
        updatedAt: null
    },

    // Table form
    tableForm: {
        id: null,
        number: '',
        capacity: 4,
        status: 'available',
        location: '',
        notes: '',
        reservationTime: null,
        customerName: '',
        customerPhone: ''
    },
    showTableForm: false,
    editingTable: null,

    // Translations
    translations: {
        // App
        appTitle: 'Restaurant Manager',

        // POS
        pos: 'POS',
        orderType: 'Order Type',
        dineIn: 'Dine-In',
        takeaway: 'Takeaway',
        delivery: 'Delivery',
        currentOrder: 'Current Order',
        item: 'Item',
        total: 'Total',
        subtotal: 'Subtotal',
        tax: 'Tax (10%)',
        placeOrder: 'Place Order',
        emptyOrder: 'Add items to your order',

        // KDS
        kds: 'Kitchen Display',
        newOrders: 'New Orders',
        preparing: 'Preparing',
        ready: 'Ready',
        startPreparing: 'Start Preparing',
        markReady: 'Mark as Ready',
        complete: 'Complete',
        noNewOrders: 'No new orders',
        noPreparingOrders: 'No orders in preparation',
        noReadyOrders: 'No orders ready',
        overdue: 'Overdue',
        late: 'Late',
        early: 'Early',
        'on-time': 'On Time',
        kdsAllOrders: 'All Orders',
        kdsAllTypes: 'All Types',
        kdsAllStations: 'All Stations',
        kdsSortTime: 'By Time',
        kdsSortPriority: 'By Priority',
        kdsSortTable: 'By Table',
        kdsStatsTotal: 'Total Orders',
        kdsStatsCompleted: 'Completed',
        kdsStatsPending: 'Pending',
        kdsStatsAvgPrep: 'Avg Prep Time',
        kdsStatsEfficiency: 'Efficiency',
        kdsTableLabel: 'Table',
        kdsAgeLabel: 'Age',
        kdsPriorityLabel: 'Priority',
        kdsEstimateLabel: 'Est',
        kdsTimeLabel: 'Time',
        kdsReadyLabel: 'Ready',
        kdsProgressLabel: 'Progress',
        kdsStatusLabel: 'Status',
        kdsTotalLabel: 'Total',
        kdsAssignedTo: 'Assigned to',
        kdsAssignChef: 'Assign Chef',
        kdsManageChefs: 'Manage Chefs',
        kdsUrgent: 'Urgent',
        kdsAddChef: 'Add Chef',
        kdsManageStations: 'Manage Stations',

        // Recipes
        recipes: 'Recipes',
        recipeList: 'Recipe List',
        addRecipe: 'Add Recipe',
        editRecipe: 'Edit Recipe',
        recipeName: 'Recipe Name',
        category: 'Category',
        price: 'Price',
        basePortions: 'Base Portions',
        ingredients: 'Ingredients',
        instructions: 'Instructions',
        addIngredient: 'Add Ingredient',
        saveRecipe: 'Save Recipe',
        cancel: 'Cancel',
        scaleToPortions: 'Scale to Portions',
        selectRecipe: 'Select a recipe to view details',
        noRecipes: 'No recipes found. Add your first recipe!',
        deleteRecipeConfirm: 'Are you sure you want to delete this recipe?',

        // Settings
        settings: 'Settings',
        taxRate: 'Tax Rate (%)',
        deliveryFee: 'Delivery Fee',
        currency: 'Currency',
        saveSettings: 'Save Settings',

        // Tables
        tables: 'Tables',
        tableManagement: 'Table Management',
        addTable: 'Add Table',
        tableNumber: 'Table Number',
        capacity: 'Capacity',
        status: 'Status',
        available: 'Available',
        occupied: 'Occupied',
        reserved: 'Reserved',
        selectTable: 'Select Table',
        noTables: 'No tables available',
        tableOrders: 'Table Orders',
        newOrder: 'New Order',
        viewOrders: 'View Orders',
        closeTable: 'Close Table',
        editTable: 'Edit Table',
        deleteTable: 'Delete Table',
        location: 'Location',
        notes: 'Notes',
        reservation: 'Reservation',
        customerName: 'Customer Name',
        customerPhone: 'Customer Phone',
        reservationTime: 'Reservation Time',
        makeReservation: 'Make Reservation',
        cancelReservation: 'Cancel Reservation',
        tableDetails: 'Table Details',
        tableHistory: 'Table History',
        occupancyTime: 'Occupancy Time',
        revenue: 'Revenue',
        averageOrderValue: 'Average Order Value',
        tableStatus: 'Table Status',
        cleaning: 'Cleaning',
        maintenance: 'Maintenance',
        inventory: 'Inventory',
        inventoryManagement: 'Inventory Management',
        purchases: 'Purchases',
        suppliers: 'Suppliers',
        waste: 'Waste',
        inventoryReports: 'Inventory Reports',
        addInventory: 'Add Inventory',
        addPurchase: 'Add Purchase',
        addSupplier: 'Add Supplier',
        addWaste: 'Add Waste',
        exportInventory: 'Export Inventory',
        importInventory: 'Import Inventory',
        supplier: 'Supplier',
        supplierName: 'Supplier Name',
        contact: 'Contact',
        purchaseDate: 'Purchase Date',
        expectedDelivery: 'Expected Delivery',
        totalCost: 'Total Cost',
        savePurchase: 'Save Purchase',
        saveSupplier: 'Save Supplier',
        paymentTerms: 'Payment Terms',
        wasteQuantity: 'Waste Quantity',
        wasteReason: 'Waste Reason',
        wasteDate: 'Waste Date',
        saveWaste: 'Save Waste',

        // Receipt & Print
        receipt: 'Receipt',
        printReceipt: 'Print Receipt',
        printOptions: 'Print Options',
        print: 'Print',
        receiptNumber: 'Receipt #',
        date: 'Date',
        time: 'Time',
        server: 'Server',
        customer: 'Customer',
        items: 'Items',
        qty: 'Qty',
        amount: 'Amount',
        serviceCharge: 'Service Charge',
        discount: 'Discount',
        grandTotal: 'Grand Total',
        paymentMethod: 'Payment Method',
        cash: 'Cash',
        card: 'Card',
        change: 'Change',
        thankYou: 'Thank You',

        // Reports
        reports: 'Reports',
        salesReport: 'Sales Report',
        dailyReport: 'Daily Report',
        monthlyReport: 'Monthly Report',
        topItems: 'Top Items',
        categoryReport: 'Category Report',
        exportData: 'Export Data',
        generateReport: 'Generate Report',
        totalSales: 'Total Sales',
        totalOrders: 'Total Orders',
        completedOrders: 'Completed Orders',
        averageOrder: 'Average Order',

        // Backup & Settings
        backup: 'Backup & Restore',
        exportBackup: 'Export Backup',
        importBackup: 'Import Backup',
        restoreData: 'Restore Data',
        restaurantInfo: 'Restaurant Information',
        restaurantName: 'Restaurant Name',
        address: 'Address',
        phone: 'Phone',
        email: 'Email',
        website: 'Website',
        contactInfo: 'Contact Information',
        receiptSettings: 'Receipt Settings',
        taxRate: 'Tax Rate (%)',
        deliveryFee: 'Delivery Fee',
        currency: 'Currency',
        receiptFooter: 'Receipt Footer',
        printSettings: 'Print Settings',
        printHeader: 'Print Header',
        printFooter: 'Print Footer',
        autoPrint: 'Auto Print',
        receiptWidth: 'Receipt Width (mm)',
        fontSize: 'Font Size (pt)',
        saveSettings: 'Save Settings',
        logoUpload: 'Upload Logo'
    },

    // Arabic translations
    arTranslations: {
        appTitle: 'مدير المطعم',
        pos: 'نقطة البيع',
        orderType: 'نوع الطلب',
        dineIn: 'تناول في المطعم',
        takeaway: 'طلبات خارجية',
        delivery: 'توصيل',
        currentOrder: 'الطلب الحالي',
        item: 'الصنف',
        total: 'الإجمالي',
        subtotal: 'المجموع الفرعي',
        tax: 'الضريبة (10%)',
        placeOrder: 'تقديم الطلب',
        emptyOrder: 'أضف أصناف إلى طلبك',
        kds: 'شاشة المطبخ',
        newOrders: 'طلبات جديدة',
        preparing: 'قيد التحضير',
        ready: 'جاهزة',
        startPreparing: 'بدء التحضير',
        markReady: 'وضع علامة جاهز',
        complete: 'إكمال',
        noNewOrders: 'لا توجد طلبات جديدة',
        noPreparingOrders: 'لا توجد طلبات قيد التحضير',
        noReadyOrders: 'لا توجد طلبات جاهزة',
        overdue: 'متأخر جداً',
        late: 'متأخر',
        early: 'مبكر',
        'on-time': 'في الوقت المحدد',
        kdsAllOrders: 'كل الطلبات',
        kdsAllTypes: 'كل الأنواع',
        kdsAllStations: 'كل المحطات',
        kdsSortTime: 'حسب الوقت',
        kdsSortPriority: 'حسب الأولوية',
        kdsSortTable: 'حسب الطاولة',
        kdsStatsTotal: 'إجمالي الطلبات',
        kdsStatsCompleted: 'المكتملة',
        kdsStatsPending: 'قيد الانتظار',
        kdsStatsAvgPrep: 'متوسط وقت التحضير',
        kdsStatsEfficiency: 'الكفاءة',
        kdsTableLabel: 'طاولة',
        kdsAgeLabel: 'المدة',
        kdsPriorityLabel: 'الأولوية',
        kdsEstimateLabel: 'تقدير',
        kdsTimeLabel: 'الوقت',
        kdsReadyLabel: 'جاهز',
        kdsProgressLabel: 'التقدم',
        kdsStatusLabel: 'الحالة',
        kdsTotalLabel: 'الإجمالي',
        kdsAssignedTo: 'مخصص لـ',
        kdsAssignChef: 'تعيين طاهٍ',
        kdsManageChefs: 'إدارة الطهاة',
        kdsUrgent: 'عاجل',
        recipes: 'الوصفات',
        recipeList: 'قائمة الوصفات',
        addRecipe: 'إضافة وصفة',
        editRecipe: 'تعديل الوصفة',
        recipeName: 'اسم الوصفة',
        category: 'الفئة',
        price: 'السعر',
        basePortions: 'الوجبات الأساسية',
        ingredients: 'المكونات',
        instructions: 'التعليمات',
        addIngredient: 'إضافة مكون',
        saveRecipe: 'حفظ الوصفة',
        cancel: 'إلغاء',
        scaleToPortions: 'تغيير الكمية للوجبات',
        selectRecipe: 'حدد وصفة لعرض التفاصيل',
        noRecipes: 'لم يتم العثور على وصفات. أضف وصفتك الأولى!',
        deleteRecipeConfirm: 'هل أنت متأكد من حذف هذه الوصفة؟',
        settings: 'الإعدادات',
        taxRate: 'نسبة الضريبة (%)',
        deliveryFee: 'رسوم التوصيل',
        currency: 'العملة',
        saveSettings: 'حفظ الإعدادات',
        tables: 'الطاولات',
        tableManagement: 'إدارة الطاولات',
        addTable: 'إضافة طاولة',
        tableNumber: 'رقم الطاولة',
        capacity: 'السعة',
        status: 'الحالة',
        available: 'متاحة',
        occupied: 'مشغولة',
        reserved: 'محجوزة',
        selectTable: 'اختر الطاولة',
        noTables: 'لا توجد طاولات متاحة',
        tableOrders: 'طلبات الطاولة',
        newOrder: 'طلب جديد',
        viewOrders: 'عرض الطلبات',
        closeTable: 'إغلاق الطاولة',
        editTable: 'تعديل الطاولة',
        deleteTable: 'حذف الطاولة',
        location: 'الموقع',
        notes: 'ملاحظات',
        reservation: 'الحجز',
        customerName: 'اسم العميل',
        customerPhone: 'هاتف العميل',
        reservationTime: 'وقت الحجز',
        makeReservation: 'إجراء حجز',
        cancelReservation: 'إلغاء الحجز',
        tableDetails: 'تفاصيل الطاولة',
        tableHistory: 'تاريخ الطاولة',
        occupancyTime: 'وقت الإشغال',
        revenue: 'الإيرادات',
        averageOrderValue: 'متوسط قيمة الطلب',
        tableStatus: 'حالة الطاولة',
        cleaning: 'تنظيف',
        maintenance: 'صيانة',
        inventory: 'المخزون',
        inventoryManagement: 'إدارة المخزون',
        purchases: 'طلبات الشراء',
        suppliers: 'الموردون',
        waste: 'الفاقد',
        inventoryReports: 'تقارير المخزون',
        addInventory: 'إضافة مخزون',
        addPurchase: 'إضافة شراء',
        addSupplier: 'إضافة مورد',
        addWaste: 'إضافة فاقد',
        exportInventory: 'تصدير المخزون',
        importInventory: 'استيراد المخزون',
        supplier: 'المورد',
        supplierName: 'اسم المورد',
        contact: 'جهة اتصال',
        purchaseDate: 'تاريخ الشراء',
        expectedDelivery: 'موعد التسليم',
        totalCost: 'التكلفة الإجمالية',
        savePurchase: 'حفظ الشراء',
        saveSupplier: 'حفظ المورد',
        paymentTerms: 'شروط الدفع',
        wasteQuantity: 'كمية الفاقد',
        wasteReason: 'سبب الفاقد',
        wasteDate: 'تاريخ الفاقد',
        saveWaste: 'حفظ الفاقد',
        receipt: 'الإيصال',
        printReceipt: 'طباعة الإيصال',
        printOptions: 'خيارات الطباعة',
        print: 'طباعة',
        receiptNumber: 'رقم الإيصال #',
        date: 'التاريخ',
        time: 'الوقت',
        server: 'الخادم',
        customer: 'العميل',
        items: 'الأصناف',
        qty: 'الكمية',
        price: 'السعر',
        amount: 'المبلغ',
        deliveryFee: 'رسوم التوصيل',
        serviceCharge: 'رسوم الخدمة',
        discount: 'الخصم',
        grandTotal: 'المجموع الكلي',
        paymentMethod: 'طريقة الدفع',
        cash: 'نقداً',
        card: 'بطاقة',
        change: 'المتبقي',
        thankYou: 'شكراً لك',
        reports: 'التقارير',
        salesReport: 'تقرير المبيعات',
        dailyReport: 'التقرير اليومي',
        monthlyReport: 'التقرير الشهري',
        topItems: 'أفضل الأصناف',
        categoryReport: 'تقرير الفئات',
        exportData: 'تصدير البيانات',
        generateReport: 'إنشاء تقرير',
        totalSales: 'إجمالي المبيعات',
        totalOrders: 'إجمالي الطلبات',
        completedOrders: 'الطلبات المكتملة',
        averageOrder: 'متوسط الطلب',
        backup: 'النسخ الاحتياطي والاستعادة',
        exportBackup: 'تصدير النسخة الاحتياطية',
        importBackup: 'استيراد النسخة الاحتياطية',
        restoreData: 'استعادة البيانات',
        restaurantInfo: 'معلومات المطعم',
        restaurantName: 'اسم المطعم',
        address: 'العنوان',
        phone: 'الهاتف',
        email: 'البريد الإلكتروني',
        website: 'الموقع الإلكتروني',
        contactInfo: 'معلومات الاتصال',
        receiptSettings: 'إعدادات الإيصال',
        taxRate: 'نسبة الضريبة (%)',
        deliveryFee: 'رسوم التوصيل',
        currency: 'العملة',
        receiptFooter: 'تذييل الإيصال',
        printSettings: 'إعدادات الطباعة',
        printHeader: 'طباعة الترويسة',
        printFooter: 'طباعة التذييل',
        autoPrint: 'الطباعة التلقائية',
        receiptWidth: 'عرض الإيصال (مم)',
        fontSize: 'حجم الخط (نقطة)',
        saveSettings: 'حفظ الإعدادات',
        logoUpload: 'رفع الشعار',
        overdue: 'متأخر',
        late: 'متأخر',
        early: 'مبكر',
        'on-time': 'في الوقت المحدد'
    },

    // Initialize app
    async initApp() {
        try {
            // Load all data from API/localStorage
            await this.loadAllData();

            // Validate and clean data on startup
            this.validateAndCleanData();

            // Request notification permission
            if ('Notification' in window) {
                Notification.requestPermission();
            }

            // Start KDS auto-refresh if enabled
            if (this.kdsAutoRefresh) {
                this.startKdsAutoRefresh();
            }

            // Watch for tab changes to manage KDS auto-refresh
            this.$watch('currentTab', (newTab) => {
                if (newTab === 'kds' && this.kdsAutoRefresh) {
                    this.startKdsAutoRefresh();
                } else {
                    this.stopKdsAutoRefresh();
                }
            });

            // Set initial language from localStorage or browser
            const savedLang = localStorage.getItem('restaurant_lang');
            this.language = savedLang || 'en';
            this.direction = this.language === 'ar' ? 'rtl' : 'ltr';

            // Update translations based on language
            if (this.language === 'ar') {
                this.translations = this.arTranslations;
            }

            // Initialize real-time sync
            this.initRealtimeSync();

        } catch (error) {
            console.error('Error initializing app:', error);
        }
    },

    // Load all data from API/localStorage
    async loadAllData() {
        try {
            const extractArrayFromResponse = (source, context = 'response') => {
                if (!source) {
                    return [];
                }

                if (Array.isArray(source)) {
                    return source;
                }

                if (typeof source === 'object') {
                    for (const key of ['data', 'items', 'results', 'recipes', 'orders']) {
                        if (Array.isArray(source[key])) {
                            console.log(`Using ${context}.${key}`);
                            return source[key];
                        }
                    }

                    for (const value of Object.values(source)) {
                        if (Array.isArray(value)) {
                            return value;
                        }
                    }
                }

                return [];
            };

            // Load recipes - request a large page size to get all recipes
            console.log('Loading recipes from API...');
            const recipesResponse = await api.getRecipes({ per_page: 1000 }).catch(error => {
                console.error('Error fetching recipes from API:', error);
                return null;
            });

            console.log('Recipes API Response:', recipesResponse);
            console.log('Response success:', recipesResponse?.success);
            console.log('Response data:', recipesResponse?.data);

            const isSuccessfulResponse = (response) => {
                if (!response) {
                    return false;
                }

                const status = typeof response?.status === 'string'
                    ? response.status.toLowerCase()
                    : response?.status;

                const hasArrayData = Array.isArray(response) ||
                    Array.isArray(response?.data) ||
                    Array.isArray(response?.data?.data);

                if (hasArrayData) {
                    return true;
                }

                if (status === 'success' || status === true) {
                    return true;
                }

                if (response.success === undefined || response.success === true) {
                    return true;
                }

                return false;
            };

            if (isSuccessfulResponse(recipesResponse)) {
                // Handle paginated or arbitrary response structure: locate any array of recipes
                let recipesArray = [];

                if (Array.isArray(recipesResponse)) {
                    recipesArray = recipesResponse;
                    console.log('Using top-level array response');
                } else {
                    console.log('Attempting to extract recipes array from response structure');
                    recipesArray = extractArrayFromResponse(recipesResponse.data ?? recipesResponse, 'recipesResponse');

                    // If still empty, attempt deep inspection of nested objects
                    if ((!recipesArray || recipesArray.length === 0) && typeof recipesResponse === 'object') {
                        for (const value of Object.values(recipesResponse)) {
                            const nestedArray = extractArrayFromResponse(value, 'recipesNested');
                            if (nestedArray.length) {
                                recipesArray = nestedArray;
                                break;
                            }
                        }
                    }
                }

                console.log('Extracted recipes array length:', recipesArray.length);
                console.log('First recipe:', recipesArray[0]);

                // Convert snake_case to camelCase for frontend consistency
                // Also ensure isActive defaults to true if not explicitly set (null or undefined)
                if (recipesArray.length > 0) {
                    this.recipes = recipesArray.map(recipe => {
                        try {
                            return {
                                id: recipe.id,
                                name: recipe.name || '',
                                category: recipe.category || '',
                                price: parseFloat(recipe.price) || 0,
                                basePortions: recipe.base_portions !== undefined ? recipe.base_portions : (recipe.basePortions !== undefined ? recipe.basePortions : 4),
                                prepTime: recipe.prep_time !== undefined ? recipe.prep_time : (recipe.prepTime !== undefined ? recipe.prepTime : 0),
                                cookTime: recipe.cook_time !== undefined ? recipe.cook_time : (recipe.cookTime !== undefined ? recipe.cookTime : 0),
                                difficulty: recipe.difficulty || 'medium',
                                tags: Array.isArray(recipe.tags) ? recipe.tags : [],
                                allergens: Array.isArray(recipe.allergens) ? recipe.allergens : [],
                                ingredients: Array.isArray(recipe.ingredients) ? recipe.ingredients : [],
                                instructions: recipe.instructions || '',
                                notes: recipe.notes || '',
                                image: recipe.image || '',
                                // Convert is_active to isActive, default to true if null or undefined
                                isActive: recipe.is_active !== undefined && recipe.is_active !== null ? Boolean(recipe.is_active) : (recipe.isActive !== undefined && recipe.isActive !== null ? Boolean(recipe.isActive) : true),
                                is_active: recipe.is_active !== undefined && recipe.is_active !== null ? Boolean(recipe.is_active) : (recipe.isActive !== undefined && recipe.isActive !== null ? Boolean(recipe.isActive) : true),
                                createdAt: recipe.created_at || recipe.createdAt || null,
                                updatedAt: recipe.updated_at || recipe.updatedAt || null
                            };
                        } catch (error) {
                            console.error('Error processing recipe:', recipe, error);
                            return null;
                        }
                    }).filter(recipe => recipe !== null);

                    console.log('Converted recipes count:', this.recipes.length);

                    // Save to localStorage as backup
                    if (this.recipes.length > 0) {
                        localStorage.setItem('restaurant_recipes', JSON.stringify(this.recipes));
                        console.log('Saved recipes to localStorage:', this.recipes.length);
                    }
                } else {
                    console.warn('No recipes found in API response, checking localStorage...');
                    this.loadRecipesFromLocalStorage();
                }

                // Update recipe categories if provided by API
                const responseCategories =
                    recipesResponse?.categories ||
                    recipesResponse?.data?.categories ||
                    recipesResponse?.data?.data?.categories;

                if (Array.isArray(responseCategories) && responseCategories.length > 0) {
                    this.recipeCategories = responseCategories;
                    localStorage.setItem('restaurant_recipe_categories', JSON.stringify(this.recipeCategories));
                    console.log('Recipe categories updated from API:', this.recipeCategories);
                }
            } else {
                console.warn('API response failed or did not include usable data, loading from localStorage');
                this.loadRecipesFromLocalStorage();
            }

            console.log('Final recipes state - count:', this.recipes.length);
            if (this.recipes.length > 0) {
                console.log('Sample recipes:', this.recipes.slice(0, 3).map(r => ({ id: r.id, name: r.name, isActive: r.isActive })));
            }

            // Load orders
            console.log('Loading orders from API...');
            const ordersResponse = await api.getOrders({ per_page: 1000 }).catch(error => {
                console.error('Error fetching orders from API:', error);
                return null;
            });

            if (isSuccessfulResponse(ordersResponse)) {
                let ordersArray = [];

                if (Array.isArray(ordersResponse)) {
                    ordersArray = ordersResponse;
                } else {
                    ordersArray = extractArrayFromResponse(ordersResponse.data ?? ordersResponse, 'ordersResponse');

                    if ((!ordersArray || ordersArray.length === 0) && typeof ordersResponse === 'object') {
                        for (const value of Object.values(ordersResponse)) {
                            const nestedArray = extractArrayFromResponse(value, 'ordersNested');
                            if (nestedArray.length) {
                                ordersArray = nestedArray;
                                break;
                            }
                        }
                    }
                }

                if (ordersArray.length > 0) {
                    const normalizedOrders = ordersArray
                        .map(order => this.normalizeOrder(order))
                        .filter(order => order !== null);

                    if (normalizedOrders.length > 0) {
                        this.orders = normalizedOrders;
                        localStorage.setItem('restaurant_orders', JSON.stringify(this.orders));
                        console.log('Orders loaded from API:', this.orders.length);
                    } else {
                        console.warn('Orders response contained no valid entries after normalization, loading from localStorage');
                        this.loadOrdersFromLocalStorage();
                    }
                } else {
                    console.warn('Orders response did not include list data, loading from localStorage');
                    this.loadOrdersFromLocalStorage();
                }
            } else {
                console.warn('Orders API response unsuccessful, loading from localStorage');
                this.loadOrdersFromLocalStorage();
            }

            // Load tables
            const tablesResponse = await api.getTables();
            if (tablesResponse.success) {
                this.tables = tablesResponse.data.data || tablesResponse.data;
            } else {
                this.loadTablesFromLocalStorage();
            }

            // Load chefs
            const chefsResponse = await api.getChefs();
            if (chefsResponse.success) {
                this.chefs = chefsResponse.data.data || chefsResponse.data;
            } else {
                this.loadChefsFromLocalStorage();
            }

            // Load stations
            const stationsResponse = await api.getStations();
            if (stationsResponse.success) {
                this.stations = stationsResponse.data.data || stationsResponse.data;
            } else {
                this.loadStationsFromLocalStorage();
            }

            // Load settings
            const settingsResponse = await api.getSettings();
            if (settingsResponse.success) {
                this.settings = settingsResponse.data.data || settingsResponse.data;
            } else {
                this.loadSettingsFromLocalStorage();
            }

        } catch (error) {
            console.error('Error loading data from API:', error);
            // Fallback to localStorage
            this.loadAllFromLocalStorage();
        }
    },

    // LocalStorage fallback methods
    loadRecipesFromLocalStorage() {
        const savedRecipes = localStorage.getItem('restaurant_recipes');
        this.recipes = savedRecipes ? JSON.parse(savedRecipes) : this.getDefaultRecipes();
    },

    loadOrdersFromLocalStorage() {
        const savedOrders = localStorage.getItem('restaurant_orders');
        if (savedOrders) {
            try {
                const parsed = JSON.parse(savedOrders);
                this.orders = Array.isArray(parsed)
                    ? parsed.map(order => this.normalizeOrder(order)).filter(order => order !== null)
                    : [];
            } catch (error) {
                console.error('Error parsing saved orders:', error);
                this.orders = [];
            }
        } else {
            this.orders = [];
        }
    },

    normalizeOrder(order) {
        if (!order || typeof order !== 'object') {
            return null;
        }

        const parseTimestamp = (value) => {
            if (!value) {
                return Date.now();
            }

            if (typeof value === 'number' && Number.isFinite(value)) {
                return value;
            }

            const parsed = Date.parse(value);
            return Number.isFinite(parsed) ? parsed : Date.now();
        };

        const normalizeItems = (items) => {
            if (!Array.isArray(items)) {
                return [];
            }

            return items.map(item => ({
                id: item?.recipe_id ?? item?.recipeId ?? item?.id ?? Date.now(),
                name: item?.name || item?.recipe_name || item?.item_name || 'Item',
                price: parseFloat(item?.price ?? item?.unit_price ?? 0) || 0,
                quantity: item?.quantity ?? item?.qty ?? 1
            }));
        };

        try {
            return {
                id: order.id,
                status: order.status || 'new',
                type: order.type || order.order_type || 'dine-in',
                tableNumber: order.table_number ?? order.tableNumber ?? null,
                timestamp: parseTimestamp(order.timestamp || order.created_at || order.createdAt),
                subtotal: parseFloat(order.subtotal ?? 0) || 0,
                tax: parseFloat(order.tax ?? 0) || 0,
                total: parseFloat(order.total ?? order.amount ?? 0) || 0,
                deliveryFee: parseFloat(order.delivery_fee ?? order.deliveryFee ?? 0) || 0,
                items: normalizeItems(order.items || order.order_items || []),
                urgent: Boolean(order.urgent ?? order.is_urgent),
                urgentTime: order.urgent_time ? parseTimestamp(order.urgent_time) : (order.urgentTime || null),
                assignedChef: order.assigned_chef ?? order.assignedChef ?? null,
                assignedStation: order.assigned_station ?? order.assignedStation ?? null,
                assignedTime: order.assigned_time ? parseTimestamp(order.assigned_time) : (order.assignedTime || null),
                notes: Array.isArray(order.notes) ? order.notes : [],
                completedTime: order.completed_at ? parseTimestamp(order.completed_at) : (order.completedTime || null)
            };
        } catch (error) {
            console.error('Error normalizing order:', order, error);
            return null;
        }
    },

    loadTablesFromLocalStorage() {
        const savedTables = localStorage.getItem('restaurant_tables');
        this.tables = savedTables ? JSON.parse(savedTables) : this.getDefaultTables();
    },

    loadChefsFromLocalStorage() {
        const savedChefs = localStorage.getItem('restaurant_chefs');
        this.chefs = savedChefs ? JSON.parse(savedChefs) : this.getDefaultChefs();
    },

    loadStationsFromLocalStorage() {
        const savedStations = localStorage.getItem('restaurant_stations');
        this.stations = savedStations ? JSON.parse(savedStations) : this.getDefaultStations();
    },

    loadSettingsFromLocalStorage() {
        const savedSettings = localStorage.getItem('restaurant_settings');
        this.settings = savedSettings ? JSON.parse(savedSettings) : this.getDefaultSettings();
    },

    loadAllFromLocalStorage() {
        this.loadRecipesFromLocalStorage();
        this.loadOrdersFromLocalStorage();
        this.loadTablesFromLocalStorage();
        this.loadChefsFromLocalStorage();
        this.loadStationsFromLocalStorage();
        this.loadSettingsFromLocalStorage();
    },

    // Default data methods
    getDefaultRecipes() {
        return [
            {
                id: 1,
                name: 'Margherita Pizza',
                category: 'Pizza',
                price: 12.99,
                basePortions: 2,
                prepTime: 10,
                cookTime: 15,
                difficulty: 'medium',
                allergens: ['gluten', 'dairy'],
                tags: ['vegetarian', 'classic'],
                ingredients: [
                    { name: 'Pizza Dough', quantity: 1, unit: 'pc', notes: 'Fresh or frozen' },
                    { name: 'Tomato Sauce', quantity: 150, unit: 'g', notes: 'Homemade or store-bought' },
                    { name: 'Mozzarella', quantity: 200, unit: 'g', notes: 'Fresh mozzarella' },
                    { name: 'Basil', quantity: 5, unit: 'leaves', notes: 'Fresh basil leaves' }
                ],
                instructions: '1. Preheat oven to 250°C\n2. Roll out pizza dough\n3. Spread tomato sauce evenly\n4. Add mozzarella cheese\n5. Add fresh basil leaves\n6. Bake for 10-12 minutes until golden',
                notes: 'Classic Italian pizza with simple, fresh ingredients',
                image: '',
                isActive: true,
                createdAt: Date.now(),
                updatedAt: Date.now()
            },
            {
                id: 2,
                name: 'Caesar Salad',
                category: 'Salad',
                price: 8.99,
                basePortions: 1,
                prepTime: 15,
                cookTime: 0,
                difficulty: 'easy',
                allergens: ['gluten', 'dairy', 'eggs'],
                tags: ['healthy', 'classic'],
                ingredients: [
                    { name: 'Romaine Lettuce', quantity: 1, unit: 'head', notes: 'Fresh and crisp' },
                    { name: 'Parmesan Cheese', quantity: 50, unit: 'g', notes: 'Freshly grated' },
                    { name: 'Croutons', quantity: 100, unit: 'g', notes: 'Homemade or store-bought' },
                    { name: 'Caesar Dressing', quantity: 60, unit: 'ml', notes: 'Homemade dressing' },
                    { name: 'Black Pepper', quantity: 1, unit: 'tsp', notes: 'Freshly ground' }
                ],
                instructions: '1. Wash and chop romaine lettuce\n2. Make Caesar dressing\n3. Toss lettuce with dressing\n4. Add croutons and parmesan\n5. Season with black pepper',
                notes: 'Classic Caesar salad with homemade dressing',
                image: '',
                isActive: true,
                createdAt: Date.now(),
                updatedAt: Date.now()
            }
        ];
    },

    getDefaultTables() {
        return [
            { id: 1, number: 1, capacity: 4, status: 'available', location: 'Window', notes: '' },
            { id: 2, number: 2, capacity: 6, status: 'available', location: 'Center', notes: '' },
            { id: 3, number: 3, capacity: 2, status: 'available', location: 'Bar', notes: 'High-top table' },
            { id: 4, number: 4, capacity: 8, status: 'available', location: 'Patio', notes: 'Outdoor seating' }
        ];
    },

    getDefaultChefs() {
        return [
            { name: 'Chef Anna' },
            { name: 'Chef Ben' },
            { name: 'Chef Carlos' }
        ];
    },

    getDefaultStations() {
        return [
            { name: 'Grill' },
            { name: 'Fry' },
            { name: 'Salad' },
            { name: 'Dessert' }
        ];
    },

    getDefaultSettings() {
        return {
            taxRate: 10,
            deliveryFee: 5,
            currency: 'USD',
            restaurantName: 'Restaurant Manager',
            address: '123 Main Street, City, State 12345',
            phone: '+1 (555) 123-4567',
            email: 'info@restaurant.com',
            website: 'www.restaurant.com',
            receiptFooter: 'Thank you for dining with us!',
            logo: '',
            printLogo: true,
            printHeader: true,
            printFooter: true,
            autoPrint: false,
            receiptWidth: 80,
            fontSize: 12
        };
    },

    // Data validation and cleanup
    validateAndCleanData() {
        // Clean up orders (remove invalid ones)
        this.orders = this.orders.filter(order =>
            order && order.id && order.items && Array.isArray(order.items)
        );

        // Clean up recipes (remove invalid ones)
        this.recipes = this.recipes.filter(recipe =>
            recipe && recipe.id && recipe.name && recipe.price > 0
        );

        // Clean up tables (remove invalid ones)
        this.tables = this.tables.filter(table =>
            table && table.id && table.number
        );

        // Save cleaned data
        this.saveAllData();
    },

    // Save all data to localStorage (fallback)
    saveAllData() {
        try {
            localStorage.setItem('restaurant_recipes', JSON.stringify(this.recipes));
            localStorage.setItem('restaurant_orders', JSON.stringify(this.orders));
            localStorage.setItem('restaurant_tables', JSON.stringify(this.tables));
            localStorage.setItem('restaurant_chefs', JSON.stringify(this.chefs));
            localStorage.setItem('restaurant_stations', JSON.stringify(this.stations));
            localStorage.setItem('restaurant_settings', JSON.stringify(this.settings));
        } catch (error) {
            console.error('Error saving data to localStorage:', error);
        }
    },

    // Change language
    changeLanguage(lang) {
        this.language = lang;
        this.direction = lang === 'ar' ? 'rtl' : 'ltr';
        localStorage.setItem('restaurant_lang', lang);

        // Update translations based on language
        if (lang === 'ar') {
            this.translations = this.arTranslations;
        } else {
            // Reset to English translations
            this.translations = {
                appTitle: 'Restaurant Manager',
                pos: 'POS',
                orderType: 'Order Type',
                dineIn: 'Dine-In',
                takeaway: 'Takeaway',
                delivery: 'Delivery',
                currentOrder: 'Current Order',
                item: 'Item',
                total: 'Total',
                subtotal: 'Subtotal',
                tax: 'Tax (10%)',
                placeOrder: 'Place Order',
                emptyOrder: 'Add items to your order',
                kds: 'Kitchen Display',
                newOrders: 'New Orders',
                preparing: 'Preparing',
                ready: 'Ready',
                startPreparing: 'Start Preparing',
                markReady: 'Mark as Ready',
                complete: 'Complete',
                noNewOrders: 'No new orders',
                noPreparingOrders: 'No orders in preparation',
                noReadyOrders: 'No orders ready',
                recipes: 'Recipes',
                recipeList: 'Recipe List',
                addRecipe: 'Add Recipe',
                editRecipe: 'Edit Recipe',
                recipeName: 'Recipe Name',
                category: 'Category',
                price: 'Price',
                basePortions: 'Base Portions',
                ingredients: 'Ingredients',
                instructions: 'Instructions',
                addIngredient: 'Add Ingredient',
                saveRecipe: 'Save Recipe',
                cancel: 'Cancel',
                scaleToPortions: 'Scale to Portions',
                selectRecipe: 'Select a recipe to view details',
                noRecipes: 'No recipes found. Add your first recipe!',
                deleteRecipeConfirm: 'Are you sure you want to delete this recipe?',
                settings: 'Settings',
                taxRate: 'Tax Rate (%)',
                deliveryFee: 'Delivery Fee',
                currency: 'Currency',
                saveSettings: 'Save Settings',
                tables: 'Tables',
                tableManagement: 'Table Management',
                addTable: 'Add Table',
                tableNumber: 'Table Number',
                capacity: 'Capacity',
                status: 'Status',
                available: 'Available',
                occupied: 'Occupied',
                reserved: 'Reserved',
                selectTable: 'Select Table',
                noTables: 'No tables available',
                tableOrders: 'Table Orders',
                newOrder: 'New Order',
                viewOrders: 'View Orders',
                closeTable: 'Close Table',
                editTable: 'Edit Table',
                deleteTable: 'Delete Table',
                location: 'Location',
                notes: 'Notes',
                reservation: 'Reservation',
                customerName: 'Customer Name',
                customerPhone: 'Customer Phone',
                reservationTime: 'Reservation Time',
                makeReservation: 'Make Reservation',
                cancelReservation: 'Cancel Reservation',
                tableDetails: 'Table Details',
                tableHistory: 'Table History',
                occupancyTime: 'Occupancy Time',
                revenue: 'Revenue',
                averageOrderValue: 'Average Order Value',
                tableStatus: 'Table Status',
                cleaning: 'Cleaning',
                maintenance: 'Maintenance',
                receipt: 'Receipt',
                printReceipt: 'Print Receipt',
                printOptions: 'Print Options',
                print: 'Print',
                receiptNumber: 'Receipt #',
                date: 'Date',
                time: 'Time',
                server: 'Server',
                customer: 'Customer',
                items: 'Items',
                qty: 'Qty',
                amount: 'Amount',
                serviceCharge: 'Service Charge',
                discount: 'Discount',
                grandTotal: 'Grand Total',
                paymentMethod: 'Payment Method',
                cash: 'Cash',
                card: 'Card',
                change: 'Change',
                thankYou: 'Thank You',
                reports: 'Reports',
                salesReport: 'Sales Report',
                dailyReport: 'Daily Report',
                monthlyReport: 'Monthly Report',
                topItems: 'Top Items',
                categoryReport: 'Category Report',
                exportData: 'Export Data',
                generateReport: 'Generate Report',
                totalSales: 'Total Sales',
                totalOrders: 'Total Orders',
                completedOrders: 'Completed Orders',
                averageOrder: 'Average Order',
                backup: 'Backup & Restore',
                exportBackup: 'Export Backup',
                importBackup: 'Import Backup',
                restoreData: 'Restore Data',
                restaurantInfo: 'Restaurant Information',
                contactInfo: 'Contact Information',
                receiptSettings: 'Receipt Settings',
                printSettings: 'Print Settings',
                logoUpload: 'Upload Logo',
                receiptFooter: 'Receipt Footer',
                autoPrint: 'Auto Print',
                receiptWidth: 'Receipt Width (mm)',
                fontSize: 'Font Size (pt)'
            };
        }
    },

    // Format price with currency
    formatPrice(price) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(price);
    },

    // Format date and time
    formatDateTime(timestamp) {
        return new Date(timestamp).toLocaleTimeString();
    },

    // KDS auto-refresh methods
    startKdsAutoRefresh() {
        if (this.kdsAutoRefresh && this.currentTab === 'kds') {
            if (this.kdsRefreshTimer) {
                clearInterval(this.kdsRefreshTimer);
            }

            this.kdsRefreshTimer = setInterval(() => {
                if (this.currentTab === 'kds') {
                    this.$nextTick(() => {
                        // This will trigger a re-render
                    });
                }
            }, this.kdsRefreshInterval * 1000);
        }
    },

    stopKdsAutoRefresh() {
        if (this.kdsRefreshTimer) {
            clearInterval(this.kdsRefreshTimer);
            this.kdsRefreshTimer = null;
        }
    },

    // Real-time sync for cross-tab communication
    initRealtimeSync() {
        window.addEventListener('storage', (e) => {
            try {
                if (e.key === 'restaurant_orders') {
                    this.orders = JSON.parse(e.newValue || '[]');
                }
                if (e.key === 'restaurant_tables') {
                    this.tables = JSON.parse(e.newValue || '[]');
                }
                if (e.key === 'restaurant_chefs') {
                    this.chefs = JSON.parse(e.newValue || '[]');
                }
                if (e.key === 'restaurant_stations') {
                    this.stations = JSON.parse(e.newValue || '[]');
                }
                if (e.key === 'restaurant_recipes') {
                    this.recipes = JSON.parse(e.newValue || '[]');
                }
                if (e.key === 'restaurant_settings') {
                    this.settings = { ...this.settings, ...JSON.parse(e.newValue || '{}') };
                }
                if (e.key === 'restaurant_recipe_categories') {
                    this.recipeCategories = JSON.parse(e.newValue || '[]');
                }
            } catch (error) {
                console.error('Error syncing data:', error);
            }
        });
    }
}); 