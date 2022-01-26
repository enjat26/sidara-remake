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
  fetchUsers();
}

function initComponents() {
  $('.select2').select2({
    placeholder: function() {
      $(this).data('placeholder');
    },
  });
}

function initButton() {
  $('[key="rfs-user"]').on("click", function () {
    resetForm('user'),
    $('.select2').val('').trigger('change'),
    initializeData();
  });

  $('[key="add-user"]').on("click", function () {
    value   = 'add';

    $('#modal-user .modal-title').text('Tambah Pengguna Baru'),
    $('#modal-user [type="submit"]').text('Tambah');
  });
}

function initModal() {
  $("#modal-user").on("hidden.bs.modal", function () {
    resetForm('user'),
    $('.select2').val('').trigger('change'),
    $('[name="password"]').attr('type', 'password'),
    $('#password-show').html('<i class="mdi mdi-eye-outline"></i>'),
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

function fetchUsers() {
  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'users/count',
    data: "scope="+$('meta[name=scope]').attr("content")+"&format=JSON&id="+$('[key="total-stakeholder"]').data("val"),
    type: "GET",
    success: function(response) {
      $('[key="total-stakeholder"]').text(response.data);
    },
  });

  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'users/count',
    data: "scope="+$('meta[name=scope]').attr("content")+"&format=JSON&id="+$('[key="total-user"]').data("val"),
    type: "GET",
    success: function(response) {
      $('[key="total-user"]').text(response.data);
    },
  });

  var dataTable = $('#dt_users').DataTable({
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
      url: $('meta[name=site-url]').attr("content")+"users/list?scope="+$('meta[name=scope]').attr("content")+"&format=DT",
      type: "POST",
      processData: true,
    },
    "columnDefs": [{
      targets: [0, 5, 6],
      orderable: false,
    }],
    lengthMenu: [
      [10, 25, 50, 100, -1],
      ['10', '25', '50', '100', 'All']
    ],
  });

  $('[name="filter-role"]').on('change', function () {
    dataTable.column(4).search(this.value).draw();
  });

  $(".dataTables_length select").addClass('form-select form-select-sm'),
  $("#dt_users_paginate").addClass("pagination-rounded");
}

$("#form-user").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'users/store?scope='+$('meta[name=scope]').attr("content")+'&id='+value,
      data: $('#form-user').serialize(),
      type: "POST",
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          pushToastr(response.type, response.header, response.message.success), requestSuccess('modal'), $('#modal-user').modal('hide');
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
    url:  $('meta[name=site-url]').attr("content")+'users/update?scope='+scope+"&id="+id,
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
      pushSwalConfirmBeforeDelete(redirect, 'users/delete?scope='+scope+'&id='+id);
    } else {
      pushSwalCancel()
    }
  });
}
