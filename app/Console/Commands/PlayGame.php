<?php

namespace App\Console\Commands;

use App\Http\Controllers\GameConsoleController;
use Illuminate\Console\Command;

class PlayGame extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:play {--gender= : [male/female]} {--force : Force the game to start}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute the game';

    /**
     * Execute the console command.
     */
    public function handle(GameConsoleController $gameController)
    {
        return $gameController->play($this);
    }
}
