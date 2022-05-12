<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="Notifications",
 *     description="Notifications model",
 *     @OA\Xml(
 *         name="Notifications"
 *     )
 * )
 */

class Notifications extends Model
{
    use HasFactory;

    /**
     * @OA\Property(
     *     title="clientId",
     *     description="Customer id",
     *     format="integer"
     * )
     *
     * @var string
     */
    private $clientId;

    /**
     * @OA\Property(
     *     title="channel",
     *     description="message channel",
     *     format="string"
     * )
     *
     * @var string
     */
    private $channel;

    /**
     * @OA\Property(
     *     title="content",
     *     description="Message text",
     *     format="string"
     * )
     *
     * @var string
     */
    private $content;
}
