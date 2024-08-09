<?php

namespace App\Console\Commands;

use App\Http\Controllers\GameConsoleController;
use Illuminate\Console\Command;

class ResetGame extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(GameConsoleController $gameController)
    {
        return $gameController->reset($this);
    }
}
