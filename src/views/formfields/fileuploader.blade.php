
<style>
    .fileuploader {
        max-width: 643px;
    }
</style>

{{-- preload files --}}
@if (isset($dataTypeContent->{$row->field}))
    @php
        $preloadedFiles = [];
    @endphp
    @foreach (json_decode($dataTypeContent->{$row->field}) as $item)
        @php
            $preloadedFiles[] = array(
                "name" => $item->name,
                "type" => $item->type,
                "size" => $item->size,
                "file" => url($item->file),
                "data" => array(
                    "url" => $item->data->url,
                ),
            );
        @endphp
    @endforeach

    @php
        $preloadedFiles = json_encode($preloadedFiles);
    @endphp

@endif

<input type="file"
    class="form-control fileuploader-voyager"
    name={{ $row->field }}[]
    id="fileuploader-voyager"
    data-name="{{ $row->display_name }}"
    @if($row->required == 1) required @endif
    step="any"
    placeholder="{{ isset($options->placeholder)? old($row->field, $options->placeholder): $row->display_name }}"
    data-fileuploader-files={{ isset($preloadedFiles) ? $preloadedFiles : '' }}>

<input type="hidden" name="images_data" id="images_data" value="{{($dataTypeContent->{$row->field})}}">
<input type="hidden" name="images_data_sort" id="images_data_sort" value="{{($dataTypeContent->{$row->field})}}">
