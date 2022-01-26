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
var district;

initialize();

function initialize() {
  initComponents(),
  initButton(),
  initModal();
}

function initializeData() {
  fetchAsset();
}

function initComponents() {
  $('.select2').select2({
    placeholder: function() {
      $(this).data('placeholder');
    },
  });

  $('[data-provide="yearpicker"]').datepicker({
    format: "yyyy",
    viewMode: "years",
    minViewMode: "years",
  });

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

function initButton()
{
  $('[key="add-image"]').on("click", function () {
    scope  = $(this).data("scope");
    value  = 'add';

    $('#modal-image .modal-title').text('Unggah Foto/Gambar Baru'),
    $('#modal-image [type="submit"]').text('Unggah');
  });

  $('[key="upd-resub"]').on("click", function () {
    scope  = $(this).data("scope");
    value   = 'add';

    Swal.fire({
      title: "Daftar Ulang Data",
      text: "Anda akan mendaftarkan ulang Data ini untuk kembali diajukan?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Ya",
      cancelButtonText: "Batal",
      customClass: {
        confirmButton: 'btn btn-primary me-2',
        cancelButton: 'btn btn-danger',
      },
      buttonsStyling: false
    }).then((result) => {
      if(result.value) {
        $.ajax({
          url:  $('meta[name=site-url]').attr("content")+'assets/store?scope='+scope+'&params='+$('meta[name=params]').attr("content")+'&id='+value,
          data: "action="+result.value,
          type: "POST",
          success: function(response) {
            setTimeout(function() {
              location.reload();
            }, 2e3)
          },
        });
      } else {
        pushSwalCancel()
      }
    });
  });

  $('[key="upd-verify"]').on("click", function () {
    scope  = $(this).data("scope");
    value   = 'add';

    Swal.fire({
      title: "Verifikasi Data",
      text: "Anda akan memverifikasi Data ini?",
      icon: "question",
      showCancelButton: true,
      showDenyButton: true,
      confirmButtonText: "Terima",
      denyButtonText: "Tolak",
      cancelButtonText: "Batal",
      customClass: {
        confirmButton: 'btn btn-success me-2',
        denyButton: 'btn btn-warning me-2',
        cancelButton: 'btn btn-danger',
      },
      buttonsStyling: false
    }).then((result) => {
      if(result.value == true || result.value == false) {
        $.ajax({
          url:  $('meta[name=site-url]').attr("content")+'assets/store?scope='+scope+'&params='+$('meta[name=params]').attr("content")+'&id='+value,
          data: "action="+result.value,
          type: "POST",
          success: function(response) {
            setTimeout(function() {
              location.reload();
            }, 2e3)
          },
        });
      } else {
        pushSwalCancel()
      }
    });
  });

  $('[key="del-asset"]').on("click", function () {
    scope   = $('meta[name=scope]').attr("content");
    value   = $('meta[name=params]').attr("content");

    Swal.fire({
      title: "Hapus Data",
      text: "Anda akan menghapus Data ini dari sistem?",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Ya",
      cancelButtonText: "Batal",
      customClass: {
        confirmButton: 'btn btn-primary me-2',
        cancelButton: 'btn btn-danger',
      },
      buttonsStyling: false
    }).then((result) => {
      if(result.value) {
        $.ajax({
          url:  $('meta[name=site-url]').attr("content")+'assets/delete?scope='+scope+'&params=purge'+'&arguments='+$('meta[name=arguments]').attr("content")+'&id='+value,
          type: "DELETE",
          success: function(response) {
            setTimeout(function() {
              window.location.href = response.data.url;
            }, 2e3)
          },
        });
      } else {
        pushSwalCancel()
      }
    });
  });

  $('[name="province"]').on('change', function () {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'areas/list',
      data: "format=Dropdown&scope="+$(this).data("scope")+"&id="+$(this).val(),
      type: "GET",
      dataType: "HTML",
    }).done(function(response) {
      if (!response) {
        $('[name="district"]').html('<option></option>');
      } else {
        $('[name="district"]').html('<option></option>'+response);

        if (district) {
          $('[name="district"]').val(district).trigger('change');
        }
      }
    });
  });

  $('[key="del-image"]').on('click', function () {
    deleteMethod(true, $(this).data("scope"), $(this).data("val"));
  });

  $('#form-asset [type="reset"]').on("click", function () {
    resetForm('asset'),
    initializeData();
  });
}

function initModal() {
  $("#modal-image").on("hidden.bs.modal", function () {
    resetForm('image'),
    $('.dropify-clear').click(),
    initializeData();
  });
}

function resetForm(form) {
  $('#form-'+form)[0].reset(),
  $('#form-'+form).removeClass('was-validated'),
  $('.invalid-tooltip').remove(),
  scope  = '';
  value   = '';
}

function fetchAsset() {
  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'youth_assets/get',
    data: "scope="+$('meta[name=scope]').attr("content")+"&format=JSON&id="+$('meta[name=params]').attr("content"),
    type: "GET",
    success: function(response) {
      $('[name="ownership"]').val(response.data.asset_ownership).trigger('change'),
      $('[name="type"]').val(response.data.asset_type).trigger('change'),
      $('[name="category"]').val(response.data.asset_category_id).trigger('change'),
      $('[name="category_type"]').val(response.data.asset_category_type).trigger('change'),

      $('[name="name"]').val(response.data.asset_name),
      $('[name="description"]').val(response.data.asset_description),

      $('[name="year"]').val(response.data.asset_production_year).datepicker('update'),
      $('[name="condition"]').val(response.data.asset_condition).trigger('change'),
      $('[name="management"]').val(response.data.asset_management).trigger('change'),
      $('[name="managedby"]').val(response.data.asset_managed_by),
      $('[name="map"]').val(response.data.asset_map),

      $('[name="province"]').val(response.data.province_id).trigger('change');

      if (response.data.asset_content) {
        $('[name="content"]').html(response.data.asset_content);
      }

      district    = response.data.district_id;
    },
  });
}

$("#form-asset").validate({
  submitHandler: function(form) {
    var formData = new FormData($('#form-asset')[0]);

    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'youth_assets/store?scope='+$('meta[name=scope]').attr("content")+'&id='+$('meta[name=params]').attr("content"),
      data: formData,
      type: "POST",
      contentType: false,
      beforeSend: function() {
        requestBefore('submit');
      },
      success: function(response) {
        setTimeout(function() {
          location.reload();
        }, 2e3)
      },
      error: function() {
        setTimeout(function() {
          requestSuccess('submit');
        }, 2e3)
      },
    });
  }
});

$("#form-image").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'youth_assets/store?scope='+scope+'&params='+$('meta[name=params]').attr("content")+'&id='+value,
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
          url: $('meta[name=site-url]').attr("content")+'youth_assets/delete?scope='+scope+'&id='+id,
          type: "DELETE",
          success: function(response){
            if (redirect == true) {
              location.reload();
            } else {
              pushSwal(response.type, response.header, response.message.success), initializeData();
            }
          }
      });
    } else {
      pushSwalCancel()
    }
  });
}
