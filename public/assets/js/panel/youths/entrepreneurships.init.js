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

var genderCTX = document.getElementById("chartGender").getContext("2d");
var genderChart = new Chart(genderCTX, {
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
  chartEntrepreneurship(),
  fetchEntrepreneurship();
}

function initComponents() {
  $('.select2').select2({
    placeholder: function() {
      $(this).data('placeholder');
    },
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
  $('[key="rfs-entrepreneurship"]').on("click", function () {
    $('.select2').val('').trigger('change'),
    initializeData();
  });

  $('[key="add-entrepreneurship"]').on("click", function () {
    scope  = $(this).data("scope");
    value   = 'add';

    $('#modal-entrepreneurship .modal-title').text('Tambah Data Prestasi Kewirausahaan'),
    $('#modal-entrepreneurship [type="submit"]').text('Tambah');
  });

  $('[key="export-print"]').on("click", function () {
    $('#form-export').attr('action', $('meta[name=site-url]').attr("content")+'youth_entrepreneurships/export/print'),
    $('#form-export').submit();
  });

  $('[key="export-pdf"]').on("click", function () {
    $('#form-export').attr('action', $('meta[name=site-url]').attr("content")+'youth_entrepreneurships/export/pdf'),
    $('#form-export').submit();
  });

  $('[key="export-excel"]').on("click", function () {
    $('#form-export').attr('action', $('meta[name=site-url]').attr("content")+'youth_entrepreneurships/export/excel'),
    $('#form-export').submit();
  });

  $('[name="filter-province"]').on('change', function () {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'areas/list',
      data: "format=Dropdown&scope="+$(this).data("scope")+"&id="+$(this).val(),
      type: "GET",
      dataType: "HTML",
    }).done(function(response) {
      if (!response) {
        $('[name="filter-district"]').html('<option></option>');
      } else {
        $('[name="filter-district"]').html('<option></option>'+response);

        if (district) {
          $('[name="filter-district"]').val(district).trigger('change');
        }
      }
    });
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
  $("#modal-entrepreneurship").on("hidden.bs.modal", function () {
    resetForm('entrepreneurship'),
    $('.select2').val('').trigger('change'),
    $('.dropify-clear').click(),
    $('[key="file-existing"]').hide(),
    $('[key="file-missing"]').hide(),
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

function chartEntrepreneurship() {
  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'youth_entrepreneurships/show',
    data: "scope="+$('#chartGender').data("scope")+"&format=JSON&id="+$('#chartGender').data("val"),
    type: "GET",
    success: function(response) {
      genderChart.data.labels = response.data.label;
      genderChart.data.datasets[0].backgroundColor = response.data.dataset.color;
      genderChart.data.datasets[0].hoverBackgroundColor = response.data.dataset.color;
      genderChart.data.datasets[0].data = response.data.dataset.value;
      genderChart.update();
    },
  });
}

function fetchEntrepreneurship() {
  var dataTable = $('#dt_entrepreneurships').DataTable({
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
      url: $('meta[name=site-url]').attr("content")+"youth_entrepreneurships/list?scope="+$('meta[name=scope]').attr("content")+"&format=DT",
      type: "POST",
      processData: true,
    },
    "columnDefs": [{
      targets: [0, 3, 4, 5, 6, 7],
      orderable: false,
    }],
    lengthMenu: [
      [10, 25, 50, 100, -1],
      ['10', '25', '50', '100', 'All']
    ],
  });

  $('[name="filter-district"]').on('change', function () {
    dataTable.column(3).search(this.value).draw();
  });

  $(".dataTables_length select").addClass('form-select form-select-sm'),
  $("#dt_entrepreneurships_paginate").addClass("pagination-rounded");
}

$("#form-entrepreneurship").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'youth_entrepreneurships/store?scope='+scope+'&id='+value,
      data: new FormData($('#form-entrepreneurship')[0]),
      type: "POST",
      contentType: false,
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          pushToastr(response.type, response.header, response.message.success), requestSuccess('modal'), $('#modal-entrepreneurship').modal('hide');
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

function getEntrepreneurship(method, id) {
  scope = method;
  value  = id;

  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'youth_entrepreneurships/get',
    data: "scope="+scope+"&format=HTML&id="+id,
    type: "GET",
    success: function(response) {
      $('[key="type"]').html(response.data.type),
      $('#modal-view-entrepreneurship [key="name"]').html(response.data.name),
      $('[key="ownership"]').html(response.data.ownership)
      $('[key="gender"]').html(response.data.gender),
      $('[key="employee"]').html(response.data.employee),
      $('[key="file"]').html(response.data.file),
      $('[key="address"]').html(response.data.address),
      $('[key="district"]').html(response.data.district),
      $('[key="created_by"]').html(response.data.created_by),

      $('#modal-view-entrepreneurship .modal-title').text('Rincian Prestasi Kewirausahaan');
    },
  });
}

function putEntrepreneurship(method, id) {
  scope = method;
  value  = id;

  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'youth_entrepreneurships/get',
    data: "scope="+scope+"&format=JSON&id="+id,
    type: "GET",
    success: function(response) {
      $('[name="type"]').val(response.data.entrepreneurship_business_type),
      $('#form-entrepreneurship [name="name"]').val(response.data.entrepreneurship_business_name),
      $('[name="ownership"]').val(response.data.entrepreneurship_ownership),
      $('[name="gender"]').val(response.data.entrepreneurship_ownership_gender).trigger('change'),
      $('[name="employee"]').val(response.data.entrepreneurship_total_employee)
      $('[name="address"]').val(response.data.entrepreneurship_address),
      $('[name="province"]').val(response.data.province_id).trigger('change');

      if (response.data.file_id) {
        $('[key="file-existing"]').show();
      } else {
        $('[key="file-missing"]').show();
      }

      district  = response.data.district_id;

      $('#modal-entrepreneurship .modal-title').text('Ubah Informasi Prestasi Kewirausahaan'),
      $('#modal-entrepreneurship [type="submit"]').text('Simpan');
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
          url: $('meta[name=site-url]').attr("content")+'youth_entrepreneurships/delete?scope='+scope+'&id='+id,
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
