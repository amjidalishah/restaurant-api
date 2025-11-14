// js/recipes.js
// Recipe management module for Alpine.js

import api from './api.js';

export const createRecipesModule = () => ({
    // Recipe form management
    editRecipe(recipe) {
        this.editingRecipe = recipe;
        // Convert snake_case from backend to camelCase for frontend
        this.recipeForm = {
            id: recipe.id,
            name: recipe.name || '',
            category: recipe.category || '',
            price: recipe.price || 0,
            basePortions: recipe.base_portions || recipe.basePortions || 4,
            prepTime: recipe.prep_time || recipe.prepTime || 0,
            cookTime: recipe.cook_time || recipe.cookTime || 0,
            difficulty: recipe.difficulty || 'medium',
            tags: recipe.tags || [],
            allergens: recipe.allergens || [],
            ingredients: recipe.ingredients || [{ name: '', quantity: 0, unit: '', notes: '' }],
            instructions: recipe.instructions || '',
            notes: recipe.notes || '',
            image: recipe.image || '',
            isActive: recipe.is_active !== undefined ? recipe.is_active : (recipe.isActive !== undefined ? recipe.isActive : true),
            createdAt: recipe.created_at || recipe.createdAt || null,
            updatedAt: recipe.updated_at || recipe.updatedAt || null
        };
        this.showRecipeForm = true;
    },

    async saveRecipe() {
        // Validate required fields - convert price to number and check properly
        const name = (this.recipeForm.name || '').trim();
        const category = (this.recipeForm.category || '').trim();
        const price = parseFloat(this.recipeForm.price) || 0;

        if (!name || !category || price <= 0 || isNaN(price)) {
            alert('Please fill in all required fields (name, category, price). Price must be greater than 0.');
            return;
        }

        try {
            // Convert camelCase to snake_case for backend
            const recipeData = {
                name: name,
                category: category,
                price: price,
                base_portions: this.recipeForm.basePortions || 4,
                prep_time: this.recipeForm.prepTime || 0,
                cook_time: this.recipeForm.cookTime || 0,
                difficulty: this.recipeForm.difficulty,
                tags: Array.isArray(this.recipeForm.tags) ? this.recipeForm.tags : [],
                allergens: Array.isArray(this.recipeForm.allergens) ? this.recipeForm.allergens : [],
                ingredients: Array.isArray(this.recipeForm.ingredients) ? this.recipeForm.ingredients : [{ name: '', quantity: 0, unit: '', notes: '' }],
                instructions: this.recipeForm.instructions || '',
                notes: this.recipeForm.notes || '',
                image: this.recipeForm.image || '',
                is_active: this.recipeForm.isActive !== undefined ? this.recipeForm.isActive : true
            };

            let response;
            if (this.editingRecipe) {
                // Update existing recipe
                response = await api.updateRecipe(this.recipeForm.id, recipeData);
            } else {
                // Add new recipe
                response = await api.createRecipe(recipeData);
            }

            if (response.success) {
                // Get the recipe data from response (handle both response.data and response.data.data)
                const recipeData = response.data.data || response.data;

                if (recipeData) {
                    // Update local state
                    if (this.editingRecipe) {
                        const index = this.recipes.findIndex(r => r.id === recipeData.id || r.id === this.recipeForm.id);
                        if (index !== -1) {
                            this.recipes[index] = recipeData;
                        } else {
                            // If not found, add it
                            this.recipes.push(recipeData);
                        }
                    } else {
                        // Add new recipe to the list
                        this.recipes.push(recipeData);
                    }

                    // Save to localStorage as fallback
                    localStorage.setItem('restaurant_recipes', JSON.stringify(this.recipes));
                }

                // Reload recipes from API to ensure we have the latest data
                try {
                    const recipesResponse = await api.getRecipes({ per_page: 1000 });
                    console.log('Reload recipes API Response:', recipesResponse);

                    if (recipesResponse && recipesResponse.success) {
                        // Handle paginated response structure: {success: true, data: {data: [...], ...}}
                        // The recipes array is at response.data.data
                        let recipesArray = [];

                        if (recipesResponse.data) {
                            // Check if data.data exists (paginated response)
                            if (recipesResponse.data.data && Array.isArray(recipesResponse.data.data)) {
                                recipesArray = recipesResponse.data.data;
                            }
                            // Check if data is directly an array
                            else if (Array.isArray(recipesResponse.data)) {
                                recipesArray = recipesResponse.data;
                            }
                        }

                        console.log('Reloaded recipes array:', recipesArray.length, recipesArray);

                        // Convert snake_case to camelCase for frontend consistency
                        this.recipes = recipesArray.map(recipe => ({
                            id: recipe.id,
                            name: recipe.name || '',
                            category: recipe.category || '',
                            price: parseFloat(recipe.price) || 0,
                            basePortions: recipe.base_portions || recipe.basePortions || 4,
                            prepTime: recipe.prep_time || recipe.prepTime || 0,
                            cookTime: recipe.cook_time || recipe.cookTime || 0,
                            difficulty: recipe.difficulty || 'medium',
                            tags: recipe.tags || [],
                            allergens: recipe.allergens || [],
                            ingredients: recipe.ingredients || [],
                            instructions: recipe.instructions || '',
                            notes: recipe.notes || '',
                            image: recipe.image || '',
                            isActive: recipe.is_active !== undefined ? recipe.is_active : (recipe.isActive !== undefined ? recipe.isActive : true),
                            createdAt: recipe.created_at || recipe.createdAt || null,
                            updatedAt: recipe.updated_at || recipe.updatedAt || null
                        }));

                        if (this.recipes.length > 0) {
                            localStorage.setItem('restaurant_recipes', JSON.stringify(this.recipes));
                            console.log('Saved reloaded recipes to localStorage');
                        }
                    }
                } catch (error) {
                    console.error('Error reloading recipes:', error);
                    // Continue anyway since we already updated the local state
                }

                this.showRecipeForm = false;
                this.editingRecipe = null;
                this.resetRecipeForm();
            } else {
                alert('Error saving recipe: ' + (response.message || 'Unknown error'));
            }
        } catch (error) {
            console.error('Error saving recipe:', error);
            alert('Error saving recipe. Please try again.');
        }
    },

    async deleteRecipe(id) {
        if (confirm(this.translations.deleteRecipeConfirm)) {
            try {
                const response = await api.deleteRecipe(id);
                if (response.success) {
                    this.recipes = this.recipes.filter(recipe => recipe.id !== id);
                    localStorage.setItem('restaurant_recipes', JSON.stringify(this.recipes));
                    this.selectedRecipe = null;
                } else {
                    alert('Error deleting recipe: ' + (response.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error deleting recipe:', error);
                alert('Error deleting recipe. Please try again.');
            }
        }
    },

    resetRecipeForm() {
        this.recipeForm = {
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
        };
    },

    duplicateRecipe(recipe) {
        const duplicatedRecipe = {
            ...recipe,
            id: Date.now(),
            name: recipe.name + ' (Copy)',
            createdAt: Date.now(),
            updatedAt: Date.now()
        };
        this.recipes.push(duplicatedRecipe);
        localStorage.setItem('restaurant_recipes', JSON.stringify(this.recipes));
    },

    async toggleRecipeActive(recipeId) {
        try {
            const response = await api.request('patch', `/recipes/${recipeId}/toggle-active`);
            if (response.success) {
                const recipe = this.recipes.find(r => r.id === recipeId);
                if (recipe) {
                    recipe.isActive = !recipe.isActive;
                    recipe.updatedAt = Date.now();
                    localStorage.setItem('restaurant_recipes', JSON.stringify(this.recipes));
                }
            }
        } catch (error) {
            console.error('Error toggling recipe active status:', error);
        }
    },

    // Recipe filtering and sorting
    getFilteredRecipes() {

        const hasRecipes = this.recipes && typeof this.recipes.length === 'number' && this.recipes.length > 0;

        if (!hasRecipes) {
            console.warn('Recipes array is empty or unavailable');
            return [];
        }

        // IMPORTANT: Only filter by isActive on POS tab
        // On recipes management page (currentTab === 'recipes'), show ALL recipes
        let filteredRecipes = Array.from(this.recipes); // Create a copy

        // Only apply isActive filter if we're on the POS tab
        if (this.currentTab === 'pos') {
            console.log('Filtering by isActive (POS tab)');
            filteredRecipes = filteredRecipes.filter(recipe => {
                const isActive = recipe.isActive !== undefined ? recipe.isActive : (recipe.is_active !== undefined ? recipe.is_active : true);
                const shouldInclude = isActive === true;
                if (!shouldInclude) {
                    console.log(`Excluding recipe ${recipe.name} - isActive: ${isActive}`);
                }
                return shouldInclude;
            });
            console.log(`After isActive filter (POS): ${filteredRecipes.length} recipes`);
        } else {
            console.log(`Not filtering by isActive (currentTab: ${this.currentTab}) - showing all ${filteredRecipes.length} recipes`);
        }

        // Determine which search term to use based on current tab
        const searchTerm = this.currentTab === 'pos' ? this.posSearchTerm : this.recipeSearchTerm;
        const filterCategory = this.currentTab === 'pos' ? this.posFilterCategory : this.recipeFilterCategory;
        const sortBy = this.currentTab === 'pos' ? this.posSortBy : this.recipeSortBy;
        const activeCategory = this.currentTab === 'pos' ? this.posActiveCategory : 'all';
        const quickFilter = this.currentTab === 'pos' ? this.posQuickFilter : 'all';

        console.log('Filter values:', { searchTerm, filterCategory, activeCategory, quickFilter, currentTab: this.currentTab });

        // Apply search filter
        if (searchTerm && searchTerm.trim()) {
            const searchLower = searchTerm.toLowerCase().trim();
            filteredRecipes = filteredRecipes.filter(recipe => {
                try {
                    return recipe.name.toLowerCase().includes(searchLower) ||
                        recipe.category.toLowerCase().includes(searchLower) ||
                        (recipe.tags && Array.isArray(recipe.tags) && recipe.tags.some(tag => tag && tag.toLowerCase().includes(searchLower))) ||
                        (recipe.ingredients && Array.isArray(recipe.ingredients) && recipe.ingredients.some(ing => ing && ing.name && ing.name.toLowerCase().includes(searchLower)));
                } catch (error) {
                    console.error('Error filtering recipe:', recipe, error);
                    return false;
                }
            });
            console.log('After search filter:', filteredRecipes.length);
        }

        // Apply category filter
        if (filterCategory && filterCategory !== 'all') {
            filteredRecipes = filteredRecipes.filter(recipe =>
                recipe.category === filterCategory
            );
            console.log('After category filter:', filteredRecipes.length);
        }

        // Apply active category filter (for POS tabs only - should not affect recipes tab)
        if (this.currentTab === 'pos' && activeCategory && activeCategory !== 'all') {
            filteredRecipes = filteredRecipes.filter(recipe =>
                recipe.category === activeCategory
            );
            console.log('After activeCategory filter:', filteredRecipes.length);
        }

        // Apply quick filters (POS only)
        if (this.currentTab === 'pos' && quickFilter !== 'all') {
            switch (quickFilter) {
                case 'popular':
                    filteredRecipes = filteredRecipes.filter(recipe =>
                        (recipe.tags && recipe.tags.includes('popular')) ||
                        recipe.price > 15
                    );
                    break;
                case 'vegetarian':
                    filteredRecipes = filteredRecipes.filter(recipe =>
                        recipe.tags && recipe.tags.includes('vegetarian')
                    );
                    break;
                case 'spicy':
                    filteredRecipes = filteredRecipes.filter(recipe =>
                        recipe.tags && recipe.tags.includes('spicy')
                    );
                    break;
                case 'healthy':
                    filteredRecipes = filteredRecipes.filter(recipe =>
                        recipe.tags && recipe.tags.includes('healthy')
                    );
                    break;
            }
        }

        // Apply sorting
        try {
            filteredRecipes.sort((a, b) => {
                let aValue, bValue;

                switch (sortBy) {
                    case 'name':
                        aValue = (a.name || '').toLowerCase();
                        bValue = (b.name || '').toLowerCase();
                        break;
                    case 'price':
                        aValue = parseFloat(a.price) || 0;
                        bValue = parseFloat(b.price) || 0;
                        break;
                    case 'popularity':
                        aValue = (a.tags && a.tags.includes('popular')) ? 1 : 0;
                        bValue = (b.tags && b.tags.includes('popular')) ? 1 : 0;
                        if (aValue === bValue) {
                            aValue = parseFloat(a.price) || 0;
                            bValue = parseFloat(b.price) || 0;
                        }
                        break;
                    case 'category':
                        aValue = (a.category || '').toLowerCase();
                        bValue = (b.category || '').toLowerCase();
                        break;
                    case 'date':
                        aValue = a.createdAt || 0;
                        bValue = b.createdAt || 0;
                        break;
                    default:
                        aValue = (a.name || '').toLowerCase();
                        bValue = (b.name || '').toLowerCase();
                }

                const sortOrder = this.currentTab === 'pos' ? 'asc' : (this.recipeSortOrder || 'asc');
                if (sortOrder === 'desc') {
                    return aValue < bValue ? 1 : -1;
                } else {
                    return aValue > bValue ? 1 : -1;
                }
            });
        } catch (error) {
            console.error('Error sorting recipes:', error);
        }

        if (filteredRecipes.length === 0 && hasRecipes) {
            console.warn('Filtered recipes became empty while original list has data. Returning all recipes as fallback.');
            filteredRecipes = Array.from(this.recipes);
        }

        console.log('Final filtered recipes count:', filteredRecipes.length);
        return filteredRecipes;
    },

    // Recipe category management
    addRecipeCategory(category) {
        if (category && !this.recipeCategories.includes(category)) {
            this.recipeCategories.push(category);
            localStorage.setItem('restaurant_recipe_categories', JSON.stringify(this.recipeCategories));
        }
    },

    removeRecipeCategory(category) {
        const index = this.recipeCategories.indexOf(category);
        if (index > -1) {
            this.recipeCategories.splice(index, 1);
            localStorage.setItem('restaurant_recipe_categories', JSON.stringify(this.recipeCategories));
        }
    },

    // Recipe scaling
    calculateScaledQuantity(quantity, portions, basePortions) {
        return (quantity * portions / basePortions).toFixed(2);
    },

    // Recipe form helpers
    addIngredient() {
        this.recipeForm.ingredients.push({ name: '', quantity: 0, unit: '', notes: '' });
    },

    removeIngredient(index) {
        this.recipeForm.ingredients.splice(index, 1);
    },

    addTag() {
        const tagInput = document.getElementById('tagInput');
        if (tagInput && tagInput.value.trim()) {
            if (!this.recipeForm.tags.includes(tagInput.value.trim())) {
                this.recipeForm.tags.push(tagInput.value.trim());
            }
            tagInput.value = '';
        }
    },

    removeTag(tag) {
        this.recipeForm.tags = this.recipeForm.tags.filter(t => t !== tag);
    },

    addAllergen() {
        const allergenInput = document.getElementById('allergenInput');
        if (allergenInput && allergenInput.value.trim()) {
            if (!this.recipeForm.allergens.includes(allergenInput.value.trim())) {
                this.recipeForm.allergens.push(allergenInput.value.trim());
            }
            allergenInput.value = '';
        }
    },

    removeAllergen(allergen) {
        this.recipeForm.allergens = this.recipeForm.allergens.filter(a => a !== allergen);
    }
}); 