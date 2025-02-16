<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // Получить список клиентов
    public function index()
    {
        return response()->json(Client::all());
    }

    // Создать клиента
    public function store(Request $request)
    {

        $client = Client::create($request->all());
        return response()->json($client, 201);
    }

    // Получить клиента по id
    public function show($id)
    {
        $client = Client::findOrFail($id);
        return response()->json($client);
    }

    // Обновить данные клиента
    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);
        $client->update($request->all());
        return response()->json($client);
    }

    // Удалить клиента
    public function destroy($id)
    {
        Client::destroy($id);
        return response()->json(null, 204);
    }
}
