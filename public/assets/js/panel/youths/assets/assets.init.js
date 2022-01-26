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
var district;

var typeCTX = document.getElementById("chartType").getContext("2d");
var typeChart = new Chart(typeCTX, {
    type: 'doughnut',
    data: {
      labels: [],
      datasets: [{
        data: [],
        backgroundColor: [],
        hoverBackgroundColor: [],
        hoverBorderColor: "#fff"
      }],
    },
});

initialize();

function initialize() {
  initComponents(),
  initButton(),
  initModal();
}

function initializeData() {
  chartAsset(),
  fetchAsset();
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

function initButton() {
  $('[key="add-category"]').on("click", function () {
    value   = 'add';

    $('#modal-category .modal-title').text('Tambah Ketegori Asset Baru'),
    $('#modal-category [type="submit"]').text('Tambah');
  });

  $('[key="del-category"]').on('click', function () {
    deleteMethod(true, $(this).data("scope"), $(this).data("val"));
  });

  $('[key="rfs-asset"]').on("click", function () {
    $('.select2').val('').trigger('change'),
    $('[data-provide="yearpicker"]').datepicker('setDate', null),
    $('[data-provide="yearpicker"]').prop('readonly', true),
    initializeData();
  });

  $('[key="add-asset"]').on("click", function () {
    scope  = $(this).data("scope");
    value   = 'add';

    $('#modal-asset .modal-title').text('Tambah Data Prestasi Pramuka'),
    $('#modal-asset [type="submit"]').text('Tambah');
  });

  $('[key="export-print"]').on("click", function () {
    $('#form-export').attr('action', $('meta[name=site-url]').attr("content")+'youth_assets/export/print'),
    $('#form-export').submit();
  });

  $('[key="export-pdf"]').on("click", function () {
    $('#form-export').attr('action', $('meta[name=site-url]').attr("content")+'youth_assets/export/pdf'),
    $('#form-export').submit();
  });

  $('[key="export-excel"]').on("click", function () {
    $('#form-export').attr('action', $('meta[name=site-url]').attr("content")+'youth_assets/export/excel'),
    $('#form-export').submit();
  });

  $('[name="province"]').on('change', function () {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'areas/list',
      data: "format=Dropdown&scope="+$(this).data("scope")+"&id="+$(this).val(),
      type: "GET",
      dataType: "HTML",
    }).done(function(response) {
      if (!response) {
        $('[name="district"]').html('<option></option>');
      } else {
        $('[name="district"]').html('<option></option>'+response);

        if (district) {
          $('[name="district"]').val(district).trigger('change');
        }
      }
    });
  });
}

function initModal() {
  $("#modal-category").on("hidden.bs.modal", function () {
    resetForm('category'),
    initializeData();
  });

  $("#modal-asset").on("hidden.bs.modal", function () {
    resetForm('asset'),
    $('.select2').val('').trigger('change'),
    $('[data-provide="yearpicker"]').datepicker('setDate', null),
    $('[data-provide="yearpicker"]').prop('readonly', true),
    initializeData();
  });
}

function resetForm(form) {
  $('#form-'+form)[0].reset(),
  $('#form-'+form).removeClass('was-validated'),
  $('.invalid-tooltip').remove(),
  scope     = '';
  value     = '';
  district  = '';
}

function chartAsset() {
  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'youth_assets/show',
    data: "scope="+$('#chartType').data("scope")+"&format=JSON&id="+$('#chartType').data("val"),
    type: "GET",
    success: function(response) {
      typeChart.data.labels = response.data.label;
      typeChart.data.datasets[0].backgroundColor = response.data.dataset.color;
      typeChart.data.datasets[0].hoverBackgroundColor = response.data.dataset.color;
      typeChart.data.datasets[0].data = response.data.dataset.value;
      typeChart.update();
    },
  });
}

function fetchAsset() {
  var dataTable = $('#dt_assets').DataTable({
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
      url: $('meta[name=site-url]').attr("content")+"youth_assets/list?scope="+$('meta[name=scope]').attr("content")+"&format=DT",
      type: "POST",
      processData: true,
    },
    "columnDefs": [{
      targets: [0, 4, 5, 6, 7],
      orderable: false,
    }],
    lengthMenu: [
      [10, 25, 50, 100, -1],
      ['10', '25', '50', '100', 'All']
    ],
  });

  $('[name="filter-type"]').on('change', function () {
    dataTable.column(3).search(this.value).draw();
  });

  $(".dataTables_length select").addClass('form-select form-select-sm'),
  $("#dt_assets_paginate").addClass("pagination-rounded");
}

$("#form-category").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'youth_assets/store?scope='+$("#modal-category").data("scope")+'&id='+value,
      data: $('#form-category').serialize(),
      type: "POST",
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          $('#modal-category').modal('hide'), location.reload();;
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

$("#form-asset").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'youth_assets/store?scope='+scope+'&id='+value,
      data: $('#form-asset').serialize(),
      type: "POST",
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          pushToastr(response.type, response.header, response.message.success), requestSuccess('modal'), $('#modal-asset').modal('hide');
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

function putCategory(method, id) {
  scope = method;
  value  = id;

  $('#modal-list-category').modal('hide'),

  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'youth_assets/get',
    data: "scope="+scope+"&format=JSON&id="+id,
    type: "GET",
    success: function(response) {
      $('#modal-category [name="name"]').val(response.data.asset_category_name);

      $('#modal-category .modal-title').text('Ubah Informasi Kategori Sarana & Prasarana'),
      $('#modal-category [type="submit"]').text('Simpan')
      $('#modal-category').modal('show');
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
      $.ajax({
          url: $('meta[name=site-url]').attr("content")+'youth_assets/delete?scope='+scope+'&id='+id,
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
