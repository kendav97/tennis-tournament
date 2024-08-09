<?php

namespace App\Models;

use App\Http\Helpers\NumericHelper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Participant extends Model
{
    use SoftDeletes;
    use HasFactory;

    const IS_DEFEATED = 1;
    const IS_NOT_DEFEATED = 0;

    const MIN_SCORE = 0;
    const MAX_SCORE = 100;

    protected $fillable = [
        'name',
        'skill',
        'strength',
        'speed',
        'reaction',
        'is_defeated',
    ];

    public function autoAdd(int $quantity = 2): Collection
    {
        if (!NumericHelper::isPowerOfTwo($quantity)) {
            throw new \InvalidArgumentException('The quantity must be a power of 2.');
        }

        return Participant::factory()->count($quantity)->create();
    }
}
