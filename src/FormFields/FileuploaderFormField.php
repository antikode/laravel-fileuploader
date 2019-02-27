<?php

namespace Antikode\Fileuploader\FormFields;

use TCG\Voyager\FormFields\AbstractHandler;

class FileuploaderFormField extends AbstractHandler
{
    protected $codename = 'fileuploader';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('antikode.fileuploader::formfields.fileuploader', [
            'row' => $row,
            'options' => $options,
            'dataType' => $dataType,
            'dataTypeContent' => $dataTypeContent
        ]);
    }
}