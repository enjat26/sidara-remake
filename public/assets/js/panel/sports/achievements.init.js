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
var atlet;

initialize();

function initialize() {
  initComponents(),
  initButton(),
  initModal();
}

function initializeData() {
  fetchAchievement();
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
    minViewMode: "years",
  });
}

function submitExport(param){
  $('[key="export-'+param+'"]').on("click", function () {
    $("#form-export").attr("action", $("meta[name=site-url]").attr("content")+"sport_achievements/export/"+param);
    $("#form-export").submit();
  });
}

function initButton() {
  submitExport('print');
  submitExport('pdf');
  submitExport('excel');

  $('[key="rfs-achievement"]').on("click", function () {
    $('.select2').val('').trigger('change'),
    initializeData();
  });

  $('[key="add-achievement"]').on("click", function () {
    scope  = $(this).data("scope");
    value   = 'add';

    $('#modal-achievement .modal-title').text('Tambah Data Prestasi Olahraga'),
    $('#modal-achievement [type="submit"]').text('Tambah');
  });

  $('[name="championship"]').on("change", function () {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'sport_achievements/list',
      data: "format=Dropdown&scope="+$(this).data("scope")+"&id="+$(this).val(),
      type: "GET",
      dataType: "HTML",
    }).done(function(response) {
      if (!response) {
        $('[name="atlet"]').html('<option></option>');
      } else {
        $('[name="atlet"]').html('<option></option>'+response);
      }

      if (atlet) {
        $('[name="atlet"]').val(atlet).trigger('change');
      }
    });
  });
}

function initModal() {
  $("#modal-achievement").on("hidden.bs.modal", function () {
    resetForm('achievement'),
    $('.select2').val('').trigger('change'),
    initializeData();
  });
}

function resetForm(form) {
  $('#form-'+form)[0].reset(),
  $('#form-'+form).removeClass('was-validated'),
  $('.invalid-tooltip').remove(),
  scope     = '';
  value     = '';
  atlet     = '';
}

function fetchAchievement() {
  var dataTable = $('#dt_achievements').DataTable({
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
      url: $('meta[name=site-url]').attr("content")+"sport_achievements/list?scope="+$('meta[name=scope]').attr("content")+"&format=DT",
      type: "POST",
      processData: true,
    },
    "columnDefs": [{
      targets: [0, 2, 3, 4, 5, 6, 7],
      orderable: false,
    }],
    lengthMenu: [
      [10, 25, 50, 100, -1],
      ['10', '25', '50', '100', 'All']
    ],
  });

  $('[name="filter-year"]').on('change', function () {
    dataTable.column(3).search(this.value).draw();
  });

  $(".dataTables_length select").addClass('form-select form-select-sm'),
  $("#dt_achievements_paginate").addClass("pagination-rounded");
}

$("#form-achievement").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'sport_achievements/store?scope='+scope+'&id='+value,
      data: $('#form-achievement').serialize(),
      type: "POST",
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          pushToastr(response.type, response.header, response.message.success), requestSuccess('modal'), $('#modal-achievement').modal('hide');
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

function putAchievement(method, id) {
  scope = method;
  value  = id;

  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'sport_achievements/get',
    data: "scope="+scope+"&format=JSON&id="+id,
    type: "GET",
    success: function(response) {
      $('[name="championship"]').val(response.data.sport_championship_id).trigger('change'),
      $('#form-achievement [name="name"]').val(response.data.sport_achievement_name),
      $('[name="number"]').val(response.data.sport_achievement_number),
      $('[name="result"]').val(response.data.sport_achievement_result)
      $('[name="medal"]').val(response.data.sport_medal_id).trigger('change');

      atlet = response.data.sport_atlet_id;

      $('#modal-achievement .modal-title').text('Ubah Informasi Prestasi Olahraga'),
      $('#modal-achievement [type="submit"]').text('Simpan');
    },
  });
}

function updateResub(redirect, scope, value) {
  Swal.fire({
    title: "Daftar Ulang Data",
    text: "Anda akan mendaftarkan ulang Data ini untuk kembali diajukan?",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Ya",
    cancelButtonText: "Batal",
    customClass: {
      confirmButton: 'btn btn-primary me-2',
      cancelButton: 'btn btn-danger',
    },
    buttonsStyling: false
  }).then((result) => {
    if(result.value) {
      $.ajax({
        url:  $('meta[name=site-url]').attr("content")+'sport_achievements/store?scope='+scope+'&id='+value,
        data: "action="+result.value,
        type: "POST",
        success: function(response) {
          setTimeout(function() {
            if (redirect == true) {
              location.reload();
            } else {
              pushToastr(response.type, response.header, response.message.success), initializeData();
            }
          }, 2e3)
        },
      });
    } else {
      pushSwalCancel()
    }
  });
}

function updateVerify(redirect, scope, value) {
  Swal.fire({
    title: "Verifikasi Data",
    text: "Anda akan memverifikasi Data ini?",
    icon: "question",
    showCancelButton: true,
    showDenyButton: true,
    confirmButtonText: "Terima",
    denyButtonText: "Tolak",
    cancelButtonText: "Batal",
    customClass: {
      confirmButton: 'btn btn-success me-2',
      denyButton: 'btn btn-warning me-2',
      cancelButton: 'btn btn-danger',
    },
    buttonsStyling: false
  }).then((result) => {
    if(result.value == true || result.value == false) {
      $.ajax({
        url:  $('meta[name=site-url]').attr("content")+'sport_achievements/store?scope='+scope+'&id='+value,
        data: "action="+result.value,
        type: "POST",
        success: function(response) {
          setTimeout(function() {
            if (redirect == true) {
              location.reload();
            } else {
              pushToastr(response.type, response.header, response.message.success), initializeData();
            }
          }, 2e3)
        },
      });
    } else {
      pushSwalCancel()
    }
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
          url: $('meta[name=site-url]').attr("content")+'sport_achievements/delete?scope='+scope+'&id='+id,
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
