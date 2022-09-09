<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Table;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function show(Transaction $transaction)
    {
        return $transaction;
    }

    public function show2($id)
    {
        $table = Table::findOrFail($id);
        return $table->transactions()->latest()->where('status', 'menunggu')->first();
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'status' => 'required|string',
            'table_id' => 'required|numeric',
            'item' => 'required|json',
        ]);
        $products = json_decode($request->item);
        

        foreach ($products as $product) {
            $data = Product::findOrFail($product->id);
            $data->stok = $data->stok - $product->total;
            $data->save();
        }

        $table = Table::findOrFail($request->table_id);

        $table->status = 'tidak';
        $table->save();

        $transaction = Transaction::create($request->all());

        return $transaction;
    }

    public function update(Request $request, Transaction $transaction)
    {
        $this->validate($request, [
            'status' => 'required|string',
        ]);

        $transaction->update($request->all());

        $table = Table::findOrFail($transaction->table_id);

        $table->status = 'tersedia';
        $table->save();

        return response([
            'message' => 'data updated!',
        ]);
    }
}
