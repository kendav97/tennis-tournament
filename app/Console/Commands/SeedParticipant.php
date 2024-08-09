<?php

namespace App\Console\Commands;

use App\Http\Controllers\ParticipantConsoleController;
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
    public function handle(ParticipantConsoleController $participantController)
    {
        return $participantController->seed($this);
    }
}
