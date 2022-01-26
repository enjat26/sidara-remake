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
  fetchChampionship(),
  fetchPartcipant();
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

function initButton()
{
  $('[name="level"]').on('change', function() {
    let val  = $(this).val();
    var html ='';

    if (val == 'Internasional') {
      html = `<option value="Dunia">Dunia</option>
              <option value="Asia">Asia</option>`;
    } else if (val == 'Nasional') {
      html = `<option value="Nasional">Nasional</option>
              <option value="Daerah">Daerah</option>`;
    } else {
      html = '';
    }

    $('[name="category"]').html('');
    $('[name="category"]').append(html);
  });

  $('[key="add-atlet"]').on("click", function () {
    fetchAtlet();

    scope  = $(this).data("scope");
    value   = 'add';

    $('#modal-atlet .modal-title').text('Tambah Peserta Kejuaraan Olahraga'),
    $('#modal-atlet [type="submit"]').text('Tambah');
  });

  $(document).on("change", ".chooseAtlet", function () {
    // if ($(this).is(":checked")) {
    //   var method = "add";
    // } else {
    //   var method = "delete";
    // }

    // alert($(this).data("val"));
    //
    // const formData = new FormData();
    // formData.append("value", $(this).data("val"));
    // formData.append("method", method);

    $.ajax({
      url: $('meta[name=site-url]').attr("content")+'sport_championships/store?scope='+$(this).data("scope")+'&params='+$('meta[name=params]').attr("content"),
      data: "value="+$(this).data("val"),
      type: "POST",
    });
  });

  $('[key="upd-resub"]').on("click", function () {
    scope  = $(this).data("scope");
    value   = 'add';

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
          url:  $('meta[name=site-url]').attr("content")+'sport_championships/store?scope='+scope+'&params='+$('meta[name=params]').attr("content")+'&id='+value,
          data: "action="+result.value,
          type: "POST",
          success: function(response) {
            setTimeout(function() {
              location.reload();
            }, 2e3)
          },
        });
      } else {
        pushSwalCancel()
      }
    });
  });

  $('[key="upd-verify"]').on("click", function () {
    scope  = $(this).data("scope");
    value   = 'add';

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
          url:  $('meta[name=site-url]').attr("content")+'sport_championships/store?scope='+scope+'&params='+$('meta[name=params]').attr("content")+'&id='+value,
          data: "action="+result.value,
          type: "POST",
          success: function(response) {
            setTimeout(function() {
              location.reload();
            }, 2e3)
          },
        });
      } else {
        pushSwalCancel()
      }
    });
  });

  $('[key="del-championship"]').on("click", function () {
    scope   = $('meta[name=scope]').attr("content");
    value   = $('meta[name=params]').attr("content");

    Swal.fire({
      title: "Hapus Data",
      text: "Anda akan menghapus Data ini dari sistem?",
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
          url:  $('meta[name=site-url]').attr("content")+'sport_championships/delete?scope='+scope+'&params=purge'+'&id='+value,
          type: "DELETE",
          success: function(response) {
            setTimeout(function() {
              window.location.href = response.data.url;
            }, 2e3)
          },
        });
      } else {
        pushSwalCancel()
      }
    });
  });

  $('#form-championship [type="reset"]').on("click", function () {
    resetForm('championship'),
    initializeData();
  });

  $('[key="del-all-participant"]').on("click", function () {
    deleteMethod(true, $(this).data("scope"), $(this).data("val"));
  });
}

function initModal() {
  $("#modal-atlet").on("hidden.bs.modal", function () {
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

function fetchChampionship() {
  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'sport_championships/get',
    data: "scope="+$('meta[name=scope]').attr("content")+"&format=JSON&id="+$('meta[name=params]').attr("content"),
    type: "GET",
    success: function(response) {
      $('[name="code"]').val(response.data.sport_championship_code),
      $('[name="year"]').val(response.data.sport_championship_year).datepicker('update'),

      $('[name="name"]').val(response.data.sport_championship_name),
      $('[name="description"]').val(response.data.sport_championship_description),

      $('[name="level"]').val(response.data.sport_championship_level).trigger('change'),
      $('[name="category"]').val(response.data.sport_championship_category).trigger('change'),

      $('[name="location"]').val(response.data.sport_championship_location),
      $('[name="explanation"]').val(response.data.sport_championship_explanation);
    },
  });
}

function fetchAtlet() {
  var dataTablePartcipant = $("#dt_atlets").DataTable({
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
    destroy: true,
    processing: true,
    serverSide: true,
    responsive: true,
    order: [],
    ajax: {
      url: $("meta[name=site-url]").attr("content")+"sport_championships/list?scope="+$("#dt_atlets").data("scope")+"&format=DT"+"&params="+$("meta[name=params]").attr("content"),
      type: "POST",
      processData: true,
    },
    columnDefs: [
      {
        targets: [0, 4, 5, 6],
        orderable: false,
      },
    ],
    lengthMenu: [
      [10, 25, 50, 100, -1],
      ["10", "25", "50", "100", "All"],
    ],
  });

  $(".dataTables_length select").addClass("form-select form-select-sm"),
  $("#dt_atlets_paginate").addClass("pagination-rounded");
}

function fetchPartcipant() {
  var dataTablePartcipant = $("#dt_participants").DataTable({
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
    destroy: true,
    processing: true,
    serverSide: true,
    responsive: true,
    order: [],
    ajax: {
      url: $("meta[name=site-url]").attr("content")+"sport_championships/list?scope="+$("#dt_participants").data("scope")+"&format=DT"+"&params="+$("meta[name=params]").attr("content"),
      type: "POST",
      processData: true,
    },
    columnDefs: [
      {
        targets: [0, 4, 5],
        orderable: false,
      },
    ],
    lengthMenu: [
      [10, 25, 50, 100, -1],
      ["10", "25", "50", "100", "All"],
    ],
  });

  $(".dataTables_length select").addClass("form-select form-select-sm"),
  $("#dt_participants_paginate").addClass("pagination-rounded");
}

$("#form-championship").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'sport_championships/store?scope='+$('meta[name=scope]').attr("content")+'&id='+$('meta[name=params]').attr("content"),
      data: $('#form-championship').serialize(),
      type: "POST",
      beforeSend: function() {
        requestBefore('submit');
      },
      success: function(response) {
        setTimeout(function() {
          location.reload();
        }, 2e3)
      },
      error: function() {
        setTimeout(function() {
          requestSuccess('submit');
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
          url: $('meta[name=site-url]').attr("content")+'sport_championships/delete?scope='+scope+'&id='+id,
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
