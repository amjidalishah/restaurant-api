// js/reports.js
// Reports module powered by Laravel API endpoints

import api from './api.js';

const now = new Date();
const isoToday = now.toISOString().split('T')[0];
const defaultMonth = now.getMonth() + 1;
const defaultYear = now.getFullYear();

const getDefaultDailyReport = () => ({
    date: isoToday,
    totalSales: 0,
    totalOrders: 0,
    completedOrders: 0,
    averageOrder: 0,
    completionRate: 0,
    totalTax: 0,
    totalDeliveryFees: 0,
    netSales: 0
});

const getDefaultMonthlyReport = () => ({
    year: defaultYear,
    month: defaultMonth,
    totalSales: 0,
    totalOrders: 0,
    completedOrders: 0,
    averageOrder: 0,
    completionRate: 0,
    totalTax: 0,
    totalDeliveryFees: 0,
    netSales: 0,
    averageDailySales: 0
});

export const createReportsModule = () => ({
    reportsModuleInitialized: false,
    reportFilters: {
        dailyDate: isoToday,
        monthlyMonth: defaultMonth,
        monthlyYear: defaultYear,
        topItemsPeriod: 'month',
        categoryPeriod: 'month',
        orderTypePeriod: 'month',
        hourlyDate: isoToday,
        topItemsLimit: 5,
        exportType: 'orders',
        exportPeriod: 'month'
    },
    reportLoading: false,
    reportsError: null,
    reportsLastUpdated: null,

    async initReportsModule() {
        if (this.reportsModuleInitialized) {
            if (this.currentTab === 'reports' && !this.reportLoading) {
                await this.loadReports();
            }
            return;
        }

        this.reportsModuleInitialized = true;

        // Ensure reports object has expected structure
        if (!this.reports || typeof this.reports !== 'object') {
            this.reports = {};
        }
        this.reports.dailySales = this.reports.dailySales || getDefaultDailyReport();
        this.reports.monthlySales = this.reports.monthlySales || getDefaultMonthlyReport();
        this.reports.topItems = Array.isArray(this.reports.topItems) ? this.reports.topItems : [];
        this.reports.categorySales = Array.isArray(this.reports.categorySales) ? this.reports.categorySales : [];
        this.reports.orderTypeSales = Array.isArray(this.reports.orderTypeSales) ? this.reports.orderTypeSales : [];
        this.reports.hourlySales = Array.isArray(this.reports.hourlySales) ? this.reports.hourlySales : [];

        if (typeof this.$watch === 'function') {
            this.$watch('currentTab', (tab) => {
                if (tab === 'reports') {
                    this.loadReports().catch(() => {});
                }
            });
        }

        if (this.currentTab === 'reports') {
            await this.loadReports();
        }
    },

    async generateReports() {
        await this.loadReports();
    },

    async loadReports() {
        this.reportLoading = true;
        this.reportsError = null;

        try {
            const [
                dailyRes,
                monthlyRes,
                topItemsRes,
                categoryRes,
                orderTypeRes,
                hourlyRes
            ] = await Promise.all([
                api.getDailySalesReport({ date: this.reportFilters.dailyDate }),
                api.getMonthlySalesReport({
                    year: this.reportFilters.monthlyYear,
                    month: this.reportFilters.monthlyMonth
                }),
                api.getTopItemsReport({
                    period: this.reportFilters.topItemsPeriod,
                    limit: this.reportFilters.topItemsLimit
                }),
                api.getCategorySalesReport({
                    period: this.reportFilters.categoryPeriod
                }),
                api.getOrderTypeSalesReport({
                    period: this.reportFilters.orderTypePeriod
                }),
                api.getHourlySalesReport({
                    date: this.reportFilters.hourlyDate
                })
            ]);

            this.reports.dailySales = this.normalizeDailyReport(this.extractData(dailyRes));
            this.reports.monthlySales = this.normalizeMonthlyReport(this.extractData(monthlyRes));
            this.reports.topItems = this.normalizeTopItems(this.extractData(topItemsRes));
            this.reports.categorySales = this.normalizeCategorySales(this.extractData(categoryRes));
            this.reports.orderTypeSales = this.normalizeOrderTypeSales(this.extractData(orderTypeRes));
            this.reports.hourlySales = this.normalizeHourlySales(this.extractData(hourlyRes));

            this.reportsLastUpdated = new Date().toISOString();
        } catch (error) {
            console.error('Error loading reports:', error);
            this.reportsError = error?.message || 'Failed to load reports from the server.';
        } finally {
            this.reportLoading = false;
        }
    },

    async exportData() {
        try {
            const params = {
                type: this.reportFilters.exportType,
                period: this.reportFilters.exportPeriod,
                format: 'json'
            };
            const exportRes = await api.exportReports(params);
            const exportData = this.extractData(exportRes);
            const blob = new Blob([JSON.stringify(exportData, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            const filename = `reports_${params.type}_${new Date().toISOString().split('T')[0]}.json`;
            link.href = url;
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        } catch (error) {
            console.error('Error exporting reports:', error);
            this.reportsError = error?.message || 'Failed to export reports.';
        }
    },

    setReportFilter(key, value) {
        if (key in this.reportFilters) {
            this.reportFilters[key] = value;
        }
    },

    extractData(response) {
        if (!response) {
            return null;
        }

        if (response.data && response.data.data !== undefined) {
            return response.data.data;
        }

        if (response.data !== undefined) {
            return response.data;
        }

        return response;
    },

    normalizeDailyReport(data) {
        if (!data) {
            return getDefaultDailyReport();
        }

        return {
            date: data.date || this.reportFilters.dailyDate,
            totalSales: Number(data.total_sales ?? data.totalSales ?? 0),
            totalOrders: Number(data.total_orders ?? data.totalOrders ?? 0),
            completedOrders: Number(data.completed_orders ?? data.completedOrders ?? 0),
            averageOrder: Number(data.average_order ?? data.averageOrder ?? 0),
            completionRate: Number(data.completion_rate ?? data.completionRate ?? 0),
            totalTax: Number(data.total_tax ?? 0),
            totalDeliveryFees: Number(data.total_delivery_fees ?? 0),
            netSales: Number(data.net_sales ?? 0)
        };
    },

    normalizeMonthlyReport(data) {
        if (!data) {
            return getDefaultMonthlyReport();
        }

        return {
            year: Number(data.year ?? this.reportFilters.monthlyYear),
            month: Number(data.month ?? this.reportFilters.monthlyMonth),
            totalSales: Number(data.total_sales ?? data.totalSales ?? 0),
            totalOrders: Number(data.total_orders ?? data.totalOrders ?? 0),
            completedOrders: Number(data.completed_orders ?? data.completedOrders ?? 0),
            averageOrder: Number(data.average_order ?? data.averageOrder ?? 0),
            completionRate: Number(data.completion_rate ?? data.completionRate ?? 0),
            totalTax: Number(data.total_tax ?? 0),
            totalDeliveryFees: Number(data.total_delivery_fees ?? 0),
            netSales: Number(data.net_sales ?? 0),
            averageDailySales: Number(data.average_daily_sales ?? data.averageDailySales ?? 0)
        };
    },

    normalizeTopItems(items) {
        if (!Array.isArray(items)) {
            return [];
        }

        return items.map(item => {
            const quantity = Number(item.total_quantity ?? item.quantity ?? 0);
            const revenue = Number(item.total_revenue ?? item.revenue ?? 0);
            const avg = quantity > 0 ? revenue / quantity : 0;

            return {
                id: item.id,
                name: item.name || 'Unknown Item',
                category: item.category || '',
                quantity,
                revenue,
                averagePrice: Number(item.average_price ?? item.averagePrice ?? avg),
            };
        });
    },

    normalizeCategorySales(items) {
        if (!Array.isArray(items)) {
            return [];
        }

        return items.map(item => {
            const revenue = Number(item.total_revenue ?? item.sales ?? 0);
            const orders = Number(item.order_count ?? item.orders ?? 0);

            return {
                category: item.category || 'Uncategorized',
                sales: revenue,
                quantity: Number(item.total_quantity ?? item.quantity ?? 0),
                averageOrderValue: orders > 0 ? revenue / orders : 0
            };
        });
    },

    normalizeOrderTypeSales(items) {
        if (!Array.isArray(items)) {
            return [];
        }

        return items.map(item => ({
            type: (item.type || 'unknown').replace('_', '-'),
            orders: Number(item.orders ?? item.total_orders ?? 0),
            sales: Number(item.sales ?? item.total_sales ?? 0),
            averageOrder: Number(item.average_order ?? item.averageOrder ?? 0)
        }));
    },

    normalizeHourlySales(items) {
        if (!Array.isArray(items)) {
            return [];
        }

        return items.map(item => ({
            hour: Number(item.hour ?? 0),
            orders: Number(item.orders ?? item.total_orders ?? 0),
            sales: Number(item.sales ?? item.total_sales ?? 0),
            averageOrder: Number(item.average_order ?? item.averageOrder ?? 0)
        }));
    }
});

