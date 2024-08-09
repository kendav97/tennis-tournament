<?php

namespace App\Console\Commands;

use App\Models\Participant;
use Illuminate\Console\Command;

class ShowParticipant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'participants:show';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shows participants in table format';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->table(
            ['ID', 'Name', 'Skill', 'Strength', 'Speed', 'Reaction', 'Is defeated'],
            Participant::all(['id', 'name', 'skill', 'strength', 'speed', 'reaction', 'is_defeated'])->toArray()
        );

        return Command::SUCCESS;
    }
}
