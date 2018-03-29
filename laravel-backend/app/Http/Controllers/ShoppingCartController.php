<?php

namespace App\Http\Controllers;

use App\ShoppingCart;
use Illuminate\Http\Request;
use App\Product;
use Illuminate\Support\Facades\DB;
use Exception;

class ShoppingCartController extends Controller
{
    
    public function createShoppingCart(Request $request)
    {
        $this->validate($request, [
            'quantity' => 'required|integer',
            'product_id' => 'required|integer',
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json(['errors' => "user_not_found"], 404);
        }
        
        $product_id = $request->'product_id';
        $product = Product::find($product_id);
        if (!$product) {
            return respons()->json(['errors' => "product_not_found"], 404);
        }
        
        $newShoppingCartData = $reuest->only(['product_id', 'quantity']);
        $newShoppingCartData['created_by'] = $user->id;

        DB::beginTransaction();

        try {
            $shoppingCart = new ShoppingCart();
            $shoppingCart->fill($newShoppingCartData);
            $shoppingCart->save();
            DB::commit();
            return response()->json($shoppingCart, 201);
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }

    }

    
    public function getShoppingCarts(Request $reuest)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['errors' => "user_not_found"], 404);
        }
        $shoppingCarts = ShoppingCart::where('created_by', '=', $user->id);

        return response()->json($shoppingCarts);
    }

    
    public function updateShoppingCart($id, Request $request)
    {
        //
        $this->validate($request, [
            'quantity' => 'required|integer',
        ]);

        $user = $request->user();
        if (!$user) {
            return response()->json(['errors' => "user_not_found"], 404);
        }

        $shoppingCart = ShoppingCart::where('id', '=', $id);
        if ($shoppingCart->created_by != $user->id) {
            return response()->json(['errors' => "not_authorised"], 401);
        }

        $newShoppingCartData = $reuest->only(['quantity']);
        $newShoppingCartData['created_by'] = $user->id;
        $shoppingCart->fill($newShoppingCartData);
        $shoppingCart->save();

        return response()->json($shoppingCart, 200);
    }

    
    public function deleteShoppingCart($id, Resquest $reuest)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['errors' => "user_not_found"], 404);
        }

        $shoppingCart = ShoppingCart::where('id', '=', $id);
        if ($shoppingCart->created_by != $user->id) {
            return response()->json(['errors' => "not_authorised"], 401);
        }

        $shoppingCart->delete();
        return response()->json(null, 204);
    }
}
