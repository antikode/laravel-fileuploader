**Require**
* Install Laravel Voyager
    ```
    https://voyager.readme.io/docs/installation
    ```
* Install Antikode Fileuploader
   * Generate Token
   ```
    https://github.com/settings/tokens
   ```
   * Edit composer.json
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

**Setup Voyager**

Open _app/config/voyager.php_ add _additional_js_ and _additional_css_

```php
// Here you can specify additional assets you would like to be included in the master.blade
    'additional_css' => [
        //'css/custom.css',
        'css/font/font-fileuploader.css',
        'css/jquery.fileuploader.min.css',
    ],

    'additional_js' => [
        //'js/custom.js',
        'js/jquery.fileuploader.min.js',
        'js/fileuploader.js'
    ],
```


**Setup Javascript**

_save this file to public/js/fileuploader.js_
```javascript
$(document).ready(function() {
	$('#images_data').val();
	
	// enable fileuploader plugin
	$('#fileuploader-voyager').fileuploader({
        extensions: ['jpg', 'jpeg', 'png', 'gif', 'bmp'],
        addMore: true,
        thumbnails: {
            onItemShow: function(item) {
                // add sorter button to the item html
                item.html.find('.fileuploader-action-remove').before('<a class="fileuploader-action fileuploader-action-sort" title="Sort"><i></i></a>');
            }
        },
        dragDrop: {
            container: '.fileuploader-thumbnails-input'
        },
        afterRender: function(listEl, parentEl, newInputEl, inputEl) {
            var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                api = $.fileuploader.getInstance(inputEl.get(0));
        
            plusInput.on('click', function() {
                api.open();
            });
        },
        
		sorter: {
			selectorExclude: null,
			placeholder: null,
			scrollContainer: window,
			onSort: function(list, listEl, parentEl, newInputEl, inputEl) {
                // onSort callback
                $('#images_data_sort').val();
                const sorts = [];
                $.each(list, function(index, value){
                    sorts.push({
                        name: value.name,
                        type : value.type,
                        size : value.size,
                        file : value.file,
                        data : {
                            url : value.data.url,
                        }
                    });
                });
                
                $('#images_data_sort').val(JSON.stringify(sorts));
			}
		}
	});
    
});
```

**Setup Observer**

_change Project if model name not equal to Project_
```
php artisan make:observer ProjectObserver --model=Project
```

Open app/Providers/AppServiceProvider.php, add this in **boot** function
```
App\Project::observe(App\Observers\ProjectObserver::class);
```

Update file app/Observers/ProjectObserver.php
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
        $filesPath = [];
        
        if($this->request->hasfile('images')){
            foreach($this->request->file('images') as $key => $file)
            {
                $filesPath[$key] = $files->multiple($file);
            }

            //set images to image metadata json
            $project->images = json_encode($filesPath);
        }
        
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
        $filesPath = [];

        if($this->request->hasfile('images')){
            foreach($this->request->file('images') as $key => $file)
            {
                $filesPath[$key] = $files->multiple($file);
            }

            //get image from database and merge with new image data
            if($this->request->images_data){
                $filesPath = array_merge(json_decode($this->request->images_data), $filesPath);
            }
        }else{
            //check if image sort
            if($this->request->images_data_sort)
                $filesPath = json_decode($this->request->images_data_sort);
        }

        //set images to image metadata json
        $project->images = json_encode($filesPath);
    }
}

```