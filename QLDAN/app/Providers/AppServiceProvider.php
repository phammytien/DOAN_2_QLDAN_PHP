<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
// use App\Models\Category; // Model does not exist
use App\Models\CanBoQL;

use Illuminate\Pagination\Paginator;

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
        // Route model binding: map 'canbo' parameter to CanBoQL model
        Route::model('canbo', CanBoQL::class);
        
        Paginator::useBootstrapFive();

        // Share top categories for navigation in user views (cached 10 minutes)
        // View::composer('user.*', function ($view) {
        //     $navCategories = Cache::remember('nav_categories_top7', 600, function () {
        //         return Category::query()
        //             ->orderBy('name')
        //             ->limit(7)
        //             ->get(['id', 'name']);
        //     });
        //
        //     $view->with('navCategories', $navCategories);
        // });

        // Share notification count for student layout
        if (class_exists(\App\Models\ThongBao::class)) {
            View::composer('layouts.sinhvien', function ($view) {
                $notificationCount = \App\Models\ThongBao::whereIn('DoiTuongNhan', ['SV', 'TatCa'])->count();
                $view->with('notificationCount', $notificationCount);
            });
        }

        // Share mock admin info and notification count for admin layout
        View::composer('layouts.admin', function ($view) {
            $adminInfo = (object)[
                'name' => 'Nguyễn Văn Anh',
                'email' => 'vananh.dthu.edu.vn'
            ];
            
            $notificationCount = class_exists(\App\Models\ThongBao::class) ? \App\Models\ThongBao::count() : 0;
            $requestResetCount = class_exists(\App\Models\TaiKhoan::class) ? \App\Models\TaiKhoan::where('TrangThai', 'request_reset')->count() : 0;
            
            $view->with('adminInfo', $adminInfo)
                 ->with('notificationCount', $notificationCount)
                 ->with('requestResetCount', $requestResetCount);
        });
    }
}
