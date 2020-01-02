<?php namespace Crudvel\Commands;

use Illuminate\Console\Command;

class CvScaff extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cv-scaff';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Advanced scaffolding command';

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
        //
    }
}
