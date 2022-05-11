<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Clients;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        return Clients::all();
    }

    public function store(ClientRequest $request)
    {
        $review = new Clients();
        $review->firstName = $request->input('firstName');
        $review->lastName = $request->input('lastName');
        $review->email = $request->input('email');
        $review->phoneNumber = $request->input('phoneNumber');

        if ($review->save()) {
            return new ClientResource($review);
        } else {
            return new ClientResource(['data' => 'error']);
        }
    }

    public function show($id)
    {
        try {
            $result = Clients::findOrFail($id);

            return new ClientResource($result);
        } catch (ModelNotFoundException $exception) {
            return new ClientResource(['data' => 'Record not found']);
        }
    }

    public function update(ClientRequest $request, $id)
    {
        try {
            $result = Clients::findOrFail($id);
            $result->firstName = $request->input('firstName');
            $result->lastName = $request->input('lastName');
            $result->email = $request->input('email');
            $result->phoneNumber = $request->input('phoneNumber');

            if ($result->save()) {
                return new ClientResource($result);
            }
        } catch (ModelNotFoundException $exception) {
            return new ClientResource(['data' => 'error']);
        }

    }

    public function destroy($id)
    {
        try {
            $client = Clients::findOrFail($id);

            if ($client->delete()) {
                return new ClientResource($client);
            }
        } catch (ModelNotFoundException $exception) {
            return new ClientResource(['data' => 'Record not found']);
        }
    }
}
