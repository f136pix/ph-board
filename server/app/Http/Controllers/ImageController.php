<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ImageController
{
    public function upload(Request $r)
    {
        $file = $r->file('image');
        $name = Str::random(10);
        $storage = Storage::putFileAs('images', $file, $name . '.' . $file->extension());
        $url = env('APP_URL') . '/' . $storage;

        return response()->json(['storage' => $storage, 'url' => $url], Response::HTTP_OK);

    }
}
