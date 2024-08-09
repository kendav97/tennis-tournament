<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Http\Helpers\NumericHelper;
use Illuminate\Console\Command;
use function Laravel\Prompts\select;
use function Laravel\Prompts\text;
use function Laravel\Prompts\error;

class ParticipantConsoleController extends Controller
{
    public function __construct(
        protected Command $command,
        protected Participant $participant,
    )
    {}

    public function list(Command $command)
    {
        $command->table(
            ['ID', 'Name', 'Skill', 'Strength', 'Speed', 'Reaction', 'Is defeated'],
            Participant::all(['id', 'name', 'skill', 'strength', 'speed', 'reaction', 'is_defeated'])->toArray()
        );

        return Command::SUCCESS;
    }

    public function create(Command $command)
    {
        $data = [
            'name' => text('Enter the name:'),
            'skill' => $this->askInt($command, 'Enter the skill (0 - 100):'),
            'strength' => $this->askInt($command, 'Enter the strength (0 - 100):'),
            'speed' => $this->askInt($command, 'Enter the speed (0 - 100):'),
            'reaction' => $this->askInt($command, 'Enter the reaction (0 - 100):'),
            'is_defeated' => 0,
        ];
    
        $this->participant::create($data);

        $command->info('Participant created successfully');

        return Command::SUCCESS;
    }

    private function askInt(Command $command, string $question, string $default = ''): int
    {
        $number = text($question, $default);
        while (!is_numeric($number)) {
            error('The value must be an integer.');
            $number = text($question, $default);
        }

        return (int) $number;
    }

    public function seed(Command $command)
    {
        $options = NumericHelper::generatePowersOfTwo($this->participant::SEED_NUMBER_OPTIONS);

        $number = select(
            'How many participants do you want to create randomly?',
            $options,
        );

        $this->participant->seed($number);

        $command->info("$number participants created successfully");

        return Command::SUCCESS;
    }

    public function clear(Command $command)
    {
        $confirm = $command->confirm('Are you sure you want to delete all participants?');   

        if ($confirm) {
            $this->participant->clear();

            $command->info('All participants deleted successfully');
            return Command::SUCCESS;
        }

        $command->info('No changes have been made to the database');
        return Command::SUCCESS;
    }
}
