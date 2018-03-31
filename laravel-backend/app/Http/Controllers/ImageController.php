<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class ImageController extends Controller
{
    private $URL="https://s3.amazonaws.com/comp9900-frog-unsw/";
    public function store(Request $request)
    {
//        $validator = Validator::make($request->all(), [
//
//        ]);
//        if ($validator->fails()) {
//            return response()->json(["errors"=>$validator->errors()->all()], 404);
//        }
        $path =$this->URL.Storage::put('images', $request->file('file'), 'public');
        //$path = $this->URL.$request->file('file')->store('images');

        return $path;

    }

}
