**Require**
* **Install Laravel Voyager**
    ```
    https://voyager.readme.io/docs/installation
    ```
* **Update Antikode Fileuploader**
    * **Composer Update**
        ```console
            composer update antikode/fileuploader
        ```
    * **Remove folder _public/vendor/antikode_**
    * **Publish vendor assets**
        ```
            php artisan vendor:publish --tag=antikode-fileuploader
        ```

* **Install Antikode Fileuploader**
   * **Generate Token**
        ```
            https://github.com/settings/tokens
        ```
   * **Edit composer.json**
        ```
            "repositories": {
                ...
                "fileuploader": {
                    "type": "vcs",
                    "no-api": true,
                    "url": "https://github.com/antikode/laravel-fileuploader.git"
                }
            }
        ```
   * **Install Package**
        ```console
            composer require antikode/fileuploader
        ```
   * **Open _config/app.php_ add this to providers**
        ```php
            'providers' = [
                ...
                Antikode\Fileuploader\FileuploaderServiceProvider::class,
                ...
            ]
        ```
   * **Publish vendor assets**
        ```
            php artisan vendor:publish --tag=antikode-fileuploader
        ```

    * **Open _config/voyager.php_ add _additional_js_ and _additional_css_**

        ```php
        // Here you can specify additional assets you would like to be included in the master.blade
        'additional_css' => [
            //'css/custom.css',
            'vendor/antikode/fileuploader/css/app.css',
            'vendor/antikode/fileuploader/css/font/font.css',
        ],

        'additional_js' => [
            //'js/custom.js',
            'vendor/antikode/fileuploader/js/app.js',
        ],
        ```
    * **Setup Observer**

        _change ProjectObserver and --model=Project if model name not equal to Project_
        ```
        php artisan make:observer ProjectObserver --model=Project
        ```

        * Open app/Providers/AppServiceProvider.php, add this in **boot** function
            ```php
            \App\Project::observe(\App\Observers\ProjectObserver::class);
            ```

        * Update file app/Observers/ProjectObserver.php
            ```php

            <?php

            namespace App\Observers;

            use App\Project;
            use Antikode\Fileuploader\Controllers\FileuploaderController as Fileuploader;
            use Illuminate\Http\Request;

            class ProjectObserver
            {
                protected $request;

                public function __construct(Request $request)
                {
                    $this->request = $request;
                }
                /**
                * Handle the project "creating" event.
                *
                * @param  \App\Project  $project
                * @return void
                */
                public function creating(Project $project)
                {
                    $files = new Fileuploader();
                    
                    $filesPath = $files->request($this->request, 'input_name');

                    //set images to image metadata json
                    $project->images = json_encode($filesPath);

                    //add another filePath if necessary
                    /*
                    $filePath2 = $files->request($this->request, 'input_name2');
                    $project->mobile_images = json_encode($filePath2);
                    */
                    
                }

                /**
                * Handle the project "updating" event.
                *
                * @param  \App\Project  $project
                * @return void
                */
                public function updating(Project $project)
                {
                    $files = new Fileuploader();
                    
                    $filesPath = $files->request($this->request, 'input_name');

                    //set images to image metadata json
                    $project->images = json_encode($filesPath);

                    //add another filePath if necessary
                    /*
                    $filePath2 = $files->request($this->request, 'input_name2');
                    $project->mobile_images = json_encode($filePath2);
                    */
                }

                /**
                * Handle the project "saving" event.
                *
                * @param  \App\Project  $project
                * @return void
                */
                public function saving(Project $project)
                {
                    $files = new Fileuploader();
                    
                    $filesPath = $files->request($this->request, 'input_name');

                    //set images to image metadata json
                    $project->images = json_encode($filesPath);

                    //add another filePath if necessary
                    /*
                    $filePath2 = $files->request($this->request, 'input_name2');
                    $project->mobile_images = json_encode($filePath2);
                    */
                }
            }


            ```
