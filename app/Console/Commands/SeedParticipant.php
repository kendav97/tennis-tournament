<?php

namespace App\Console\Commands;

use App\Http\Controllers\ParticipantController;
use App\Http\Helpers\NumericHelper;
use App\Models\Participant;
use Illuminate\Console\Command;

class SeedParticipant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'participants:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create random participants';

    /**
     * Execute the console command.
     */
    public function handle(ParticipantController $participantController)
    {
        $options = NumericHelper::generatePowersOfTwo(ParticipantController::SEED_NUMBER_OPTIONS);

        $number = $this->choice(
            'How many participants do you want to create randomly?',
            $options,
        );

        $participantController->seed($number);

        $this->info("$number participants added successfully");

        return Command::SUCCESS;
    }
}
