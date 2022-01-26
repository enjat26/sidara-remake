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
  $('.select2').select2({
    placeholder: function() {
      $(this).data('placeholder');
    },
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
  $('[key="add-file"]').on("click", function () {
    scope  = $(this).data("scope");
    value   = 'add';

    $('.upload').show();

    $('#modal-file .modal-title').text('Unggah Berkas Baru'),
    $('#modal-file [type="submit"]').text('Unggah');
  });

  $('[key="upd-file"]').on("click", function () {
    scope  = $(this).data("scope");
    value  = $(this).data("val");

    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'files/get',
      data: "scope="+scope+"&format=JSON&id="+value,
      type: "GET",
      success: function(response) {
        $('[name="name"]').val(response.data.file_name),
        $('[name="description"]').val(response.data.file_description);

        $('#modal-file .modal-title').text('Ubah Informasi Berkas'),
        $('#modal-file [type="submit"]').text('Simpan');
      },
    });
  });

  $('[key="dwd-file"]').on('click', function () {
    updateMethod(false, $(this).data("scope"), $(this).data("val"));
  });

  $('[key="del-file"]').on('click', function () {
    deleteMethod(true, $(this).data("scope"), $(this).data("val"));
  });
}

function initModal() {
  $("#modal-file").on("hidden.bs.modal", function () {
    resetForm('file'),
    $('.select2').val('').trigger('change'),
    $('.dropify-clear').click(),
    $('.upload').hide(),
    initializeData();
  });
}

function resetForm(form) {
  $('#form-'+form)[0].reset(),
  $('#form-'+form).removeClass('was-validated'),
  $('.invalid-tooltip').remove(),
  scope   = '';
  value   = '';
}

$("#form-file").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'files/store?scope='+scope+'&id='+value,
      data: new FormData($('#form-file')[0]),
      type: "POST",
      contentType: false,
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          $('#modal-file').modal('hide'), location.reload();
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
    url:  $('meta[name=site-url]').attr("content")+'files/update?scope='+scope+'&id='+id,
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
          url: $('meta[name=site-url]').attr("content")+'files/delete?scope='+scope+'&id='+id,
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
