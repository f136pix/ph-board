<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Http\Resources\RoleResource;
use App\Models\Product;
use App\Models\Role;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function index()
    {
        return response()->json(ProductResource::collection(Product::paginate()), Response::HTTP_OK);
    }

    public function store(Request $r)
    {
        $product = Product::create($r->only('title', 'description', 'image', 'price'));

        return response()->json(new ProductResource($product), Response::HTTP_OK);

    }

    public function show(int $id)
    {
        return response()->json(new ProductResource(Product::find($id)), Response::HTTP_OK);
    }

    public function update(Request $r, int $id)
    {
        $product = Product::find($id);

        $product->update($r->only('title', 'description', 'image','price'));

        return response()->json(['message' => 'produto atualizado', 'produto' => new ProductResource($product)], Response::HTTP_ACCEPTED);
    }

    public function destroy(int $id)
    {
        Role::destroy($id);

        return response()->json(['message' => 'produto destruido'], Response::HTTP_ACCEPTED);
    }

}



