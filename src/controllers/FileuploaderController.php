<?php

namespace Antikode\Fileuploader\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Constraint;
use Intervention\Image\Facades\Image as InterventionImage;
use Illuminate\Support\Str;

class FileuploaderController extends Controller
{
    public function multiple($file)
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
        $path = 'projects'.DIRECTORY_SEPARATOR.date('FY').DIRECTORY_SEPARATOR;
        
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

        return $filesPath;
    }
}
