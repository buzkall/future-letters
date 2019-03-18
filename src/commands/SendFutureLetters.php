<?php

namespace Buzkall\FutureLetters\Commands;

use Buzkall\FutureLetters\FutureLetterController;
use Illuminate\Console\Command;

class SendFutureLetters extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:future-letters';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send the future letter when the time comes';

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
        $future_letter = new FutureLetterController();
        $future_letter->cron();
    }
}
