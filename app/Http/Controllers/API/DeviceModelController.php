<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DeviceModel;
use Illuminate\Http\Request;

class DeviceModelController extends Controller
{
    // Получить список клиентов
    public function index()
    {
        return response()->json(DeviceModel::all());
    }

    // Создать клиента
    public function store(Request $request)
    {

        $client = DeviceModel::create($request->all());
        return response()->json($client, 201);
    }

    // Получить клиента по id
    public function show($id)
    {
        $client = DeviceModel::findOrFail($id);
        return response()->json($client);
    }

    // Обновить данные клиента
    public function update(Request $request, $id)
    {
        $client = DeviceModel::findOrFail($id);
        $client->update($request->all());
        return response()->json($client);
    }

    // Удалить клиента
    public function destroy($id)
    {
        DeviceModel::destroy($id);
        return response()->json(null, 204);
    }
}
