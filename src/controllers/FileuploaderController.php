<?php

namespace Antikode\Fileuploader\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image as InterventionImage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class FileuploaderController extends Controller
{
    /**
     * @param Request $request
     * @param String $image_name
     * 
     * @return array
     */
    public function request($request, $image_name, $page)
    {
        $filesPath = [];
        
        if($request->hasfile($image_name)){

            $filesPath = $this->uploadMultiple($request->file($image_name), $page);
            
            //get image from database and merge with new image data
            if($request->get('images_data_'.$image_name)){
                $filesPath = array_merge(json_decode($request->get('images_data_'.$image_name)), $filesPath);
            }else{
                $filesPath = $filesPath;
            }
        }else{
            //check if image sort
            if($request->get('images_data_sort_'.$image_name))
                $filesPath = json_decode($request->get('images_data_sort_'.$image_name));
        }
        
        return $filesPath;
    }

    private function uploadMultiple($files, $page)
    {
        $filesPath = [];
        foreach($files as $key => $file)
        {
            $filesPath[$key] = $this->upload($file, $page);
        }

        return $filesPath;
    }

    private function upload($file, $page)
    {
        $filesPath = [];
        $image = InterventionImage::make($file);

        $resize_width = null;
        $resize_height = null;

        if (isset($this->options->resize) && (
                isset($this->options->resize->width) || isset($this->options->resize->height)
            )) {
            if (isset($this->options->resize->width)) {
                $resize_width = $this->options->resize->width;
            }
            if (isset($this->options->resize->height)) {
                $resize_height = $this->options->resize->height;
            }
        } else {
            $resize_width = $image->width();
            $resize_height = $image->height();
        }

        $resize_quality = isset($this->options->quality) ? intval($this->options->quality) : 75;

        $filename = Str::random(20);
        $path = $page.DIRECTORY_SEPARATOR.date('FY').DIRECTORY_SEPARATOR;
        
        array_push($filesPath, $path.$filename.'.'.$file->getClientOriginalExtension());
        $filePath = $path.$filename.'.'.$file->getClientOriginalExtension();
        
        $image = $image->resize(
            $resize_width,
            $resize_height,
            function (Constraint $constraint) {
                $constraint->aspectRatio();
                if (isset($this->options->upsize) && !$this->options->upsize) {
                    $constraint->upsize();
                }
            }
        )->encode($file->getClientOriginalExtension(), $resize_quality);

        Storage::disk(config('voyager.storage.disk'))->put($filePath, (string) $image, 'public');

        $fileMetaData = [
            "name" => $filename,
            "type" => $image->mime(),
            "size" => $image->filesize(),
            "file" => 'storage/'.$filePath,
            "data" => [
                "url" => $filePath
            ]
        ];

        return $fileMetaData;
    }
}
