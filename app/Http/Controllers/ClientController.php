<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Clients;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ClientController extends Controller
{

    /**
     * @OA\Get(
     *      path="/api/client",
     *      operationId="getClientsList",
     *      tags={"Clients"},
     *      summary="Get list clients",
     *      description="Returns list clients",
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

    public function index()
    {
        return Clients::all();
    }


    /**
     * @OA\Post(
     *      path="/api/client",
     *      operationId="storeClient",
     *      tags={"Clients"},
     *      summary="Store new client",
     *      description="Returns client data",
     *       @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                   property="firstName",
     *                   description="firstName",
     *                   type="string",
     *                   example="Piter"
     *               ),
     *               @OA\Property(
     *                   property="lastName",
     *                   description="lastName",
     *                   type="string",
     *                   example="Piters"
     *               ),
     *               @OA\Property(
     *                   property="email",
     *                   description="email",
     *                   type="string",
     *                   example="123@example.com"
     *               ),
     *               @OA\Property(
     *                   property="phoneNumber",
     *                   description="phoneNumber",
     *                   type="string",
     *                   example="+14155552671"
     *               ),
     *           )
     *       )
     *
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

    /**
     * @OA\Get(
     *      path="/api/client/{id}",
     *      operationId="getClientById_",
     *      tags={"Clients"},
     *      summary="Get client information by id",
     *      description="Returns client data",
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

    public function show($id)
    {
        try {
            $result = Clients::findOrFail($id);

            return new ClientResource($result);
        } catch (ModelNotFoundException $exception) {
            return new ClientResource(['data' => 'Record not found']);
        }
    }

    /**
     * @OA\Put(
     *      path="/api/client/{id}",
     *      operationId="updateClient",
     *      tags={"Clients"},
     *      summary="Update existing client",
     *      description="Returns updated project data",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="client id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *           mediaType="application/json",
     *           @OA\Schema(
     *               type="object",
     *               @OA\Property(
     *                   property="firstName",
     *                   description="firstName",
     *                   type="string",
     *                   example="Piter"
     *               ),
     *               @OA\Property(
     *                   property="lastName",
     *                   description="lastName",
     *                   type="string",
     *                   example="Piters"
     *               ),
     *               @OA\Property(
     *                   property="email",
     *                   description="email",
     *                   type="string",
     *                   example="123@example.com"
     *               ),
     *               @OA\Property(
     *                   property="phoneNumber",
     *                   description="phoneNumber",
     *                   type="string",
     *                   example="+14155552671"
     *               ),
     *           )
     *       )
     *
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

    /**
     * @OA\Delete(
     *      path="/api/client/{id}",
     *      operationId="deleteClient",
     *      tags={"Clients"},
     *      summary="Delete existing client",
     *      description="Deletes a record and returns content",
     *      @OA\Parameter(
     *          name="id",
     *          description="Project id",
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
