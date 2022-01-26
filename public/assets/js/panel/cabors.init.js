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
  fetchCabors();
}

function initComponents() {

}

function initButton() {
  $('[key="rfs-cabor"]').on("click", function () {
    resetForm('cabor'),
    initializeData();
  });

  $('[key="add-cabor"]').on("click", function () {
    value   = 'add';

    $('#modal-cabor .modal-title').text('Tambah Cabang Olahraga Baru'),
    $('#modal-cabor [type="submit"]').text('Tambah');
  });
}

function initModal() {
  $("#modal-cabor").on("hidden.bs.modal", function () {
    resetForm('cabor'),
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

function fetchCabors() {
  var dataTable = $('#dt_cabors').DataTable({
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
      url: $('meta[name=site-url]').attr("content")+"cabors/list?scope="+$('meta[name=scope]').attr("content")+"&format=DT",
      type: "POST",
      processData: true,
    },
    "columnDefs": [{
      targets: [0, 3, 4],
      orderable: false,
    }],
    lengthMenu: [
      [10, 25, 50, 100, -1],
      ['10', '25', '50', '100', 'All']
    ],
  });

  $(".dataTables_length select").addClass('form-select form-select-sm'),
  $("#dt_cabors_paginate").addClass("pagination-rounded");
}

$("#form-cabor").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'cabors/store?scope='+$('meta[name=scope]').attr("content")+'&id='+value,
      data: $('#form-cabor').serialize(),
      type: "POST",
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          pushToastr(response.type, response.header, response.message.success), requestSuccess('modal'), $('#modal-cabor').modal('hide');
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

function putCabor(id) {
  value  = id;

  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'cabors/get',
    data: "scope="+$('meta[name=scope]').attr("content")+"&format=JSON&id="+id,
    type: "GET",
    success: function(response) {
      $('[name="code"]').val(response.data.cabor_code),
      $('[name="name"]').val(response.data.cabor_name),
      $('[name="description"]').val(response.data.cabor_description),

      $('#modal-cabor .modal-title').text('Ubah Informasi Cabang Olahraga'),
      $('#modal-cabor [type="submit"]').text('Simpan');
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
      pushSwalConfirmBeforeDelete(redirect, 'cabors/delete?scope='+scope+'&id='+id);
    } else {
      pushSwalCancel()
    }
  });
}
