<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Clients",
 *     description="Clients model",
 *     @OA\Xml(
 *         name="Clients"
 *     )
 * )
 */

class Clients extends Model
{
    use HasFactory;
    //protected $guarded = [];

    /**
     * @OA\Property(
     *     title="firstname",
     *     description="Customer name",
     *     format="string"
     * )
     *
     * @var string
     */
    private $firstName;

    /**
     * @OA\Property(
     *     title="lastName",
     *     description="Customer lastName",
     *     format="string"
     * )
     *
     * @var string
     */
    private $lastName;

    /**
     * @OA\Property(
     *     title="email",
     *     description="Customer email",
     *     format="string"
     * )
     *
     * @var string
     */
    private $email;

    /**
     * @OA\Property(
     *     title="phonenumbar",
     *     description="Customer phonenumbar",
     *     format="string"
     * )
     *
     * @var string
     */
    private $phoneNumber;
}
