<?php

namespace App\Providers;

use App\Models\Tender;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        App::setLocale('de'); // Set the application locale
        Carbon::setLocale('de'); // Set Carbon's locale

        View::composer('*', function ($view) {
            if($view->getName() == "include.header"){
                if (Auth::check()) {
                    $user = Auth::user();

                    if ($user->isAdmin()) {
                        $tenders = Tender::all();
                    } else {
                        $tenders = Tender::whereHas('users', function ($query) use ($user) {
                            $query->where('users.id', $user->id);
                        })->get();
                    }

                    $statusCounts = $tenders->groupBy('status')->map->count();
                    $view->with('statusCounts', $statusCounts);
                }
            }

            if($view->getName() == "include.tender_deadline_section"){
                if (Auth::check()) {
                    $user = Auth::user();

                    if (!$user->isAdmin()) {
                        $tenders = Tender::whereHas('users', function ($query) use ($user) {
                            $query->where('users.id', $user->id);
                        })->get();
                    }
                    $view->with('tenders', $tenders);

                }
            }
        });
    }
}
