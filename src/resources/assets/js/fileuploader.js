$('.row_field').each(function(index, value){
    $('#images_data_'+$(value).val()).val();
      // enable fileuploader plugin
      $('#fileuploader-voyager-'+$(value).val()).fileuploader({
          extensions: ['jpg', 'jpeg', 'png', 'gif', 'bmp'],
          changeInput: ' ',
          theme: 'thumbnails',
          enableApi: true,
          addMore: true,
          thumbnails: {
              box: '<div class="fileuploader-items">' + '<ul class="fileuploader-items-list">' + '<li class="fileuploader-thumbnails-input"><div class="fileuploader-thumbnails-input-inner"><i>+</i></div></li>' + '</ul>' + '</div>',
              item: '<li class="fileuploader-item file-has-popup">' + '<div class="fileuploader-item-inner">' + '<div class="type-holder">${extension}</div>' + '<div class="actions-holder">' + '<a class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i></i></a>' + '</div>' + '<div class="thumbnail-holder">' + '${image}' + '<span class="fileuploader-action-popup"></span>' + '</div>' + '<div class="content-holder"><h5>${name}</h5><span>${size2}</span></div>' + '<div class="progress-holder">${progressBar}</div>' + '</div>' + '</li>',
              item2: '<li class="fileuploader-item file-has-popup">' + '<div class="fileuploader-item-inner">' + '<div class="type-holder">${extension}</div>' + '<div class="actions-holder">' + '<a href="${file}" class="fileuploader-action fileuploader-action-download" title="${captions.download}" download><i></i></a>' + '<a class="fileuploader-action fileuploader-action-remove" title="${captions.remove}"><i></i></a>' + '</div>' + '<div class="thumbnail-holder">' + '${image}' + '<span class="fileuploader-action-popup"></span>' + '</div>' + '<div class="content-holder"><h5>${name}</h5><span>${size2}</span></div>' + '<div class="progress-holder">${progressBar}</div>' + '</div>' + '</li>',
              startImageRenderer: true,
              canvasImage: false,
              _selectors: {
                  list: '.fileuploader-items-list',
                  item: '.fileuploader-item',
                  start: '.fileuploader-action-start',
                  retry: '.fileuploader-action-retry',
                  remove: '.fileuploader-action-remove'
              },
              onItemShow: function onItemShow(item, listEl, parentEl, newInputEl, inputEl) {
                  var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                      api = $.fileuploader.getInstance(inputEl.get(0));
  
                  plusInput.insertAfter(item.html)[api.getOptions().limit && api.getChoosedFiles().length >= api.getOptions().limit ? 'hide' : 'show']();
  
                  if (item.format == 'image') {
                      item.html.find('.fileuploader-item-icon').hide();
                  }
              }
          },
          dragDrop: {
              container: '.fileuploader-thumbnails-input'
          },
          afterRender: function afterRender(listEl, parentEl, newInputEl, inputEl) {
              var plusInput = listEl.find('.fileuploader-thumbnails-input'),
                  api = $.fileuploader.getInstance(inputEl.get(0));
  
              plusInput.on('click', function () {
                  api.open();
              });
          },
  
          sorter: {
              selectorExclude: null,
              placeholder: null,
              scrollContainer: window,
              onSort: function onSort(list, listEl, parentEl, newInputEl, inputEl) {
                  // onSort callback
                  $('#images_data_sort_'+$(value).val()).val();
                  var sorts = [];
                  $.each(list, function (index, value) {
                      sorts.push({
                          name: value.name,
                          type: value.type,
                          size: value.size,
                          file: value.file,
                          data: {
                              url: value.data.url
                          }
                      });
                  });
  
                  $('#images_data_sort_'+$(value).val()).val(JSON.stringify(sorts));
              }
          }
      });
  });