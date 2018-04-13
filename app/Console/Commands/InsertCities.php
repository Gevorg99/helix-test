<?php

namespace App\Console\Commands;

use App\Events\InsertCitiesEvent;
use Illuminate\Console\Command;

class InsertCities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'insert:cities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inserting cities from given link';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        event(new InsertCitiesEvent());
    }
}
