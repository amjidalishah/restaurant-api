// api.js
// Centralized API utility for Restaurant POS (Database Only Version)

const metaBase = document.head.querySelector('meta[name="api-base-url"]')?.getAttribute('content');
const API_BASE = (import.meta.env.VITE_API_BASE_URL ?? metaBase ?? '/api').replace(/\/+$/, '');

class ApiClient {
    constructor() {
        this.defaultHeaders = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        };
    }

    async request(method, url, data = null, options = {}) {
        try {
            const fetchOptions = {
                method: method.toUpperCase(),
                headers: { ...this.defaultHeaders, ...options.headers },
            };

            if (data) {
                fetchOptions.body = JSON.stringify(data);
            }

            const response = await fetch(`${API_BASE}${url}`, fetchOptions);
            const result = await response.json().catch(() => ({}));

            if (!response.ok) {
                throw new Error(result.message || `API Error: ${response.statusText}`);
            }

            return result;
        } catch (error) {
            console.error(`❌ API ${method.toUpperCase()} ${url} failed:`, error);
            throw error;
        }
    }

    // ==============================
    // CRUD METHODS (Recipes)
    // ==============================
    getRecipes(params = {}) {
        const query = new URLSearchParams(params).toString();
        return this.request('get', `/recipes${query ? '?' + query : ''}`);
    }

    getRecipe(id) {
        return this.request('get', `/recipes/${id}`);
    }

    createRecipe(data) {
        return this.request('post', '/recipes', data);
    }

    updateRecipe(id, data) {
        return this.request('put', `/recipes/${id}`, data);
    }

    deleteRecipe(id) {
        return this.request('delete', `/recipes/${id}`);
    }

    // ==============================
    // CRUD METHODS (Orders)
    // ==============================
    getOrders(params = {}) {
        const query = new URLSearchParams(params).toString();
        return this.request('get', `/orders${query ? '?' + query : ''}`);
    }

    getOrder(id) {
        return this.request('get', `/orders/${id}`);
    }

    createOrder(data) {
        return this.request('post', '/orders', data);
    }

    updateOrder(id, data) {
        return this.request('put', `/orders/${id}`, data);
    }

    deleteOrder(id) {
        return this.request('delete', `/orders/${id}`);
    }

    // ==============================
    // CRUD METHODS (Tables)
    // ==============================
    getTables(params = {}) {
        const query = new URLSearchParams(params).toString();
        return this.request('get', `/tables${query ? '?' + query : ''}`);
    }

    getTable(id) {
        return this.request('get', `/tables/${id}`);
    }

    createTable(data) {
        return this.request('post', '/tables', data);
    }

    updateTable(id, data) {
        return this.request('put', `/tables/${id}`, data);
    }

    deleteTable(id) {
        return this.request('delete', `/tables/${id}`);
    }

    // ==============================
    // CRUD METHODS (Chefs)
    // ==============================
    getChefs(params = {}) {
        const query = new URLSearchParams(params).toString();
        return this.request('get', `/chefs${query ? '?' + query : ''}`);
    }

    getChef(id) {
        return this.request('get', `/chefs/${id}`);
    }

    createChef(data) {
        return this.request('post', '/chefs', data);
    }

    updateChef(id, data) {
        return this.request('put', `/chefs/${id}`, data);
    }

    deleteChef(id) {
        return this.request('delete', `/chefs/${id}`);
    }

    // ==============================
    // CRUD METHODS (Stations)
    // ==============================
    getStations(params = {}) {
        const query = new URLSearchParams(params).toString();
        return this.request('get', `/stations${query ? '?' + query : ''}`);
    }

    getStation(id) {
        return this.request('get', `/stations/${id}`);
    }

    createStation(data) {
        return this.request('post', '/stations', data);
    }

    updateStation(id, data) {
        return this.request('put', `/stations/${id}`, data);
    }

    deleteStation(id) {
        return this.request('delete', `/stations/${id}`);
    }

    // ==============================
    // SETTINGS
    // ==============================
    getSettings() {
        return this.request('get', '/settings');
    }

    updateSettings(data) {
        return this.request('post', '/settings/restaurant', data);
    }

    // ==============================
    // REPORTS
    // ==============================
    getDailySalesReport(params = {}) {
        const query = new URLSearchParams(params).toString();
        return this.request('get', `/reports/daily-sales${query ? `?${query}` : ''}`);
    }

    getMonthlySalesReport(params = {}) {
        const query = new URLSearchParams(params).toString();
        return this.request('get', `/reports/monthly-sales${query ? `?${query}` : ''}`);
    }

    getTopItemsReport(params = {}) {
        const query = new URLSearchParams(params).toString();
        return this.request('get', `/reports/top-items${query ? `?${query}` : ''}`);
    }

    getCategorySalesReport(params = {}) {
        const query = new URLSearchParams(params).toString();
        return this.request('get', `/reports/category-sales${query ? `?${query}` : ''}`);
    }

    getOrderTypeSalesReport(params = {}) {
        const query = new URLSearchParams(params).toString();
        return this.request('get', `/reports/order-type-sales${query ? `?${query}` : ''}`);
    }

    getHourlySalesReport(params = {}) {
        const query = new URLSearchParams(params).toString();
        return this.request('get', `/reports/hourly-sales${query ? `?${query}` : ''}`);
    }

    exportReports(params = {}) {
        const query = new URLSearchParams(params).toString();
        return this.request('get', `/reports/export${query ? `?${query}` : ''}`);
    }

    // ==============================
    // HEALTH CHECK (Optional)
    // ==============================
    async healthCheck() {
        try {
            const response = await fetch(`${API_BASE}/health`);
            return response.ok;
        } catch {
            return false;
        }
    }
}

// Export singleton instance
const api = new ApiClient();
export default api;
