<?php

namespace App\Console\Commands;

use App\Models\Participant;
use Illuminate\Console\Command;

class CreateParticipant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'participant:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new participant';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $data = [
            'name' => $this->ask('Enter the name:'),
            'skill' => $this->askInt('Enter the skill (0 - 100):'),
            'strength' => $this->askInt('Enter the strength (0 - 100):'),
            'speed' => $this->askInt('Enter the speed (0 - 100):'),
            'reaction' => $this->askInt('Enter the reaction (0 - 100):'),
            'is_defeated' => 0,
        ];
    
        Participant::create($data);

        $this->info('Participant created successfully');

        return Command::SUCCESS;
    }

    private function askInt(string $question, string|null $default = null): int
    {
        $number = $this->ask($question, $default);
        while (!is_numeric($number)) {
            $this->error('The value must be an integer.');
            $number = $this->ask($question, $default);
        }

        return (int) $number;
    }
}
