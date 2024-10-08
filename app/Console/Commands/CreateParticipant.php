<?php

namespace App\Console\Commands;

use App\Http\Controllers\ParticipantConsoleController;
use App\Models\Participant;
use Illuminate\Console\Command;

class CreateParticipant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'participants:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new participant';

    /**
     * Execute the console command.
     */
    public function handle(ParticipantConsoleController $participantController)
    {
        return $participantController->create($this);
    }
}
