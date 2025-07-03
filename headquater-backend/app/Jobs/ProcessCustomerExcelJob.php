<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use App\Imports\CustomersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessCustomerExcelJob implements ShouldQueue, Batchable
{
    use Queueable;

    public $file;

    /**
     * Create a new job instance.
     */
    public function __construct($file)
    {
        //
        $this->file = $file;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
        Excel::queueImport(new CustomersImport, $this->file);
    }
}
