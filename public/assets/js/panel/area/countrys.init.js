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
  fetchCountrys();
}

function initComponents() {

}

function initButton() {
  $('[key="rfs-country"]').on("click", function () {
    resetForm('country'),
    $('[key="avatar"]').attr('src', $('[key="avatar"]').data('src')),
    initializeData();
  });

  $('[key="upd-avatar"]').on("click", function () {
    $('#image').click();
  });

  $('#image').on("change", function () {
    previewImage(this);
  });

  $('[key="add-country"]').on("click", function () {
    value   = 'add';

    $('#modal-country .modal-title').text('Tambah Negara Baru'),
    $('#modal-country [type="submit"]').text('Tambah');
  });
}

function initModal() {
  $("#modal-country").on("hidden.bs.modal", function () {
    resetForm('country'),
    $('[key="avatar"]').attr('src', $('[key="avatar"]').data('src')),
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

function fetchCountrys() {
  var dataTable = $('#dt_countrys').DataTable({
    oLanguage: {
      sProcessing: "Sedang memuat...",
      sSearch: "Cari:",
      oPaginate: {
        sLast: ">>",
        sNext: ">",
        sPrevious: "<",
        sFirst: "<<",
      },
      sZeroRecords: "Tidak dapat menemukan data yang cocok",
    },
    "destroy": true,
    "processing": true,
    "serverSide": true,
    "responsive": true,
    "order": [],
    "ajax": {
      url: $('meta[name=site-url]').attr("content")+"countrys/list?scope="+$('meta[name=scope]').attr("content")+"&format=DT",
      type: "POST",
      processData: true,
    },
    "columnDefs": [{
      targets: [0, 4, 5, 6],
      orderable: false,
    }],
    lengthMenu: [
      [10, 25, 50, 100, -1],
      ['10', '25', '50', '100', 'All']
    ],
  });

  $(".dataTables_length select").addClass('form-select form-select-sm'),
  $("#dt_countrys_paginate").addClass("pagination-rounded");
}

function previewImage(input, target) {
  var file = event.target.files[0];
  var img = new Image();

  if (file && file.size >= 2*1024*1024) {
    document.getElementById('image').value = "",
    pushToastr('warning', '406 Not Acceptable', 'Ukuran maksimal gambar yang diizinkan hanya <strong>2MB</strong>!');
  } else if (file && !file.type.match('image/jp.*')) {
    document.getElementById('image').value = "",
    pushToastr('warning', '405 Method Not Allowed', 'Format gambar yang diizinkan hanya <strong>JPG dan JPEG</strong>');
  } else if (file && input.files && input.files[0]) {
    var fileReader = new FileReader();
    fileReader.onload = function (e) {
      $('[key="avatar"]').attr('src', e.target.result);
    };
    fileReader.readAsDataURL(input.files[0]);
  }
}

$("#form-country").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'countrys/store?scope='+$('meta[name=scope]').attr("content")+'&id='+value,
      data: new FormData($('#form-country')[0]),
      type: "POST",
      contentType: false,
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          pushToastr(response.type, response.header, response.message.success), requestSuccess('modal'), $('#modal-country').modal('hide');
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

function putCountry(id) {
  value  = id;

  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'countrys/get',
    data: "scope="+$('meta[name=scope]').attr("content")+"&format=JSON&id="+id,
    type: "GET",
    success: function(response) {
      $('[key="avatar"]').attr('src', $('[key="avatar-'+response.data.country_iso3+'"]').attr('src')),
      $('[name="iso2"]').val(response.data.country_iso2),
      $('[name="iso3"]').val(response.data.country_iso3),
      $('[name="name"]').val(response.data.country_name),
      $('[name="capital"]').val(response.data.country_capital),
      $('[name="region"]').val(response.data.country_region),
      $('[name="subregion"]').val(response.data.country_sub_region),
      $('[name="latitude"]').val(response.data.country_latitude),
      $('[name="longitude"]').val(response.data.country_longitude),
      $('[name="currency"]').val(response.data.country_currency),
      $('[name="symbol"]').val(response.data.country_currency_symbol),
      $('[name="phone"]').val(response.data.country_phone_code),

      $('#modal-country .modal-title').text('Ubah Informasi Negara'),
      $('#modal-country [type="submit"]').text('Simpan');
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
      pushSwalConfirmBeforeDelete(redirect, 'countrys/delete?scope='+scope+'&id='+id);
    } else {
      pushSwalCancel()
    }
  });
}
