<?php

namespace Antikode\Fileuploader;

use Illuminate\Support\ServiceProvider;
use TCG\Voyager\Facades\Voyager;
use Antikode\Fileuploader\FormFields\FileuploaderFormField;

class FileuploaderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        Voyager::addFormField(FileuploaderFormField::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__.'/routes.php';
        $this->loadViewsFrom(__DIR__ . '/views', 'antikode.fileuploader');

        //publish assets
        $this->publishes([
            __DIR__.'/public/assets' => public_path('vendor/antikode/fileuploader'),
        ], 'antikode-fileuploader');
    }
}
