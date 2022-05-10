<?php

namespace App\Http\Controllers;

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

    public function store(Request $request)
    {
        $request->validate(
            [
                'firstName' => 'required|string|max:32|min:2|regex:/^[a-zA-Z]+$/',
                'lastName' => 'required|string|max:32|min:2|regex:/^[a-zA-Z]+$/',
                'email' => 'required|string|unique:clients,email',
                'phoneNumber' => 'required|regex:/^\+?[1-9]\d{1,14}$/',
            ]
        );

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

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'firstName' => 'required|string|max:32|min:2|regex:/^[a-zA-Z]+$/',
                'lastName' => 'required|string|max:32|min:2|regex:/^[a-zA-Z]+$/',
                'email' => 'required|string|unique:clients,email',
                'phoneNumber' => 'required|regex:/^\+?[1-9]\d{1,14}$/',
            ]
        );

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
