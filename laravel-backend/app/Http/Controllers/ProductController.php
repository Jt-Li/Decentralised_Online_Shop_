<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Product;
use Illuminate\Support\Facades\DB;
use Exception;

class ProductController extends Controller
{
    //
    public function uploadProduct(Request $request)
    {
        $this->validate($request, [
            'quantity' => 'required|integer',
            'description' => 'required',
            'name' => 'required',
            'image_url' => 'required',
            'price' => 'required',
            'category_id' => 'required|integer',
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json(['errors' => "user_not_found"], 404);
        }
        
        $category_id = $request->category_id;
        $category = Category::find($category_id);
        if (!$category) {
            return response()->json(['errors' => "category_not_found"], 404);
        }
        
        $newProductData = $request->only(['quantity', 'image_url', 'description', 'name', 'price', 'category_id' ]);
        $newProductData['owner_id'] = $user->id;

        DB::beginTransaction();

        try {
            $Product = new Product();
            $Product->fill($newProductData);
            $Product->save();
            DB::commit();
            return response()->json($Product, 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error'=>$e->getMessage()]);
        }

    }

    public function editProduct($id, Request $request) {
    	$this->validate($request, [
            'quantity' => 'required|integer',
            'description' => 'required',
            'name' => 'required',
            'image_url' => 'required',
            'price' => 'required',
            'category_id' => 'required|integer',
        ]);

        $user = $request->user();
        if (!$user) {
        	return resonse()->json(['errors' => "user_not_found"], 404);
        }

        $product = Product::find($id);
        if ($product->owner_id != $user->id) {
        	return response()->json(['errors' => "not_authorised"], 404)；
        }

        $category_id = $request->category_id;
        $category = Category::find($category_id);
        if (!$category) {
            return response()->json(['errors' => "category_not_found"], 404);
        }

        $newProductData = $request->only(['quantity', 'image_url', 'description', 'name', 'price', 'category_id' ]);
        $newProductData['owner_id'] = $user->id;

        
        $product->fill($newProductData);
        $product->save();
        return resonse()->json($product, 200);
    }

    public function deleteProduct($id, Request $request) {
    	$user = $request->user();
        if (!$user) {
        	return resonse()->json(['errors' => "user_not_found"], 404);
        }

        $product = Product::find($id);
        if ($product->owner_id != $user->id) {
        	return response()->json(['errors' => "not_authorised"], 404)；
        }

        $product->delete();

        return resonse()->json(null, 200);

    }

    public function listAllProducts(Request $request) {
    	$user = $request->user();
        if (!$user) {
        	return resonse()->json(['errors' => "user_not_found"], 404);
        }

        $products = Product::where('owner_id', '=', $user->id);

        return response()->json($products);
    }
}
