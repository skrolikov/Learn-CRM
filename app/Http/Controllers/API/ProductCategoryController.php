<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    // Получить список клиентов
    public function index()
    {
        return response()->json(ProductCategory::all());
    }

    // Создать клиента
    public function store(Request $request)
    {

        $client = ProductCategory::create($request->all());
        return response()->json($client, 201);
    }

    // Получить клиента по id
    public function show($id)
    {
        $client = ProductCategory::findOrFail($id);
        return response()->json($client);
    }

    // Обновить данные клиента
    public function update(Request $request, $id)
    {
        $client = ProductCategory::findOrFail($id);
        $client->update($request->all());
        return response()->json($client);
    }

    // Удалить клиента
    public function destroy($id)
    {
        ProductCategory::destroy($id);
        return response()->json(null, 204);
    }
}
