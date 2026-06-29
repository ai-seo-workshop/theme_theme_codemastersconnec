<?php

namespace App\Providers;

use App\Models\MaterielTask;
use App\Services\CategoryService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class ViewServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        View::composer('*', function ($view) {
            $locale = app()->getLocale();
            $logoTask = MaterielTask::byLanguage($locale)
                ->where('category_id', 0)
                ->byType(MaterielTask::TYPE_LOGO)
                ->first();

            $logoUrl = trim((string) data_get($logoTask, 'content', ''));
            if ($logoUrl === '') {
                $logoUrl = config('app.logo_url', 'https://codemastersconnect.com/wp-content/uploads/2022/08/asfgv-01.png');
            }

            $view->with('categories', CategoryService::getActiveCategories());
            $view->with('logoUrl', $logoUrl);
        });
    }
}
