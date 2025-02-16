<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DeviceColor;
use Illuminate\Http\Request;

class DeviceColorController extends Controller
{
    // Получить список клиентов
    public function index()
    {
        return response()->json(DeviceColor::all());
    }

    // Создать клиента
    public function store(Request $request)
    {

        $client = DeviceColor::create($request->all());
        return response()->json($client, 201);
    }

    // Получить клиента по id
    public function show($id)
    {
        $client = DeviceColor::findOrFail($id);
        return response()->json($client);
    }

    // Обновить данные клиента
    public function update(Request $request, $id)
    {
        $client = DeviceColor::findOrFail($id);
        $client->update($request->all());
        return response()->json($client);
    }

    // Удалить клиента
    public function destroy($id)
    {
        DeviceColor::destroy($id);
        return response()->json(null, 204);
    }
}
