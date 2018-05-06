<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Product;
use App\User;
use App\ShoppingCart;
use Illuminate\Support\Facades\App;
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
        $newProductData = $request->only(['quantity', 'image_url', 'description', 'name', 'price', 'category_id']);
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
        $product['deleted'] = true;
        $product->save();

        return response()->json(['messages' => "deleted_successfully"], 200);

    }

    public function listAllOwnProducts(Request $request) {
        //check user
    	$user = User::where('address', '=', $request->address)->first();
        if (!$user) {
        	return response()->json(['errors' => "user_not_found"], 404);
        }
        //get all products belong to user
        $products = Product::where('owner_id', '=', $user->id)->where('deleted', '=', 'false')->simplePaginate(10);
        
        return response()->json($products, 200);
    }

    public function listAllProducts(Request $request) {
        $products = Product::where('deleted', '=', 'false')->simplePaginate(10);

        return response()->json($products, 200);
    }

    public function searchProducts(Request $request) {
        //formate key words
        $key_words = '%'.$request->key_words.'%';
        //get all products contains such key words
        $products = Product::where('name', 'ilike', $key_words)->where('deleted', '=', 'false')->simplePaginate(10);

        return response()->json($products, 200);
    }

    public function getListOfProducts(Request $request) {
        $purchasedItems = $request->ids;
        $fields=['owner_id', 'image_url', 'description', 'name'
        , 'category_id', 'deleted'];
        $ids = [];
        foreach ($purchasedItems as $item){
            array_push($ids, $item['id']);
        }

        $products = Product::whereIn('id', $ids)->get();
        foreach ($products as $product){
            $map[$product['id']] = $product;
        }

        foreach ($purchasedItems as $item){
            $item['product'] = 1;
        }
        for($i = 0; $i< sizeof($purchasedItems); $i++){
            $temp_product = $map[$purchasedItems[$i]['id']];
            for($j=0; $j < sizeof($fields); $j++){
                $purchasedItems[$i][$fields[$j]] = $map[$purchasedItems[$i]['id']][$fields[$j]];
                $purchasedItems[$i]['price'] = $purchasedItems[$i]['amount'] / $purchasedItems[$i]['quantity'];
            }
        }
        return response()->json($purchasedItems, 200);
    }

    public function getSingleProduct($id, Request $request){
        $product = Product::where('id', '=', $id)->where('deleted', '=', 'false')->first();
        if(!$product){
            return response()->json(['errors' => "product_not_available"], 404);
        }
        $user = User::find($product['owner_id']);
        $product['owner_address'] = $user->address;
        return response()->json($product, 200);
    }

    public function reduceProductQuantity(Request $request) {
        $shoppingCart = ShoppingCart::where('id', '=', $request->shoppingCartId)->first();
        $prodcut_id = $shoppingCart->product_id;
        $quantity = $shoppingCart->quantity;
        $product = Product::find($prodcut_id);
        if ($product['quantity'] < $quantity) {
            return response()->json(['errors' => "Requested_quantity_not_allow"], 404);
        } else {
            $product['quantity'] -= $quantity;
            if ($product['quantity'] <= 0) {
                $product['deleted'] = true;
            }
            $product->save();
            $shoppingCart->delete();
            
            return response()->json('Thank you for your order',200);
        }
        
        
        

    }
}
