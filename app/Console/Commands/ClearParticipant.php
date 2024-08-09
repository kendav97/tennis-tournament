<?php

namespace App\Console\Commands;

use App\Http\Controllers\ParticipantController;
use Illuminate\Console\Command;
use App\Models\Participant;

class ClearParticipant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'participants:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all participants of database';

    /**
     * Execute the console command.
     */
    public function handle(ParticipantController $participantController)
    {
        $confirm = $this->confirm('Are you sure you want to delete all participants?');   

        if ($confirm) {
            $participantController->clear();

            $this->info('All participants deleted successfully');
            return Command::SUCCESS;
        }

        $this->info('No changes have been made to the database');
        return Command::SUCCESS;
    }
}
