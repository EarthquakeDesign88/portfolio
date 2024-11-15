<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CompanySetup;

class QuotaMonthlyUpdate extends Command
{
    protected $signature = 'quota:requota';
    protected $description = 'Update remaining_quota in CompanySetup model for each row';

    public function handle()
    {
        $companySetups = CompanySetup::all();

        foreach ($companySetups as $companySetup) {
            $companySetup->update([
                'remaining_quota' => $companySetup->total_quota
            ]);
        }

        $this->info('Quota Monthly update completed successfully!');
    }
}
