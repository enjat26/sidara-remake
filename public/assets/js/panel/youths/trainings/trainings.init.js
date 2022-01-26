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

var trainingChartOptions = {
  chart: {
    height: 500,
    type: "bar",
    toolbar: {
      show: false,
    },
  },
  plotOptions: { bar: { horizontal: !0 } },
  series: [
    {
      name: "Peserta",
      data: [],
    },
  ],
  colors: ["#556ee6"],
  xaxis: {
    categories: [],
  },
  grid: {
    borderColor: "#f1f1f1",
  },
  tooltip: {
    x: {
      format: "dd/MM/yy HH:mm",
    },
  },
};

var trainingChart = new ApexCharts(
  document.querySelector("#chartTraining"),
  trainingChartOptions
);

trainingChart.render();

initialize();

function initialize() {
  initComponents(),
  initButton(),
  initModal();
}

function initializeData() {
  chartTraining(),
  fetchTraining();
}

function initComponents() {
  $('.select2').select2({
    placeholder: function() {
      $(this).data('placeholder');
    },
  });

  $('[data-provide="datepicker"]').datepicker({
    format: 'dd/mm/yyyy',
    autoclose: true,
    language: 'id',
    pickTime: false
  });

  $('[data-provide="yearpicker"]').datepicker({
    format: "yyyy",
    viewMode: "years",
    minViewMode: "years",
  });
}

function initButton() {
  $('[name="filter-year"]').on("change", function () {
    chartTraining();
  });

  $('[key="rfs-training"]').on("click", function () {
    $('.select2').val('').trigger('change'),
    initializeData();
  });

  $('[key="add-training"]').on("click", function () {
    scope  = $(this).data("scope");
    value   = 'add';

    $('#modal-training .modal-title').text('Tambah Pelatihan Baru'),
    $('#modal-training [type="submit"]').text('Tambah');
  });

  $('[key="export-print"]').on("click", function () {
    $('#form-export').attr('action', $('meta[name=site-url]').attr("content")+'youth_trainings/export/print'),
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
  $("#modal-training").on("hidden.bs.modal", function () {
    resetForm('training'),
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
}

function fetchTraining() {
  var dataTable = $('#dt_trainings').DataTable({
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
      url: $('meta[name=site-url]').attr("content")+"youth_trainings/list?scope="+$('meta[name=scope]').attr("content")+"&format=DT",
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

  $('[name="filter-year"]').on('change', function () {
    dataTable.column(2).search(this.value).draw();
  });

  $(".dataTables_length select").addClass('form-select form-select-sm'),
  $("#dt_trainings_paginate").addClass("pagination-rounded");
}

function chartTraining() {
  $.ajax({
    url: $("meta[name=site-url]").attr("content") + "youth_trainings/show",
    data: "scope="+$("#chartTraining").data("scope")+"&format=JSON&id="+$("#chartTraining").data("val")+"&year="+$('[name="filter-year"]').val(),
    type: "GET",
    success: function (response) {
      trainingChart.updateOptions({
        xaxis: {
          categories: response.data.trainingData,
        },
      });

      trainingChart.updateSeries([
        {
          data: response.data.participantData,
        },
      ]);
    },
  });
}

$("#form-training").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'youth_trainings/store?scope='+scope+'&id='+value,
      data: $('#form-training').serialize(),
      type: "POST",
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
            pushToastr(response.type, response.header, response.message.success), requestSuccess('modal'), $('#modal-training').modal('hide');
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

function putTraining(method, id) {
  scope = method;
  value  = id;

  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'youth_trainings/get',
    data: "scope="+scope+"&format=JSON&id="+id,
    type: "GET",
    success: function(response) {
      $('#form-training [name="name"]').val(response.data.youth_training_name),
      $('[name="year"]').val(response.data.youth_training_year).datepicker('update'),
      $('[name="date"]').val(parseDatepicker(response.data.youth_training_date)).datepicker('update'),
      $('[name="explanation"]').val(response.data.youth_training_explanation),
      $('[name="province"]').val(response.data.province_id).trigger('change');

      district = response.data.youth_training_district_id;

      $('#modal-training .modal-title').text('Ubah Informasi Pelatihan'),
      $('#modal-training [type="submit"]').text('Simpan');
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
          url: $('meta[name=site-url]').attr("content")+'youth_trainings/delete?scope='+scope+'&id='+id,
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
