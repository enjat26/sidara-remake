/*
Author: Ionix Eternal Studio
Website: https://ionixeternal.co.id/
Contact: support@ionixeternal.co.id
File: Datatables Js File
*/

'use strict'

$(document).ready(function() {
  initializeData();
});

var scope, value;

initialize();

function initialize() {
  initComponents(),
  initButton(),
  initModal();
}

function initializeData() {

}

function initComponents() {
  $('.zoom-gallery').magnificPopup({
		delegate: 'a',
		type: 'image',
		closeOnContentClick: false,
		closeBtnInside: false,
		mainClass: 'mfp-with-zoom mfp-img-mobile',
		image: {
			verticalFit: true,
			titleSrc: function(item) {
				return item.el.attr('title') + ' &middot; <a href="'+item.el.attr('data-source')+'" target="_blank">image source</a>';
			}
		},
		gallery: {
			enabled: true
		},
		zoom: {
			enabled: true,
			duration: 300, // don't foget to change the duration also in CSS
			opener: function(element) {
				return element.find('img');
			}
		}
	});

  $('.select2').select2({
    placeholder: function() {
      $(this).data('placeholder');
    },
  });

  $(".inputtags").tagify({
    transformTag: transformTag,
  });

  $('.dropify').dropify({
    messages: {
      default: "Drag and drop a file here or click",
      replace: "Drag and drop or click to replace",
      remove: "Remove",
      error: "Ooops, something wrong appended."
    },
    error: {
      fileSize: "The file size is too big ({{ value }} max).",
      imageFormat: 'The image format is not allowed ({{ value }} only).'
    }
  });
}

function initButton() {
  $('[key="add-image"]').on("click", function () {
    scope  = $(this).data("scope");
    value   = 'add';

    $('#modal-image .modal-title').text('Unggah Foto/Gambar Baru'),
    $('#modal-image [type="submit"]').text('Unggah');
  });

  $('[key="add-video"]').on("click", function () {
    scope  = $(this).data("scope");
    value   = 'add';

    $('#modal-video .modal-title').text('Unggah Vidio Baru'),
    $('#modal-video [type="submit"]').text('Unggah');
  });

  $('[key="view-gallery"]').on('click', function () {
    updateMethod(false, $(this).data("scope"), $(this).data("val"));
  });

  $('[key="view-video"]').on('play', function () {
    updateMethod(false, $(this).data("scope"), $(this).data("val"));
  });

  $('[key="del-gallery"]').on('click', function () {
    deleteMethod(true, $(this).data("scope"), $(this).data("val"));
  });

  $('[key="local"]').on("click", function () {
    $('#local input').prop('required', true),
    $('#youtube input').prop('required', false);
  });

  $('[key="youtube"]').on("click", function () {
    $('#local input').prop('required', false),
    $('#youtube input').prop('required', true);
  });
}

function initModal() {
  $("#modal-image").on("hidden.bs.modal", function () {
    resetForm('image'),
    $('[name="tags"]').data('tagify').removeAllTags(),
    $('.dropify-clear').click(),
    initializeData();
  });

  $("#modal-video").on("hidden.bs.modal", function () {
    resetForm('video'),
    $('[name="tags"]').data('tagify').removeAllTags(),
    $('.dropify-clear').click(),
    initializeData();
  });
}

function resetForm(form) {
  $('#form-'+form)[0].reset(),
  $('#form-'+form).removeClass('was-validated');
  $('.invalid-tooltip').remove();
  scope   = '';
  value   = '';
}

// generate a random color (in HSL format, which I like to use)
function getRandomColor() {
    function rand(min, max) {
        return min + Math.random() * (max - min);
    }

    var h = rand(1, 360)|0,
        s = rand(40, 70)|0,
        l = rand(65, 72)|0;

    return 'hsl(' + h + ',' + s + '%,' + l + '%)';
}

function transformTag(tagData) {
    tagData.style = "--tag-bg:" + getRandomColor();

    if( tagData.value.toLowerCase() == 'shit' )
       tagData.value = 's✲✲t'
}

$("#form-image").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'gallerys/store?scope='+scope+'&id='+value,
      data: new FormData($('#form-image')[0]),
      type: "POST",
      contentType: false,
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          $('#modal-image').modal('hide'), location.reload();
        }, 2e3)
      },
      error: function() {
        setTimeout(function() {
          requestSuccess('modal');
        }, 2e3)
      },
    });
  }
});

$("#form-video").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'gallerys/store?scope='+scope+'&id='+value,
      data: new FormData($('#form-video')[0]),
      type: "POST",
      contentType: false,
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          $('#modal-video').modal('hide'), location.reload();
        }, 2e3)
      },
      error: function() {
        setTimeout(function() {
          requestSuccess('modal');
        }, 2e3)
      },
    });
  }
});

function updateMethod(redirect, scope, id) {
  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'gallerys/update?scope='+scope+'&id='+id,
    type: "POST",
    success: function(response) {
      if (redirect == true) {
        location.reload();
      } else {
        pushToastr(response.type, response.header, response.message.success), initializeData();
      }
    },
    error: function() {
      setTimeout(function() {
        initializeData();
      }, 2e3)
    },
  });
}

function deleteMethod(redirect, scope, id) {
  Swal.fire({
    title: "Apakah Anda yakin?",
    text: "Anda tidak akan dapat mengembalikan ini!",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Hapus",
    cancelButtonText: "Batal",
    customClass: {
      confirmButton: 'btn btn-success me-2',
      cancelButton: 'btn btn-danger',
    },
    buttonsStyling: false
  }).then((result) => {
    if(result.value) {
      $.ajax({
          url: $('meta[name=site-url]').attr("content")+'gallerys/delete?scope='+scope+'&id='+id,
          type: "DELETE",
          success: function(response){
            if (redirect == true) {
              location.reload();
            } else {
              pushSwal(response.type, response.header, response.message.success), initializeData();
            }
          },
      });
    } else {
      pushSwalCancel()
    }
  });
}
