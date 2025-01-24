<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class ProductController extends Controller
{
    public function index(){
        $products=Product::get();
        if($products->count()>0){
            return ProductResource::collection($products);
        }else{
            return response()->json(['message'=>'No record available '],200);
        }
    }
   public function store(Request $request)
{
    // Validate the incoming request
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'price' => 'required|integer|min:1',
        'description' => 'required|string',
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation Error: All fields are mandatory.',
            'errors' => $validator->messages(),
        ], 422);
    }

    // Create the product
    $product = Product::create([
        'name' => $request->name,
        'price' => $request->price,
        'description' => $request->description,
    ]);

    // Return the response with ProductResource
    return response()->json([
        'message' => 'Product Created Successfully',
        'data' => new ProductResource($product),
    ], 201); // 201 indicates resource created successfully
}
    public function show(Product $product){
        return new ProductResource($product);
    }
    public function update(Request $request,Product $product){
         $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'price' => 'required|integer|min:1',
        'description' => 'required|string',
    ]);

    // Check if validation fails
    if ($validator->fails()) {
        return response()->json([
            'message' => 'Validation Error: All fields are mandatory.',
            'errors' => $validator->messages(),
        ], 422);
    }
    $product->update([
        'name' => $request->name,
        'price' => $request->price,
        'description' => $request->description,
    ]);
     // Return the response with ProductResource
    return response()->json([
        'message' => 'Product Updated Successfully',
        'data' => new ProductResource($product),
    ], 201); // 201 indicates resource Updated successfully
    }
    public function destroy(Product $product){
        $product->delete();
         // Return the response with ProductResource
    return response()->json([
        'message' => 'Product Deleted Successfully',
        'data' => new ProductResource($product),
    ], 201); // 201 indicates resource Deleted successfully
    }


}