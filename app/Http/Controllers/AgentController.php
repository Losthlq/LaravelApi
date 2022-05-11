<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgentRequest;
use App\Http\Resources\AgentResource;
use App\Http\Resources\ClientResource;
use App\Models\Clients;
use App\Models\Notifications;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AgentController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['email or password is wrong']
            ], 401);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 201);
    }

    public function store(AgentRequest $request)
    {
        try {
            Clients::findOrFail($request->input('clientId'));

            $notification = new Notifications();
            $notification->clientId = $request->input('clientId');
            $notification->channel = $request->input('channel');
            $notification->content = $request->input('content');

            if ($notification->save()) {
                return new AgentResource($notification);
            } else {
                return new AgentResource(['data' => 'error']);
            }

        } catch (ModelNotFoundException $exception) {
            return new AgentResource(['data' => 'clientId not found']);
        }
    }

    public function getClient($id)
    {
        try {
            $result = Clients::findOrFail($id);

            return new ClientResource($result);
        } catch (ModelNotFoundException $exception) {
            return new ClientResource(['data' => 'Record not found']);
        }
    }

    public function getAllClients()
    {
        $clients = Clients::paginate(10);
        return new ClientResource($clients);

    }

    public function getNotification($id)
    {
        try {
            $result = Notifications::findOrFail($id);

            return new AgentResource($result);
        } catch (ModelNotFoundException $exception) {
            return new AgentResource(['data' => 'Record not found']);
        }
    }

    public function getNotificationsWithFilter()
    {
        if(\request()->has('clientId')){



            $notification = Notifications::where('clientId', \request('clientId'))->paginate(10);

            return $notification;
        } else {

            return new AgentResource(Notifications::paginate(10));
        }
    }
}
