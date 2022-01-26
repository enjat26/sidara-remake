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
  fetchGroup();
}

function initComponents() {

}

function initButton() {
  $('[key="rfs-navigation-group"]').on("click", function () {
    resetForm('navigation-groups'),
    initializeData();
  });

  $('[key="add-navigation-group"]').on("click", function () {
    value   = 'add';

    $('#modal-navigation-groups .modal-title').text('Tambah Grup Navigasi Baru'),
    $('#modal-navigation-groups [type="submit"]').text('Tambah');
  });
}

function initModal() {
  $("#modal-navigation-groups").on("hidden.bs.modal", function () {
    resetForm('navigation-groups'),
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

function fetchGroup() {
  var dataTable = $('#dt_navigation_groups').DataTable({
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
      url: $('meta[name=site-url]').attr("content")+"navigation_groups/list?scope="+$('meta[name=scope]').attr("content")+"&format=DT",
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
  $("#dt_navigation_groups_paginate").addClass("pagination-rounded");
}

$("#form-navigation-groups").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'navigation_groups/store?scope='+$('meta[name=scope]').attr("content")+'&id='+value,
      data: $('#form-navigation-groups').serialize(),
      type: "POST",
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          pushToastr(response.type, response.header, response.message.success), requestSuccess('modal'), $('#modal-navigation-groups').modal('hide');
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

function putNavigationGroup(id) {
  value  = id;

  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'navigation_groups/get',
    data: "scope="+$('meta[name=scope]').attr("content")+"&format=JSON&id="+id,
    type: "GET",
    success: function(response) {
      $('[name="code"]').val(response.data.group_code),
      $('[name="title"]').val(response.data.group_title),
      $('[name="description"]').val(response.data.group_description),

      $('#modal-navigation-groups .modal-title').text('Ubah Informasi Grup Navigasi'),
      $('#modal-navigation-groups [type="submit"]').text('Simpan');
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
      pushSwalConfirmBeforeDelete(redirect, 'navigation_groups/delete?scope='+scope+'&id='+id);
    } else {
      pushSwalCancel()
    }
  });
}
