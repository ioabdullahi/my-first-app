<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // GET /products (List all products)
    public function index()
    {
        return Product::all(); // Returns JSON response
    }

    // POST /products (Create new product)
    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0'
        ]);

        // Create and return product
        return Product::create($validated);
    }

    // GET /products/{id} (Show single product)
    public function show($id)
    {
        return Product::findOrFail($id); // 404 if not found
    }

    // PUT/PATCH /products/{id} (Update product)
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0'
        ]);

        $product->update($validated);
        return $product;
    }

    // DELETE /products/{id} (Remove product)
    public function destroy($id)
    {
        Product::destroy($id);
        return response(null, 204); // HTTP 204 No Content
    }
}