<?php

namespace App\Http\Controllers;

use App\Http\Requests\AgentRequest;
use App\Http\Resources\AgentResource;
use App\Http\Resources\ClientResource;
use App\Jobs\SendNotification;
use App\Models\Clients;
use App\Models\Notifications;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AgentController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/login",
     * operationId="authLogin",
     * tags={"Login"},
     * summary="User Login",
     * description="Login User Here",
     *
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *           mediaType="application/json",
     *              @OA\Schema(
     *               type="object",
     *                  @OA\Property(
     *                       property="email",
     *                      description="email",
     *                      type="string",
     *                   example="test@domain.com"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      description="password",
     *                      type="string",
     *                      example="12345"
     *                  ),
     *              )
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *       ),
     *     @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     * )
     */

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return new AgentResource(['data' => 'email or password is wrong']);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return new AgentResource($response);
    }

    /**
     *
     * @OA\SecurityScheme(
     *   securityScheme="bearerAuth",
     *   in="header",
     *   name="Authorization",
     *   type="http",
     *   scheme="bearer",
     *   bearerFormat="JWT",
     * ),
     * @OA\Post(
     *      path="/api/agent/notification",
     *      operationId="storeNotification",
     *      tags={"Agent"},
     *      summary="Store new notification",
     *      description="Returns notification data",
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *           mediaType="application/json",
     *              @OA\Schema(
     *               type="object",
     *                  @OA\Property(
     *                       property="clientId",
     *                      description="clientId",
     *                      type="integer",
     *                   example=1
     *                  ),
     *                  @OA\Property(
     *                      property="channel",
     *                      description="channel",
     *                      type="string",
     *                      example="sms"
     *                  ),
     *                  @OA\Property(
     *                      property="content",
     *                      description="content",
     *                      type="string",
     *                      example="Lorem ipsum dolor sit amet, consectetur adipiscing elit.."
     *                  ),
     *              )
     *          )
     *      ),

     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Notifications")
     *
     *       ),
     *     @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

    public function store(AgentRequest $request)
    {
        try {
            Clients::findOrFail($request->input('clientId'));

            $notification = new Notifications();
            $notification->clientId = $request->input('clientId');
            $notification->channel = $request->input('channel');
            $notification->content = $request->input('content');

            if ($notification->save()) {
                SendNotification::dispatch($notification);

                return new AgentResource($notification);
            } else {
                return new AgentResource(['data' => 'error']);
            }

        } catch (ModelNotFoundException $exception) {
            return new AgentResource(['data' => 'clientId not found']);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/agent/client/{id}",
     *      operationId="getClientById",
     *      tags={"Agent"},
     *      summary="Get client information by id",
     *      description="Returns client data",
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="id",
     *          description="client id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Clients")
     *       ),
     *     @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

    public function getClient($id)
    {
        try {
            $result = Clients::findOrFail($id);

            return new ClientResource($result);
        } catch (ModelNotFoundException $exception) {
            return new ClientResource(['data' => 'Record not found']);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/agent/allClients",
     *      operationId="getClientsList_",
     *      tags={"Agent"},
     *      summary="Get all clients",
     *      description="Get paginated list of clients",
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="The page number",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Clients")
     *       ),
     *     @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */

    public function getAllClients()
    {
        $clients = Clients::paginate(10);

        return new ClientResource($clients);
    }

    /**
     * @OA\Get(
     *      path="/api/agent/notification/{id}",
     *      operationId="getNotificationById",
     *      tags={"Agent"},
     *      summary="Get notification information",
     *      description="Return notification by id",
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *      @OA\Parameter(
     *          name="id",
     *          description="Notification id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Notifications")
     *       ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

    public function getNotification($id)
    {
        try {
            $result = Notifications::findOrFail($id);

            return new AgentResource($result);
        } catch (ModelNotFoundException $exception) {
            return new AgentResource(['data' => 'Record not found']);
        }
    }

    /**
     * @OA\Get(
     *      path="/api/agent/filterNotification",
     *      operationId="getPaginatedNotification_",
     *      tags={"Agent"},
     *      summary="Get paginated list of notifications with filter",
     *      description="get paginated list of notifications, with possibility to filter notifications by client",
     *      security={
     *          {"bearerAuth": {}}
     *      },
     *
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="The page number",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="clientId",
     *         in="query",
     *         description="Filter client Id",

     *         @OA\Schema(
     *             type="object",
     *         ),
     *          example= {"clientId":1},
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Notifications")
     *       ),
     *     @OA\Response(
     *          response=400,
     *          description="Bad Request"
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *     @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     * )
     */

    public function getNotificationsWithFilter()
    {
        if (\request()->has('clientId')) {
            $notification = Notifications::where('clientId', \request('clientId'))->paginate(10);

            return $notification;
        } else {
            return new AgentResource(Notifications::paginate(10));
        }
    }
}
