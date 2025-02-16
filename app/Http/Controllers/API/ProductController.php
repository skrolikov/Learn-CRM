<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Получить список клиентов
    public function index()
    {
        return response()->json(Product::all());
    }

    // Создать клиента
    public function store(Request $request)
    {

        $client = Product::create($request->all());
        return response()->json($client, 201);
    }

    // Получить клиента по id
    public function show($id)
    {
        $client = Product::findOrFail($id);
        return response()->json($client);
    }

    // Обновить данные клиента
    public function update(Request $request, $id)
    {
        $client = Product::findOrFail($id);
        $client->update($request->all());
        return response()->json($client);
    }

    // Удалить клиента
    public function destroy($id)
    {
        Product::destroy($id);
        return response()->json(null, 204);
    }
}
