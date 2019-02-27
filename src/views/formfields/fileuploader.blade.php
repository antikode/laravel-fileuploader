<script src="https://code.jquery.com/jquery-3.2.1.min.js" crossorigin="anonymous"></script>
<style>
      body {
            font-family: 'Roboto', sans-serif;
            font-size: 14px;
    line-height: normal;
            background-color: #fff;

            margin: 0;
            padding: 20px;
      }

.fileuploader {
    max-width: 460px;
}
</style>

<input type="file"
    class="form-control"
    name="files"
    data-name="{{ $row->display_name }}"
    @if($row->required == 1) required @endif
    step="any"
    placeholder="{{ isset($options->placeholder)? old($row->field, $options->placeholder): $row->display_name }}"
    value="@if(isset($dataTypeContent->{$row->field})){{ old($row->field, $dataTypeContent->{$row->field}) }}@else{{old($row->field)}}@endif">


<script>
$(document).ready(function() {
	
	// enable fileuploader plugin
	$('input[name="files"]').fileuploader({
		addMore: true,
        thumbnails: {
            onItemShow: function(item) {
                // add sorter button to the item html
                item.html.find('.fileuploader-action-remove').before('<a class="fileuploader-action fileuploader-action-sort" title="Sort"><i></i></a>');
            }
        },
		sorter: {
			selectorExclude: null,
			placeholder: null,
			scrollContainer: window,
			onSort: function(list, listEl, parentEl, newInputEl, inputEl) {
                // onSort callback
			}
		}
	});
	
});
</script>