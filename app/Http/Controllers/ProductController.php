<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
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

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'category' => 'required|string',
            'picture' => 'required|image',
            'hotPrice' => 'required|numeric',
            'icePrice' => 'required|numeric',
        ]);

        $name = date('his') . '.png';
        $product = Product::create($request->except('picture'));
        $request->picture->storeAs('public/images', $name);

        $product->picture = $name;
        $product->save();

        return response([
            'message' => 'data created!',
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'name' => 'sometimes|string',
            'category' => 'sometimes|string',
            'picture' => 'sometimes|image',
            'hotPrice' => 'sometimes|numeric',
            'icePrice' => 'sometimes|numeric',
        ]);

        if ($request->hasFile('picture')) {
            $name = date('his') . '.png';
            $product->update(['picture' => $name]);
            $request->picture->storeAs('public/images', $name);
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
