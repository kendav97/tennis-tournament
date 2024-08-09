<?php

namespace App\Console\Commands;

use App\Http\Controllers\ParticipantConsoleController;
use Illuminate\Console\Command;

class ListParticipant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'participants:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows participants in table format';

    /**
     * Execute the console command.
     */
    public function handle(ParticipantConsoleController $participantController)
    {
        return $participantController->list($this);
    }
}
