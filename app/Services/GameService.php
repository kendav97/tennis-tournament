<?php

namespace App\Services;

use App\Http\Controllers\TournamentController;
use App\Http\Helpers\NumericHelper;
use App\Models\Participant;
use Exception;


class GameService
{
    const MALE = 'male';
    const FEMALE = 'female';

    const GENDERS = [self::MALE, self::FEMALE];

    const MAX_ITERATIONS = 1000;

    public function play(string $gender, bool $force = false): Participant|int
    {
        if (!in_array($gender, self::GENDERS)) {
            throw new Exception("Incorrect gender");
        }

        $iterations = 0;
        do {
            $participants = Participant::where('is_defeated', Participant::IS_NOT_DEFEATED)->get();
            $count = $participants->count();

            if ((!NumericHelper::isPowerOfTwo($count) && !$force) || $count == 0 || ($count == 1 && $iterations == 0)) {
                return $count;
            }

            if ($count == 1) {
                $winner = $participants->first();
                TournamentController::save($gender, $winner->id);
                return $winner;
            }

            for ($i = 0; $i < ($count - 1); $i += 2) {
                $this->match($gender, $participants[$i], $participants[$i+1]);
            }

            $iterations++;
        } while ($iterations < self::MAX_ITERATIONS);

        throw New Exception("Iterations limit is exceeded");
    }

    private function match(string $gender, Participant $first, Participant $second): void
    {
        if ($gender == self::MALE) {
            $first_score = $this->getMaleScore($first);
            $second_score = $this->getMaleScore($second);
        }
        if ($gender == self::FEMALE) {
            $first_score = $this->getFemaleScore($first);
            $second_score = $this->getFemaleScore($second);
        }

        if ($first_score > $second_score) {
            $second->is_defeated = Participant::IS_DEFEATED;
            $second->save();
        } else {
            $first->is_defeated = Participant::IS_DEFEATED;
            $first->save();
        }
    }

    private function getMaleScore(Participant $p): float
    {
        return (
            $p->skill
            + $p->strength
            + $p->speed
            + random_int(Participant::MIN_SCORE, Participant::MAX_SCORE)
        ) / 4;
    }

    private function getFemaleScore(Participant $p): float
    {
        return (
            $p->skill
            + $p->reaction
            + random_int(Participant::MIN_SCORE, Participant::MAX_SCORE)
        ) / 3;
    }

    public function reset(): void
    {
        Participant::where('is_defeated',Participant::IS_DEFEATED )->update(['is_defeated' => Participant::IS_NOT_DEFEATED]);
    }
}
