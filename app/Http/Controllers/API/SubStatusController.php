<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SubStatus;
use Illuminate\Http\Request;

class SubStatusController extends Controller
{
    // Получить список клиентов
    public function index()
    {
        return response()->json(SubStatus::all());
    }

    // Создать клиента
    public function store(Request $request)
    {

        $client = SubStatus::create($request->all());
        return response()->json($client, 201);
    }

    // Получить клиента по id
    public function show($id)
    {
        $client = SubStatus::findOrFail($id);
        return response()->json($client);
    }

    // Обновить данные клиента
    public function update(Request $request, $id)
    {
        $client = SubStatus::findOrFail($id);
        $client->update($request->all());
        return response()->json($client);
    }

    // Удалить клиента
    public function destroy($id)
    {
        SubStatus::destroy($id);
        return response()->json(null, 204);
    }
}
