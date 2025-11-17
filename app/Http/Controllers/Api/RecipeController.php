<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Recipe::query();

        // Search functionality
        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Category filter
        if ($request->has('category') && $request->category !== 'all') {
            $query->byCategory($request->category);
        }

        // Active filter
        if ($request->has('active')) {
            $query->active();
        }

        // Sort functionality
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        
        switch ($sortBy) {
            case 'price':
                $query->orderBy('price', $sortOrder);
                break;
            case 'category':
                $query->orderBy('category', $sortOrder);
                break;
            case 'date':
                $query->orderBy('created_at', $sortOrder);
                break;
            default:
                $query->orderBy('name', $sortOrder);
        }

        $recipes = $query->paginate($request->get('per_page', 15));

        $definedCategories = collect(config('recipes.categories', []));
        $dbCategories = Recipe::distinct()->pluck('category')->filter();
        $categories = $definedCategories
            ->merge($dbCategories)
            ->unique()
            ->values();

        return response()->json([
            'success' => true,
            'data' => $recipes,
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        // Handle JSON strings from FormData
        $data = $request->all();
        if ($request->has('ingredients') && is_string($request->input('ingredients'))) {
            $data['ingredients'] = json_decode($request->input('ingredients'), true);
        }
        if ($request->has('tags') && is_string($request->input('tags'))) {
            $data['tags'] = json_decode($request->input('tags'), true);
        }
        if ($request->has('allergens') && is_string($request->input('allergens'))) {
            $data['allergens'] = json_decode($request->input('allergens'), true);
        }
        if ($request->has('is_active') && is_string($request->input('is_active'))) {
            $data['is_active'] = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN);
        }
        
        $request->merge($data);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => [
                'required',
                'string',
                'max:255',
                Rule::in(config('recipes.categories', [])),
            ],
            'price' => 'required|numeric|min:0',
            'base_portions' => 'required|integer|min:1',
            'prep_time' => 'required|integer|min:0',
            'cook_time' => 'required|integer|min:0',
            'difficulty' => 'required|in:easy,medium,hard',
            'allergens' => 'nullable|array',
            'tags' => 'nullable|array',
            'ingredients' => 'required|array',
            'instructions' => 'required|string',
            'notes' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Ensure directory exists
            $imageDir = public_path('images/recipes');
            if (!file_exists($imageDir)) {
                mkdir($imageDir, 0755, true);
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = 'images/recipes/' . $imageName;
            
            $image->move($imageDir, $imageName);
            
            $validated['image'] = $imagePath;
        } else {
            $validated['image'] = null;
        }

        $recipe = Recipe::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Recipe created successfully',
            'data' => $recipe,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipe $recipe): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $recipe,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recipe $recipe): JsonResponse
    {
        // Handle JSON strings from FormData
        $data = $request->all();
        if ($request->has('ingredients') && is_string($request->input('ingredients'))) {
            $data['ingredients'] = json_decode($request->input('ingredients'), true);
        }
        if ($request->has('tags') && is_string($request->input('tags'))) {
            $data['tags'] = json_decode($request->input('tags'), true);
        }
        if ($request->has('allergens') && is_string($request->input('allergens'))) {
            $data['allergens'] = json_decode($request->input('allergens'), true);
        }
        if ($request->has('is_active') && is_string($request->input('is_active'))) {
            $data['is_active'] = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN);
        }
        
        $request->merge($data);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'category' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::in(config('recipes.categories', [])),
            ],
            'price' => 'sometimes|required|numeric|min:0',
            'base_portions' => 'sometimes|required|integer|min:1',
            'prep_time' => 'sometimes|required|integer|min:0',
            'cook_time' => 'sometimes|required|integer|min:0',
            'difficulty' => 'sometimes|required|in:easy,medium,hard',
            'allergens' => 'nullable|array',
            'tags' => 'nullable|array',
            'ingredients' => 'sometimes|required|array',
            'instructions' => 'sometimes|required|string',
            'notes' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'sometimes|boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($recipe->image && file_exists(public_path($recipe->image))) {
                unlink(public_path($recipe->image));
            }
            
            // Ensure directory exists
            $imageDir = public_path('images/recipes');
            if (!file_exists($imageDir)) {
                mkdir($imageDir, 0755, true);
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $imagePath = 'images/recipes/' . $imageName;
            
            $image->move($imageDir, $imageName);
            
            $validated['image'] = $imagePath;
        } elseif ($request->has('image') && $request->input('image') === null) {
            // If image is explicitly set to null, delete existing image
            if ($recipe->image && file_exists(public_path($recipe->image))) {
                unlink(public_path($recipe->image));
            }
            $validated['image'] = null;
        }
        // If image is not provided, keep existing image (don't update it)

        $recipe->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Recipe updated successfully',
            'data' => $recipe,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipe $recipe): JsonResponse
    {
        // Delete associated image if exists
        if ($recipe->image && file_exists(public_path($recipe->image))) {
            unlink(public_path($recipe->image));
        }

        $recipe->delete();

        return response()->json([
            'success' => true,
            'message' => 'Recipe deleted successfully',
        ]);
    }

    /**
     * Toggle recipe active status
     */
    public function toggleActive(Recipe $recipe): JsonResponse
    {
        $recipe->update(['is_active' => !$recipe->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Recipe status updated successfully',
            'data' => $recipe,
        ]);
    }

    /**
     * Duplicate a recipe
     */
    public function duplicate(Recipe $recipe): JsonResponse
    {
        $newRecipe = $recipe->replicate();
        $newRecipe->name = $recipe->name . ' (Copy)';
        $newRecipe->save();

        return response()->json([
            'success' => true,
            'message' => 'Recipe duplicated successfully',
            'data' => $newRecipe,
        ], 201);
    }

    /**
     * Get recipe categories
     */
    public function categories(): JsonResponse
    {
        $definedCategories = collect(config('recipes.categories', []));
        $dbCategories = Recipe::distinct()->pluck('category')->filter();
        $categories = $definedCategories
            ->merge($dbCategories)
            ->unique()
            ->values();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }
}
