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
  fetchCertification();
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
    minViewMode: "years"
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
  $('[key="rfs-certification"]').on("click", function () {
    $('.select2').val('').trigger('change'),
    initializeData();
  });

  $('[key="add-certification"]').on("click", function () {
    scope  = $(this).data("scope");
    value   = 'add';

    $('#modal-certification .modal-title').text('Tambah Peserta Sertifikasi'),
    $('#modal-certification [type="submit"]').text('Tambah');
  });

  $('[key="add-import"]').on("click", function () {
    scope  = $(this).data("scope");
    value   = 'add';

    $('#modal-import .modal-title').text('Impor Data Peserta Sertifikasi'),
    $('#modal-import [type="submit"]').text('Impor');
  });
}

function initModal() {
  $("#modal-certification").on("hidden.bs.modal", function () {
    resetForm('certification'),
    $('.select2').val('').trigger('change'),
    $('[data-provide="yearpicker"]').val('').datepicker('update'),
    $('[data-provide="yearpicker"]').prop('readonly', true),
    initializeData();
  });

  $("#modal-import").on("hidden.bs.modal", function () {
    resetForm('import'),
    $('.select2').val('').trigger('change'),
    $('.dropify-clear').click(),
    initializeData();
  });
}

function resetForm(form) {
  $('#form-'+form)[0].reset(),
  $('#form-'+form).removeClass('was-validated'),
  $('.invalid-tooltip').remove(),
  scope     = '';
  value     = '';
}

function fetchCertification() {
  var dataTable = $('#dt_certifications').DataTable({
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
      url: $('meta[name=site-url]').attr("content")+"sport_certifications/list?scope="+$('meta[name=scope]').attr("content")+"&format=DT",
      type: "POST",
      processData: true,
    },
    "columnDefs": [{
      targets: [0, 4, 5, 6, 8, 9],
      orderable: false,
    }],
    lengthMenu: [
      [10, 25, 50, 100, -1],
      ['10', '25', '50', '100', 'All']
    ],
  });

  $('[name="filter-cabor"]').on('change', function () {
    dataTable.column(3).search(this.value).draw();
  });

  $('[name="filter-category"]').on('change', function () {
    dataTable.column(4).search(this.value).draw();
  });

  $('[name="filter-year"]').on('change', function () {
    dataTable.column(6).search(this.value).draw();
  });

  $(".dataTables_length select").addClass('form-select form-select-sm'),
  $("#dt_certifications_paginate").addClass("pagination-rounded");
}

$("#form-certification").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'sport_certifications/store?scope='+scope+'&id='+value,
      data: $('#form-certification').serialize(),
      type: "POST",
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          pushToastr(response.type, response.header, response.message.success), requestSuccess('modal'), $('#modal-certification').modal('hide');
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

function putCertification(method, id) {
  scope  = method;
  value  = id;

  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'sport_certifications/get',
    data: "scope="+scope+"&format=JSON&id="+id,
    type: "GET",
    success: function(response) {
      $('[name="category"]').val(response.data.sport_certification_category).trigger('change'),
      $('[name="cabor"]').val(response.data.sport_cabor_code).trigger('change'),
      $('[name="level"]').val(response.data.sport_certification_level).trigger('change'),
      $('[name="year"]').val(response.data.sport_certification_year).datepicker('update'),

      $('#form-certification [name="name"]').val(response.data.sport_certification_name),
      $('[name="gender"]').val(response.data.sport_certification_gender).trigger('change'),
      $('[name="explanation"]').val(response.data.sport_certification_explanation),

      $('#modal-certification .modal-title').text('Ubah Informasi Peserta Sertifikasi'),
      $('#modal-certification [type="submit"]').text('Simpan');
    },
  });
}

$("#form-import").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'sport_certifications/import',
      data: new FormData($('#form-import')[0]),
      type: "POST",
      contentType: false,
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          pushToastr(response.type, response.header, response.message.success), requestSuccess('modal'), $('#modal-import').modal('hide');
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
          url: $('meta[name=site-url]').attr("content")+'sport_certifications/delete?scope='+scope+'&id='+id,
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
