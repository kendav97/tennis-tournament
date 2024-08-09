<?php

namespace App\Http\Controllers;

use App\Http\Helpers\NumericHelper;
use App\Models\Participant;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use function Laravel\Prompts\select;


class GameController extends Controller
{
    const MALE = 'male';
    const FEMALE = 'female';

    const GENDERS = [self::MALE, self::FEMALE];

    const MAX_ITERATIONS = 1000;

    public function consolePlay(Command $command)
    {
        $gender = $command->option('gender');

        if (!$gender) {
            $command->info('No gender has been specified. Please select one:');
            $gender = select(
                label: 'Select the gender:',
                options: GameController::GENDERS
            );
        } else {
            if (!in_array($gender, GameController::GENDERS)) {
                $command->error('The entered gender is invalid. Please select one of the following:');
                $gender = select(
                    label: 'Select the gender:',
                    options: GameController::GENDERS
                );
            }
        }

        $force = $command->option('force');

        $gameController = new GameController();
        $result = $gameController->play($gender, $force);

        if (is_numeric($result)) {
            if ($result == 0) {
                $command->error('There are no participants available in the database.');
            } elseif ($result > 0 && !$force) {
                $command->warn('The number of participants is not a base 2 power, therefore there may be an unexpected result.');
                if (!$command->confirm('Do you want to proceed anyway?')) {
                    return Command::FAILURE;
                }
                $gameController->play($gender, true);
            }
        }

        $winnerName = $result->name;
        $winnerId = $result->id;
        $winnerText = "The winner is $winnerName with id = $winnerId";
        $command->info($winnerText);
        
        return Command::SUCCESS;
    }

    public function requestReplay(Request $request)
    {
        $this->reset();

        return $this->requestPlay($request);
    }

    public function requestPlay(Request $request)
    {
        $validatedData = $request->validate([
            'gender' => 'required|in:'.implode(',', self::GENDERS),
            'force' => 'boolean',
        ]);
    
        $gender = $validatedData['gender'];
        $force = $validatedData['force'];
    
        $result = $this->play($gender, $force);

        if (is_numeric($result)) {
            if ($result == 0) {
                return response()->json([
                    'message' => 'There are no participants available in the database.',
                    'success' => false
                ], 200);
            }

            if ($result > 0) {
                return response()->json([
                    'message' => 'The number of participants is not a base 2 power, therefore there may be an unexpected result. Send the parameter "force" = true to run the game anyway.',
                    'success' => false
                ], 200);
            }
        }

        $winnerName = $result->name;
        $winnerId = $result->id;
        $winnerText = "The winner is $winnerName with id = $winnerId";

        return response()->json([
            'message' => $winnerText,
            'success' => true
        ], 200);
    }

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
                return $participants->first();
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
