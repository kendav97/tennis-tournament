<?php

namespace App\Http\Controllers;

use App\Services\GameService;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use function Laravel\Prompts\select;

class GameConsoleController extends Controller
{
    public function __construct(
        protected GameService $game
    )
    {}

    public function play(Command $command)
    {
        $gender = $command->option('gender');

        if (!$gender) {
            $command->info('No gender has been specified. Please select one:');
            $gender = select(
                label: 'Select the gender:',
                options: $this->game::GENDERS
            );
        } else {
            if (!in_array($gender, $this->game::GENDERS)) {
                $command->error('The entered gender is invalid. Please select one of the following:');
                $gender = select(
                    label: 'Select the gender:',
                    options: $this->game::GENDERS
                );
            }
        }

        $force = $command->option('force');

        $result = $this->game->play($gender, $force);

        if (is_numeric($result)) {
            if ($result == 0) {
                $command->error('There are no participants available in the database.');
            } elseif ($result > 0 && !$force) {
                $command->warn('The number of participants is not a base 2 power, therefore there may be an unexpected result.');
                if (!$command->confirm('Do you want to proceed anyway?')) {
                    return Command::FAILURE;
                }
                $this->game->play($gender, true);
            }
        }

        $winnerName = $result->name;
        $winnerId = $result->id;
        $winnerText = "The winner is $winnerName with id = $winnerId";
        $command->info($winnerText);
        
        return Command::SUCCESS;
    }

    public function reset(Command $command)
    {
        $this->game->reset();

        $command->info("Game reseted successfully");
        
        return Command::SUCCESS;
    }

    public function replay(Command $command)
    {
        $this->reset($command);

        return $this->play($command);
    }
}
