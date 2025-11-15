<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Setting::query();

        // Search functionality
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('key', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        // Type filter
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        $settings = $query->orderBy('key')->get();

        return response()->json([
            'success' => true,
            'data' => $settings,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:settings,key',
            'value' => 'required|string',
            'type' => 'required|in:string,number,boolean,json',
            'description' => 'nullable|string',
        ]);

        $setting = Setting::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Setting created successfully',
            'data' => $setting,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Setting $setting): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $setting,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Setting $setting): JsonResponse
    {
        $validated = $request->validate([
            'key' => 'sometimes|required|string|max:255|unique:settings,key,' . $setting->id,
            'value' => 'sometimes|required|string',
            'type' => 'sometimes|required|in:string,number,boolean,json',
            'description' => 'nullable|string',
        ]);

        $setting->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Setting updated successfully',
            'data' => $setting,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Setting $setting): JsonResponse
    {
        $setting->delete();

        return response()->json([
            'success' => true,
            'message' => 'Setting deleted successfully',
        ]);
    }

    /**
     * Get setting value by key
     */
    public function getValue(string $key): JsonResponse
    {
        $value = Setting::getValue($key);

        return response()->json([
            'success' => true,
            'data' => [
                'key' => $key,
                'value' => $value,
            ],
        ]);
    }

    /**
     * Set setting value by key
     */
    public function setValue(Request $request, string $key): JsonResponse
    {
        $key = $this->normalizeSettingKey($key);

        $validated = $request->validate([
            'value' => 'required',
            'type' => 'sometimes|in:string,number,boolean,json',
            'description' => 'nullable|string',
        ]);

        $type = $validated['type'] ?? 'string';
        $description = $validated['description'] ?? null;

        $setting = Setting::setValue($key, $validated['value'], $type, $description);

        return response()->json([
            'success' => true,
            'message' => 'Setting value updated successfully',
            'data' => $this->formatSettingsForFrontend(),
        ]);
    }

    /**
     * Get all restaurant settings
     */
    public function getRestaurantSettings(): JsonResponse
    {
        $settings = $this->formatSettingsForFrontend();

        return response()->json([
            'success' => true,
            'data' => $settings,
        ]);
    }

    /**
     * Update restaurant settings
     */
    public function updateRestaurantSettings(Request $request): JsonResponse
    {
        $normalized = $this->normalizeSettingPayload($request->all());

        $validated = validator($normalized, [
            'restaurant_name' => 'sometimes|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'tax_rate' => 'sometimes|numeric|min:0|max:100',
            'delivery_fee' => 'sometimes|numeric|min:0',
            'currency' => 'sometimes|string|max:10',
            'receipt_footer' => 'nullable|string',
            'print_header' => 'sometimes|boolean',
            'print_footer' => 'sometimes|boolean',
            'auto_print' => 'sometimes|boolean',
            'receipt_width' => 'sometimes|integer|min:50|max:120',
            'font_size' => 'sometimes|integer|min:8|max:16',
            'logo' => 'nullable|string',
        ])->validate();

        foreach ($validated as $key => $value) {
            $type = is_bool($value) ? 'boolean' : (is_numeric($value) ? 'number' : 'string');
            Setting::setValue($key, $value, $type);
        }

        return response()->json([
            'success' => true,
            'message' => 'Restaurant settings updated successfully',
            'data' => $this->formatSettingsForFrontend(),
        ]);
    }

    /**
     * Get setting types
     */
    public function getTypes(): JsonResponse
    {
        $types = ['string', 'number', 'boolean', 'json'];

        return response()->json([
            'success' => true,
            'data' => $types,
        ]);
    }

    /**
     * Normalize payload keys coming from the frontend (camelCase) to snake_case.
     */
    private function normalizeSettingPayload(array $payload): array
    {
        $mapping = $this->getKeyMapping();

        foreach ($mapping as $camel => $snake) {
            if (array_key_exists($camel, $payload)) {
                $payload[$snake] = $payload[$camel];
                unset($payload[$camel]);
            }
        }

        return $payload;
    }

    /**
     * Return settings formatted for the frontend (camelCase keys).
     */
    private function formatSettingsForFrontend(): array
    {
        $mapping = array_flip($this->getKeyMapping());
        $defaults = [
            'restaurant_name' => config('app.name', 'BlessedCafe'),
            'address' => '',
            'phone' => '',
            'email' => '',
            'website' => '',
            'tax_rate' => 10,
            'delivery_fee' => 5,
            'currency' => 'PHP',
            'receipt_footer' => '',
            'print_header' => true,
            'print_footer' => true,
            'auto_print' => false,
            'receipt_width' => 80,
            'font_size' => 12,
            'logo' => '',
        ];

        $snakeSettings = [];
        foreach ($defaults as $key => $default) {
            $snakeSettings[$key] = Setting::getValue($key, $default);
        }

        $formatted = [];
        foreach ($snakeSettings as $snakeKey => $value) {
            $camelKey = $mapping[$snakeKey] ?? $snakeKey;
            $formatted[$camelKey] = $value;
        }

        return $formatted;
    }

    /**
     * Mapping between camelCase frontend keys and snake_case DB keys.
     */
    private function getKeyMapping(): array
    {
        return [
            'restaurantName' => 'restaurant_name',
            'address' => 'address',
            'phone' => 'phone',
            'email' => 'email',
            'website' => 'website',
            'taxRate' => 'tax_rate',
            'deliveryFee' => 'delivery_fee',
            'currency' => 'currency',
            'receiptFooter' => 'receipt_footer',
            'printHeader' => 'print_header',
            'printFooter' => 'print_footer',
            'autoPrint' => 'auto_print',
            'receiptWidth' => 'receipt_width',
            'fontSize' => 'font_size',
            'logo' => 'logo',
        ];
    }

    private function normalizeSettingKey(string $key): string
    {
        return $this->getKeyMapping()[$key] ?? $key;
    }
}
