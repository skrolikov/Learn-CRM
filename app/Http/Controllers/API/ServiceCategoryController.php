<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;

class ServiceCategoryController extends Controller
{
    // Получить список клиентов
    public function index()
    {
        return response()->json(ServiceCategory::all());
    }

    // Создать клиента
    public function store(Request $request)
    {

        $client = ServiceCategory::create($request->all());
        return response()->json($client, 201);
    }

    // Получить клиента по id
    public function show($id)
    {
        $client = ServiceCategory::findOrFail($id);
        return response()->json($client);
    }

    // Обновить данные клиента
    public function update(Request $request, $id)
    {
        $client = ServiceCategory::findOrFail($id);
        $client->update($request->all());
        return response()->json($client);
    }

    // Удалить клиента
    public function destroy($id)
    {
        ServiceCategory::destroy($id);
        return response()->json(null, 204);
    }
}
