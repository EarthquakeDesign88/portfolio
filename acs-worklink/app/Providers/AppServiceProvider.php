<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\Position; // Import models
use App\Models\Department;
use App\Models\JobQualification;
use App\Models\FormCreate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // View composer for navbar
        View::composer('partials.navbar', function ($view) {
            $countPositions = Position::count();
            $countDepartments = Department::count();
            $countJobQualifications = JobQualification::count();
            $countReadEmails = FormCreate::where('mail_status', '=', '0')->count();


            $view->with([
                'countPositions' => $countPositions,
                'countDepartments' => $countDepartments,
                'countJobQualifications' => $countJobQualifications,
                'countReadEmails' => $countReadEmails,
            ]);
        });
    }
}
