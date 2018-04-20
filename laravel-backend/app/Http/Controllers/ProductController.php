<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Product;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Exception;
use Validator;

class ProductController extends Controller
{
    
    public function uploadProduct(Request $request)
    {
        
        
        //check arguments
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer',
            'description' => 'required',
            'name' => 'required',
            'image_url' => 'required',
            'price' => 'required',
            'category_id' => 'required|integer',
        ]);
        $num = $request->price;
        $decimals = ( (int) $num != $num ) ? (strlen($num) - strpos($num, '.')) - 1 : 0;
        if ($decimals > 3) {
            return response()->json(["errors"=>'It can only contain 3 digits'], 404);
        }
        if ($validator->fails()) {
            return response()->json(["errors"=>$validator->errors()->all()], 404);
        }
        
        //check user
        $user = User::where('address', '=', $request->address)->first();
        if (!$user) {
            return response()->json(['errors' => "user_not_found"], 404);
        }
        
        //check category_id
        $category_id = $request->category_id;
        $category = Category::find($category_id);
        if (!$category) {
            return response()->json(['errors' => "category_not_found"], 404);
        }
        
        //fill the data
        $newProductData = $request->only(['quantity', 'image_url', 'description', 'name', 'price', 'category_id' ]);
        $newProductData['owner_id'] = $user->id;
       

        //store data
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

    public function editProduct(Request $request) {
        //check arguments
    	$validator = Validator::make($request->all(), [
            'quantity' => 'required|integer',
            'description' => 'required',
            'name' => 'required',
            'price' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(["errors"=>$validator->errors()->all()], 404);
        }

        //get product id
        $id = $request->id;

        //check user
        $user = User::where('address', '=', $request->address)->first();
        if (!$user) {
        	return response()->json(['errors' => "user_not_found"], 404);
        }

        //check product authorised 
        $product = Product::where('id', '=', $id)->first();
        if (!$product) {
            return response()->json(['errors' => "product_not_found"], 404);
        }
        if ($product->owner_id != $user->id) {
        	return response()->json(['errors' => "not_authorised"], 404);
        }
        
        //fill data
        $newProductData = $request->only(['quantity',  'description', 'name', 'price']);
        $newProductData['owner_id'] = $user->id;
        

        //store data
        $product->fill($newProductData);
        $product->save();
        return response()->json($product, 200);
    }

    public function deleteProduct($id, Request $request) {
        
        //check user
        $user = User::where('address', '=', $request->address)->first();
        if (!$user) {
        	return response()->json(['errors' => "user_not_found"], 404);
        }

        //check authorised
        $product = Product::where('id', '=', $id)->first();
        //if product not exist
        if (!$product) {
            return response()->json(['errors' => "product_not_found"], 404);
        }
        if ($product->owner_id != $user->id) {
        	return response()->json(['errors' => "not_authorised"], 404);
        }

        //delete
        $product->delete();

        return response()->json(['messages' => "deleted_successfully"], 200);

    }

    public function listAllOwnProducts(Request $request) {
        //check user
    	$user = User::where('address', '=', $request->address)->first();
        if (!$user) {
        	return response()->json(['errors' => "user_not_found"], 404);
        }
        //get all products belong to user
        $products = Product::where('owner_id', '=', $user->id)->simplePaginate(10);
        
        return response()->json($products, 200);
    }

    public function listAllProducts(Request $request) {
        $products = DB::table('products')->simplePaginate(10);

        return response()->json($products, 200);
    }

    public function searchProducts(Request $request) {
        //formate key words
        $key_words = '%'.$request->key_words.'%';
        //get all products contains such key words
        $products = Product::where('name', 'ilike', $key_words)->simplePaginate(10);

        return response()->json($products, 200);
    }

    public function getListOfProducts(Request $request) {
        $ids = $request->ids;
        $products = Product::whereIn('id', $ids)->get();

        return response()->json($products, 200);
    }
}
