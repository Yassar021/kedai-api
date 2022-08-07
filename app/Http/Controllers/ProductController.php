<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $q = $request->query('category', '');
        return Product::where('category', 'like', "%$q%")->latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $name = date('his') . '.png';
        $product = Product::create($request->except('picture'));
        $request->image->storeAs('public/images', $name);

        $product->picture = $name;
        $product->save();

        return response([
            'message' => 'data created!',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProductRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        if ($request->hasFile('picture')) {
            $name = date('his') . '.png';
            $product->update(['picture' => $name]);
            $request->image->storeAs('public/images', $name);
        }
        $product->update($request->except('picture'));

        return response([
            'message' => 'data updated!',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response([
            'message' => 'data deleted!',
        ]);
    }
}
