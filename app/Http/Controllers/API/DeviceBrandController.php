<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DeviceBrand;
use Illuminate\Http\Request;

class DeviceBrandController extends Controller
{
    // Получить список клиентов
    public function index()
    {
        return response()->json(DeviceBrand::all());
    }

    // Создать клиента
    public function store(Request $request)
    {

        $client = DeviceBrand::create($request->all());
        return response()->json($client, 201);
    }

    // Получить клиента по id
    public function show($id)
    {
        $client = DeviceBrand::findOrFail($id);
        return response()->json($client);
    }

    // Обновить данные клиента
    public function update(Request $request, $id)
    {
        $client = DeviceBrand::findOrFail($id);
        $client->update($request->all());
        return response()->json($client);
    }

    // Удалить клиента
    public function destroy($id)
    {
        DeviceBrand::destroy($id);
        return response()->json(null, 204);
    }
}
