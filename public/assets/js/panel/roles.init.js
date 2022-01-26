/*
Author: Ionix Eternal Studio
Website: https://ionixeternal.co.id/
Contact: support@ionixeternal.co.id
File: Datatables Js File
*/

'use strict'

$(document).ready(function() {

});

var scope, value;

initialize();

function initialize() {
  initComponents(),
  initButton(),
  initModal();
}

function initComponents() {
  $(".colorpicker").spectrum({
    showInitial: true
  });
}

function initButton() {
  $('[key="add-role"]').on("click", function () {
    value   = 'add';

    $('#modal-role .modal-title').text('Tambah Hak Akses Baru'),
    $('#modal-role [type="submit"]').text('Tambah');
  });

  $('[key="upd-role"]').on("click", function () {
    value   = $(this).data("val");

    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'roles/get',
      data: "scope="+$('meta[name=scope]').attr("content")+"&format=JSON&id="+value,
      type: "GET",
      success: function(response) {
        $('[name="code"]').val(response.data.role_code),
        $('[name="name"]').val(response.data.role_name),
        $('[name="description"]').val(response.data.role_description),
        $('[name="access"]').val(response.data.role_access),
        $('[name="color"]').val('#'+response.data.role_color).spectrum('set', '#'+response.data.role_color);

        $('#modal-role .modal-title').text('Ubah Informasi Hak Akses'),
        $('#modal-role [type="submit"]').text('Simpan');
      },
    });
  });

  $('[key="del-role"]').on('click', function () {
    deleteMethod(true, $(this).data("scope"), $(this).data("val"));
  });
}

function initModal() {
  $("#modal-role").on("hidden.bs.modal", function () {
    resetForm('role'),
    $('.colorpicker').spectrum('set', ''),
    $('.colorpicker').prop('readonly', true);
  });
}

function resetForm(form) {
  $('#form-'+form)[0].reset(),
  $('#form-'+form).removeClass('was-validated'),
  $('.invalid-tooltip').remove(),
  scope  = '';
  value   = '';
}

$("#form-role").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'roles/store?scope='+$('meta[name=scope]').attr("content")+"&id="+value,
      data: $('#form-role').serialize(),
      type: "POST",
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          $('#modal-role').modal('hide'), location.reload();
        }, 2e3)
      },
      error: function() {
        setTimeout(function() {
          requestSuccess('modal'),
          $('.colorpicker').spectrum('set', ''),
          $('.colorpicker').prop('readonly', true);
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
      pushSwalConfirmBeforeDelete(redirect, 'roles/delete?scope='+scope+'&id='+id);
    } else {
      pushSwalCancel()
    }
  });
}
