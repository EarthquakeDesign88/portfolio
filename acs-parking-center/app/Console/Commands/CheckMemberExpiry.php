<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Member;
use Carbon\Carbon;

class CheckMemberExpiry extends Command
{
    protected $signature = 'member:check-expiry';
    protected $description = 'Check member expiry and update status if expired';

    public function handle()
    {
        $members = Member::where('expiry_date', '=', Carbon::today())->get();

        foreach ($members as $member) {
            $member->update(['member_status' => 'inactive']);
        }

        $this->info('Member expiry checked and updated successfully.');
    }
}
