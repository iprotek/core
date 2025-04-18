<?php

namespace iProtek\Core\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Illuminate\Support\Facades\Log; 
use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use iProtek\Core\Helpers\FileImportHelper;

class FileImportBatchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'file-import-batch:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Preparation for import batch file.';

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
     * @return int
     */
    public function handle()
    { 
        //CHECKING AND PROCESSING BATCH AND PREPARING DATA
        FileImportHelper::startBatchProcessing();

        echo "Batch import processed completed!";

    }
}
