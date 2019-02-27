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
        $this->loadViewsFrom(__DIR__ . '/views', 'antikode.fileuploader');
    }
}
