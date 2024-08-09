<?php

namespace App\Console\Commands;

use App\Http\Controllers\ParticipantConsoleController;
use Illuminate\Console\Command;

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
    public function handle(ParticipantConsoleController $participantController)
    {
        return $participantController->clear($this);
    }
}
