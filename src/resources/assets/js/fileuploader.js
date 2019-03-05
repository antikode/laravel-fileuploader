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