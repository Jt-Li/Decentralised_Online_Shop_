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
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer',
            'product_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(["errors"=>$validator->errors()->all()], 404);
        }

        $user = User::where('address', '=', $request->address);
        if (!$user) {
            return response()->json(['errors' => "user_not_found"], 404);
        }
        
        $product_id = $request->product_id;
        $product = Product::find($product_id);
        if (!$product) {
            return response()->json(['errors' => "product_not_found"], 404);
        }

        $quantity = $request->quantity;
        if ($quantity > $product->quantity) {
            return response()->json(['errors' => "not_enough_stocks"], 404);
        }
        
        $newShoppingCartData = $request->only(['product_id', 'quantity']);
        $newShoppingCartData['created_by'] = $user->id;

        DB::beginTransaction();

        try {
            $shoppingCart = new ShoppingCart();
            $shoppingCart->fill($newShoppingCartData);
            $shoppingCart->save();
            DB::commit();
            return response()->json($shoppingCart, 200);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['error'=>$e->getMessage()]);
        }

    }

    
    public function getShoppingCarts(Request $request)
    {
        $user = User::where('address', '=', $request->address);
        if (!$user) {
            return response()->json(['errors' => "user_not_found"], 404);
        }
        $shoppingCarts = ShoppingCart::where('created_by', '=', $user->id);

        return response()->json($shoppingCarts);
    }

    
    public function updateShoppingCart($id, Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(["errors"=>$validator->errors()->all()], 404);
        }

        $user = User::where('address', '=', $request->address);
        if (!$user) {
            return response()->json(['errors' => "user_not_found"], 404);
        }

        $shoppingCart = ShoppingCart::where('id', '=', $id);
        if ($shoppingCart->created_by != $user->id) {
            return response()->json(['errors' => "not_authorised"], 404);
        }

        $newShoppingCartData = $request->only(['quantity']);
        $newShoppingCartData['created_by'] = $user->id;
        $shoppingCart->fill($newShoppingCartData);
        $shoppingCart->save();

        return response()->json($shoppingCart, 200);
    }

    
    public function deleteShoppingCart($id, Resquest $request)
    {
        $user = User::where('address', '=', $request->address);
        if (!$user) {
            return response()->json(['errors' => "user_not_found"], 404);
        }

        $shoppingCart = ShoppingCart::where('id', '=', $id);
        if ($shoppingCart->created_by != $user->id) {
            return response()->json(['errors' => "not_authorised"], 404);
        }

        $shoppingCart->delete();
        return response()->json(null, 204);
    }
}
