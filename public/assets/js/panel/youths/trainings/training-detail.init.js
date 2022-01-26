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
  // fetchParticipant();
}

function initComponents() {
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
  $('[key="add-import"]').on("click", function () {
    scope  = $(this).data("scope");
    value  = 'add';

    $('#modal-import .modal-title').text('Impor Data Peserta pada Pelatihan ini'),
    $('#modal-import [type="submit"]').text('Impor');
  });

  $('[key="del-allparticipant"]').on('click', function () {
    deleteMethod(true, $(this).data("scope"), $(this).data("val"));
  });

  $('[key="del-participant"]').on('click', function () {
    deleteMethod(true, $(this).data("scope"), $(this).data("val"));
  });
}

function initModal() {
  $("#modal-import").on("hidden.bs.modal", function () {
    resetForm('import'),
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

$("#form-import").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'youth_trainings/import?scope='+scope+'&params='+$('meta[name=params]').attr("content")+'&id='+value,
      data: new FormData($('#form-import')[0]),
      type: "POST",
      contentType: false,
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          $('#modal-import').modal('hide'), location.reload();
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
          url: $('meta[name=site-url]').attr("content")+'youth_trainings/delete?scope='+scope+'&id='+id,
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
