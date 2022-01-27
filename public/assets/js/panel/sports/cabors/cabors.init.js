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

}

function submitExport(param){
  $('[key="export-'+param+'"]').on("click", function () {
    $("#form-export").attr("action", $("meta[name=site-url]").attr("content")+"sport_cabors/export/"+param);
    $("#form-export").submit();
  });
}

function initButton() {
  submitExport('print');
  submitExport('pdf');
  submitExport('excel');

  $('[key="add-cabor"]').on("click", function () {
    value   = 'add';

    $('#modal-cabor .modal-title').text('Tambah Cabang Olahraga Baru'),
    $('#modal-cabor [type="submit"]').text('Tambah');
  });

  $('[key="upd-avatar"]').on("click", function () {
    $('#image').click();
  });

  $('#image').on("change", function () {
    previewImage(this);
  });

  $('[key="upd-cabor"]').on("click", function () {
    value   = $(this).data("val");

    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'sport_cabors/get',
      data: "scope="+$('meta[name=scope]').attr("content")+"&format=JSON&id="+value,
      type: "GET",
      success: function(response) {
        if (response.data.sport_cabor_avatar) {
          $('[key="avatar"]').attr('src', $('[key="avatar"]').data('url')+response.data.sport_cabor_avatar);
        } else {
          $('[key="avatar"]').attr('src', $('[key="avatar"]').data('src'));
        }

        $('[name="name"]').val(response.data.sport_cabor_name),
        $('[name="code"]').val(response.data.sport_cabor_code);

        $('#modal-cabor .modal-title').text('Ubah Informasi Cabang Olahraga'),
        $('#modal-cabor [type="submit"]').text('Simpan');
      },
    });
  });

  $('[key="del-cabor"]').on('click', function () {
    deleteMethod(true, $(this).data("scope"), $(this).data("val"));
  });
}

function initModal() {
  $("#modal-cabor").on("hidden.bs.modal", function () {
    resetForm('cabor'),
    $('[key="avatar"]').attr('src', $('[key="avatar"]').data('src')),
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

function previewImage(input, target) {
  var file = event.target.files[0];
  var img = new Image();

  if (file && file.size >= 2*1024*1024) {
    document.getElementById('image').value = "",
    pushToastr('warning', '406 Not Acceptable', 'Ukuran maksimal gambar yang diizinkan hanya <strong>2MB</strong>!');
  } else if (file && !file.type.match('image/png')) {
    document.getElementById('image').value = "",
    pushToastr('warning', '405 Method Not Allowed', 'Format gambar yang diizinkan hanya <strong>PNG</strong>');
  } else if (file && input.files && input.files[0]) {
    var fileReader = new FileReader();
    fileReader.onload = function (e) {
      $('[key="avatar"]').attr('src', e.target.result);
    };
    fileReader.readAsDataURL(input.files[0]);
  }
}

$("#form-cabor").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'sport_cabors/store?scope='+$('meta[name=scope]').attr("content")+'&id='+value,
      data: new FormData($('#form-cabor')[0]),
      type: "POST",
      contentType: false,
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
            $('#modal-cabor').modal('hide'), location.reload();
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
      pushSwalConfirmBeforeDelete(redirect, 'sport_cabors/delete?scope='+scope+'&id='+id);
    } else {
      pushSwalCancel()
    }
  });
}
