<?php

namespace App\Models;

use App\Http\Helpers\NumericHelper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 *     schema="Participant",
 *     @OA\Property(property="id", type="integer", format="int64", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="skill", type="integer", format="int32", example=50),
 *     @OA\Property(property="strength", type="integer", format="int32", example=80),
 *     @OA\Property(property="speed", type="integer", format="int32", example=70),
 *     @OA\Property(property="reaction", type="integer", format="int32", example=90),
 *     @OA\Property(property="is_defeated", type="boolean", example=false),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-11-18T12:34:56+00:00"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-11-18T12:34:56+00:00"),
 *     @OA\Property(property="deleted_at", type="string", format="date-time", nullable=true, example="null")
 * )
 */
class Participant extends Model
{
    use SoftDeletes;
    use HasFactory;

    const IS_DEFEATED = 1;
    const IS_NOT_DEFEATED = 0;

    const MIN_SCORE = 0;
    const MAX_SCORE = 100;

    const SEED_NUMBER_OPTIONS = 9;

    protected $fillable = [
        'name',
        'skill',
        'strength',
        'speed',
        'reaction',
        'is_defeated',
    ];

    public function seed(int $quantity = 2): Collection
    {
        if (!NumericHelper::isPowerOfTwo($quantity)) {
            throw new \InvalidArgumentException('The quantity must be a power of 2.');
        }

        return Participant::factory()->count($quantity)->create();
    }

    public function clear()
    {
        foreach(self::all() as $participant) {
            $participant->delete();
        }
    }
}
