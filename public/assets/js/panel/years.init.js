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
  fetchYears();
}

function initComponents() {
  $('[data-provide="yearpicker"]').datepicker({
    format: "yyyy",
    viewMode: "years",
    minViewMode: "years",
  });
}

function initButton() {
  $('[key="rfs-year"]').on("click", function () {
    resetForm('year'),
    initializeData();
  });

  $('[key="add-year"]').on("click", function () {
    value   = 'add';

    $('#modal-year .modal-title').text('Tambah Tahun Baru'),
    $('#modal-year [type="submit"]').text('Tambah');
  });
}

function initModal() {
  $("#modal-year").on("hidden.bs.modal", function () {
    resetForm('year'),
    $('[data-provide="yearpicker"]').val('').datepicker('update'),
    $('[data-provide="yearpicker"]').prop('readonly', true),
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

function fetchYears() {
  var dataTable = $('#dt_years').DataTable({
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
      url: $('meta[name=site-url]').attr("content")+"years/list?scope="+$('meta[name=scope]').attr("content")+"&format=DT",
      type: "POST",
      processData: true,
    },
    "columnDefs": [{
      targets: [0, 3],
      orderable: false,
    }],
    lengthMenu: [
      [10, 25, 50, 100, -1],
      ['10', '25', '50', '100', 'All']
    ],
  });

  $(".dataTables_length select").addClass('form-select form-select-sm'),
  $("#dt_years_paginate").addClass("pagination-rounded");
}

$("#form-year").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'years/store?scope='+$('meta[name=scope]').attr("content")+'&id='+value,
      data: $('#form-year').serialize(),
      type: "POST",
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          pushToastr(response.type, response.header, response.message.success), requestSuccess('modal'), $('#modal-year').modal('hide');
        }, 2e3)
      },
      error: function() {
        setTimeout(function() {
          requestSuccess('modal'),
          $('[data-provide="yearpicker"]').prop('readonly', true);
        }, 2e3)
      },
    });
  }
});

function putYear(id) {
  value  = id;

  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'years/get',
    data: "scope="+$('meta[name=scope]').attr("content")+"&format=JSON&id="+id,
    type: "GET",
    success: function(response) {
      $('[name="year"]').val(response.data.year),
      $('[name="description"]').val(response.data.year_description),

      $('#modal-year .modal-title').text('Ubah Informasi Tahun'),
      $('#modal-year [type="submit"]').text('Simpan');
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
      pushSwalConfirmBeforeDelete(redirect, 'years/delete?scope='+scope+'&id='+id);
    } else {
      pushSwalCancel()
    }
  });
}
