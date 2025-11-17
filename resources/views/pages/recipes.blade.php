<div class="bg-white rounded-lg shadow-lg p-6" x-init="async () => { if (recipes.length === 0) { await loadAllData(); } }">
    <h2 class="text-2xl font-bold mb-6 text-primary flex items-center gap-2">
        <i class="fas fa-book"></i>
        <span x-text="translations.recipes"></span>
    </h2>

            <!-- Recipe Controls -->
            <div class="flex flex-wrap gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                <div class="flex-1 min-w-64">
                    <input type="text" x-model="recipeSearchTerm" placeholder="Search recipes..."
                           class="w-full border rounded-lg px-3 py-2">
                </div>
                <select x-model="recipeFilterCategory" class="border rounded-lg px-3 py-2">
                    <option value="all">All Categories</option>
                    <template x-for="category in recipeCategories" :key="category">
                        <option :value="category" x-text="category"></option>
                    </template>
                </select>
                <select x-model="recipeSortBy" class="border rounded-lg px-3 py-2">
                    <option value="name">Sort by Name</option>
                    <option value="price">Sort by Price</option>
                    <option value="category">Sort by Category</option>
                    <option value="date">Sort by Date</option>
                </select>
                <button @click="recipeSortOrder = recipeSortOrder === 'asc' ? 'desc' : 'asc'"
                        class="border rounded-lg px-3 py-2 hover:bg-gray-100 transition">
                    <i class="fas" :class="recipeSortOrder === 'asc' ? 'fa-sort-up' : 'fa-sort-down'"></i>
                </button>
                <button @click="showRecipeForm = true; editingRecipe = null; resetRecipeForm()"
                        class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
                                <i class="fas fa-plus mr-1"></i>
                                <span x-text="translations.addRecipe"></span>
                            </button>
                        </div>
                        
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recipe List -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h3 class="font-bold mb-4" x-text="translations.recipeList"></h3>
                        
                        <div class="space-y-2 max-h-96 overflow-y-auto">
                            <template x-for="recipe in getFilteredRecipes()" :key="recipe.id">
                                <div class="bg-white border rounded-lg p-3 cursor-pointer hover:bg-gray-50 transition"
                                     @click="selectedRecipe = recipe; showRecipeForm = false">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex-1">
                                        <div class="font-bold" x-text="recipe.name"></div>
                                        <div class="text-sm text-gray-600" x-text="recipe.category"></div>
                                            <div class="text-sm font-bold text-primary" x-text="formatPrice(recipe.price)"></div>
                                    </div>
                                        <div class="flex gap-1" @click.stop>
                                            <button @click="duplicateRecipe(recipe)"
                                                    class="text-blue-500 hover:text-blue-700" title="Duplicate">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        <button @click="editRecipe(recipe)"
                                                    class="text-green-500 hover:text-green-700" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                            <button @click="toggleRecipeActive(recipe.id)"
                                                    class="text-yellow-500 hover:text-yellow-700" title="Toggle Active">
                                                <i class="fas" :class="recipe.isActive ? 'fa-eye' : 'fa-eye-slash'"></i>
                                            </button>
                                        <button @click="deleteRecipe(recipe.id)"
                                                    class="text-red-500 hover:text-red-700" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        <span x-text="'Prep: ' + recipe.prepTime + 'm'"></span>
                                        <span x-show="recipe.cookTime > 0" x-text="' | Cook: ' + recipe.cookTime + 'm'"></span>
                                        <span x-text="' | ' + recipe.difficulty"></span>
                                    </div>
                                    <div x-show="recipe.tags && recipe.tags.length > 0" class="mt-2">
                                        <template x-for="tag in (recipe.tags || [])" :key="tag">
                                            <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-1 mb-1" x-text="tag"></span>
                                        </template>
                                    </div>
                                </div>
                            </template>
                            <template x-if="getFilteredRecipes().length === 0">
                                <div class="text-center text-gray-500 py-8">
                                    <i class="fas fa-search text-2xl mb-2"></i>
                                    <p x-text="'No recipes found matching your criteria'"></p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                
                <!-- Recipe Form/Details -->
                <div class="lg:col-span-2">
                    <div x-show="showRecipeForm" class="bg-gray-50 rounded-lg p-6">
                        <h3 class="font-bold mb-4" x-text="editingRecipe ? translations.editRecipe : translations.addRecipe"></h3>
                        
                        <form @submit.prevent="saveRecipe">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1" x-text="translations.recipeName"></label>
                                    <input type="text" x-model="recipeForm.name" required
                                           class="w-full border rounded-lg px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1" x-text="translations.category"></label>
                                    <select x-model="recipeForm.category" required class="w-full border rounded-lg px-3 py-2">
                                        <option value="">Select Category</option>
                                        <template x-for="category in recipeCategories" :key="category">
                                            <option :value="category" x-text="category"></option>
                                        </template>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1" x-text="translations.price"></label>
                                    <input type="number" x-model="recipeForm.price" step="0.01" min="0" required
                                           class="w-full border rounded-lg px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1" x-text="translations.basePortions"></label>
                                    <input type="number" x-model="recipeForm.basePortions" min="1" required
                                           class="w-full border rounded-lg px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Prep Time (minutes)</label>
                                    <input type="number" x-model="recipeForm.prepTime" min="0" required
                                           class="w-full border rounded-lg px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Cook Time (minutes)</label>
                                    <input type="number" x-model="recipeForm.cookTime" min="0" required
                                           class="w-full border rounded-lg px-3 py-2">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Difficulty</label>
                                    <select x-model="recipeForm.difficulty" class="w-full border rounded-lg px-3 py-2">
                                        <option value="easy">Easy</option>
                                        <option value="medium">Medium</option>
                                        <option value="hard">Hard</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Active</label>
                                    <div class="flex items-center mt-2">
                                        <input type="checkbox" x-model="recipeForm.isActive" class="mr-2">
                                        <span class="text-sm">Available for ordering</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Image Upload -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Recipe Image</label>
                                <div class="space-y-2">
                                    <!-- Image Preview -->
                                    <div x-show="recipeForm.image" class="mb-2">
                                        <img :src="recipeForm.image" 
                                             alt="Recipe preview" 
                                             class="w-32 h-32 object-cover rounded-lg border border-gray-300">
                                    </div>
                                    <!-- File Input -->
                                    <input type="file" 
                                           id="recipe-image-input"
                                           accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                           @change="handleImageChange($event)"
                                           class="w-full border rounded-lg px-3 py-2">
                                    <p class="text-xs text-gray-500">Accepted formats: JPEG, PNG, JPG, GIF, WEBP (Max: 2MB)</p>
                                    <!-- Remove Image Button -->
                                    <button type="button" 
                                            x-show="recipeForm.image"
                                            @click="removeImage()"
                                            class="text-sm text-red-500 hover:text-red-700">
                                        <i class="fas fa-times mr-1"></i>Remove Image
                                    </button>
                                </div>
                            </div>
                          
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1" x-text="translations.ingredients"></label>
                                <div class="space-y-2">
                                    <template x-for="(ingredient, index) in recipeForm.ingredients" :key="index">
                                        <div class="grid grid-cols-12 gap-2">
                                            <input type="text" x-model="ingredient.name" placeholder="Ingredient"
                                                   class="col-span-5 border rounded-lg px-3 py-2">
                                            <input type="number" x-model="ingredient.quantity" step="0.01" min="0" placeholder="Qty"
                                                   class="col-span-2 border rounded-lg px-3 py-2">
                                            <input type="text" x-model="ingredient.unit" placeholder="Unit"
                                                   class="col-span-2 border rounded-lg px-3 py-2">
                                            <input type="text" x-model="ingredient.notes" placeholder="Notes"
                                                   class="col-span-2 border rounded-lg px-3 py-2">
                                            <button type="button" @click="recipeForm.ingredients.splice(index, 1)"
                                                    class="col-span-1 text-red-500 hover:text-red-700">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                                <button type="button" @click="recipeForm.ingredients.push({name: '', quantity: 0, unit: '', notes: ''})"
                                        class="mt-2 text-sm text-primary">
                                    <i class="fas fa-plus mr-1"></i>
                                    <span x-text="translations.addIngredient"></span>
                                </button>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Tags (comma-separated)</label>
                                <input type="text" x-model="(recipeForm.tags || []).join(', ')" placeholder="vegetarian, spicy, gluten-free"
                                       class="w-full border rounded-lg px-3 py-2"
                                       @input="recipeForm.tags = $event.target.value.split(',').map(tag => tag.trim()).filter(tag => tag)">
                            </div>
                            
                            <div class="mb-6">
                                <label class="block text-sm font-medium mb-1" x-text="translations.instructions"></label>
                                <textarea x-model="recipeForm.instructions" rows="4"
                                          class="w-full border rounded-lg px-3 py-2"></textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium mb-1">Notes</label>
                                <textarea x-model="recipeForm.notes" rows="2"
                                          class="w-full border rounded-lg px-3 py-2"></textarea>
                            </div>
                            
                            <div class="flex justify-end gap-3">
                                <button type="button" @click="showRecipeForm = false"
                                        class="border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                                    <span x-text="translations.cancel"></span>
                                </button>
                                <button type="submit" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-teal-700 transition">
                                    <span x-text="translations.saveRecipe"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Enhanced Recipe Details -->
                    <div x-show="!showRecipeForm && selectedRecipe" class="bg-gray-50 rounded-lg p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="font-bold text-xl" x-text="selectedRecipe?.name || ''"></h3>
                            <div class="flex gap-2">
                                <button @click="editRecipe(selectedRecipe)"
                                        class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600 transition">
                                    <i class="fas fa-edit mr-1"></i>Edit
                                </button>
                                <button @click="duplicateRecipe(selectedRecipe)"
                                        class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600 transition">
                                    <i class="fas fa-copy mr-1"></i>Duplicate
                                </button>
                            </div>
                        </div>
                        
                        <!-- Recipe Image -->
                        <div x-show="selectedRecipe?.image" class="mb-6">
                            <img :src="selectedRecipe.image ? (selectedRecipe.image.startsWith('http') || selectedRecipe.image.startsWith('/') ? selectedRecipe.image : '/' + selectedRecipe.image) : ''" 
                                 :alt="selectedRecipe?.name"
                                 class="w-full max-w-md h-64 object-cover rounded-lg border border-gray-300">
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-white p-3 rounded-lg">
                                <div class="text-sm text-gray-600">Category</div>
                                <div class="font-bold" x-text="selectedRecipe?.category"></div>
                            </div>
                            <div class="bg-white p-3 rounded-lg">
                                <div class="text-sm text-gray-600">Price</div>
                                <div class="font-bold text-primary" x-text="formatPrice(selectedRecipe?.price || 0)"></div>
                            </div>
                            <div class="bg-white p-3 rounded-lg">
                                <div class="text-sm text-gray-600">Difficulty</div>
                                <div class="font-bold capitalize" x-text="selectedRecipe?.difficulty"></div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-bold mb-3">Ingredients</h4>
                            <div class="bg-white border rounded-lg p-4">
                                    <template x-for="ingredient in (selectedRecipe?.ingredients || [])" :key="ingredient.name">
                                    <div class="flex justify-between py-2 border-b last:border-b-0">
                                        <div>
                                                <div class="font-medium" x-text="ingredient.name || 'Unknown'"></div>
                                                <div x-show="ingredient.notes" class="text-xs text-gray-500" x-text="ingredient.notes"></div>
                                            </div>
                                            <div class="text-right">
                                                <div class="font-bold" x-text="(ingredient.quantity || 0) + ' ' + (ingredient.unit || '')"></div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                        
                        <div>
                                <h4 class="font-bold mb-3">Instructions</h4>
                            <div class="bg-white border rounded-lg p-4 whitespace-pre-line" 
                                     x-text="selectedRecipe?.instructions || 'No instructions available'"></div>
                            </div>
                        </div>
                        
                        <div x-show="selectedRecipe?.tags && selectedRecipe.tags.length > 0" class="mt-4">
                            <h4 class="font-bold mb-2">Tags</h4>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="tag in (selectedRecipe?.tags || [])" :key="tag">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm" x-text="tag"></span>
                                </template>
                            </div>
                        </div>
                        
                        <div x-show="selectedRecipe?.notes" class="mt-4">
                            <h4 class="font-bold mb-2">Notes</h4>
                            <div class="bg-white border rounded-lg p-4" x-text="selectedRecipe?.notes || ''"></div>
                        </div>
                    </div>
                    
                    <div x-show="!showRecipeForm && !selectedRecipe" class="text-center py-12 text-gray-500">
                        <i class="fas fa-book-open text-4xl mb-4"></i>
                        <p x-text="translations.selectRecipe"></p>
                    </div>
                </div>
            </div>
        </div>

