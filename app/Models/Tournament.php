<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Tournament",
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="gender", type="string", example="female"),
 *     @OA\Property(property="participant_id", type="number", example="256"),
 *     @OA\Property(
 *         property="participant",
 *         type="object",
 *         description="Winner participant",
 *         ref="#/components/schemas/Participant"
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-11-18T12:34:56+00:00"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-11-18T12:34:56+00:00")
 * )
 */
class Tournament extends Model
{
    protected $fillable = [
        'gender',
        'participant_id'
    ];

    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }
}
