<?php

namespace App\Http\Controllers;

use App\ShoppingCart;
use Illuminate\Http\Request;
use App\Product;
use App\User;
use Illuminate\Support\Facades\DB;
use Exception;
use Validator;

class ShoppingCartController extends Controller
{
    
    public function createShoppingCart(Request $request)
    {
        //validate arguments 
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer',
            'product_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(["errors"=>$validator->errors()->all()], 404);
        }

        //check user
        $user = User::where('address', '=', $request->address)->first();
        if (!$user) {
            return response()->json(['errors' => "user_not_found"], 404);
        }
        
        //check if product exist
        $product_id = $request->product_id;
        $product = Product::find($product_id);
        if (!$product) {
            return response()->json(['errors' => "product_not_found"], 404);
        }

        //check if enough quantity
        $quantity = $request->quantity;
        if ($quantity > $product->quantity) {
            return response()->json(['errors' => "not_enough_stocks"], 404);
        }

        //check if user have the same shoppingcart before
        $checkShoppingCart = ShoppingCart::where('created_by', '=', $user->id)->where('product_id', '=', $product_id)->first();

        //if user doesn't have same shoppingcart before
        if (!$checkShoppingCart) {
            //begin to fill data in
            $newShoppingCartData = $request->only(['product_id','quantity']);
            $newShoppingCartData['created_by'] = $user->id;

            //store data into database, rollback and return errors if fail
            DB::beginTransaction();

            try {
                $ShoppingCart = new ShoppingCart();
                $ShoppingCart->fill($newShoppingCartData);
                $ShoppingCart->save();
                DB::commit();
                return response()->json($ShoppingCart, 200);
            } catch (Exception $e) {
                DB::rollback();
                return response()->json(['errors'=>$e->getMessage()]);
            }

        } else {
            $oldQuantity = $checkShoppingCart->quantity;
            $newQuantity = $oldQuantity + $quantity;
            $checkShoppingCart['quantity']= $newQuantity;
            $checkShoppingCart->save();
            return response()->json($checkShoppingCart, 200);
        }
        
        

    }

    
    public function getShoppingCarts(Request $request)
    {
        //check user
        $user = User::where('address', '=', $request->address)->first();
        if (!$user) {
            return response()->json(['errors' => "user_not_found"], 404);
        }

        //get all shoppingcarts belong to this user
        $shoppingCarts = ShoppingCart::where('created_by', '=', $user->id)->get();

        $products = DB::table('shopping_carts')
            ->join('products', 'shopping_carts.product_id', '=', 'products.id')
            ->join('users', 'products.owner_id', '=', 'users.id')
            ->where('shopping_carts.created_by', '=', $user->id)
            ->select('products.id', 'products.owner_id', 'products.image_url',
             'products.description', 'products.name', 'products.price', 'products.category_id',
             'products.quantity','shopping_carts.id as shopping_carts_id','users.address as owner_address')
            ->simplePaginate(10);
        return response()->json($products);
    }

    
    public function updateShoppingCart($id, Request $request)
    {
        //check arguments
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(["errors"=>$validator->errors()->all()], 404);
        }

        //check user
        $user = User::where('address', '=', $request->address)->first();
        if (!$user) {
            return response()->json(['errors' => "user_not_found"], 404);
        }

        //check if this shopping cart belong to user
        $shoppingCart = ShoppingCart::where('id', '=', $id)->first();
        if ($shoppingCart->created_by != $user->id) {
            return response()->json(['errors' => "not_authorised"], 404);
        }

        //check if enough stocks
        $quantity = $request->quantity;
        $product = Product::find($shoppingCart->product_id);
        if ($quantity > $product->quantity) {
            return response()->json(['errors' => "not_enough_stocks"], 404);
        }

        //fill the data 
        $newShoppingCartData = $request->only(['quantity']);
        $newShoppingCartData['created_by'] = $user->id;
        $shoppingCart->fill($newShoppingCartData);
        $shoppingCart->save();

        return response()->json($shoppingCart, 200);
    }

    
    public function deleteShoppingCart($id, $address, Request $request)
    {
         //check user
        $user = User::where('address', '=', $address)->first();
        if (!$user) {
            return response()->json(['errors' => "user_not_found"], 404);
        }

        //check if shoppingcart exist
        $shoppingCart = ShoppingCart::where('id', '=', $id)->first();
        if (!$shoppingCart) {
            return response()->json(['errors' => "shoppingCart_not_found"], 404);
        }
        //check if authorised
        if ($shoppingCart->created_by != $user->id) {
            return response()->json(['errors' => "not_authorised"], 404);
        }

        $shoppingCart->delete();
        return response()->json(['messages' => "deleted_successfully"], 200);

    }
}
