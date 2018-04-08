<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
class ImageController extends Controller
{
    private $URL="https://s3.amazonaws.com/comp9900-frog-unsw/";
    public function store(Request $request)
    {
        
        $path =$this->URL.Storage::put('images', $request->file('images'), 'public');
       
        return $path;

    }

}
