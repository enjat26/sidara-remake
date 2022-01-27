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
var district, type;

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
  chartAtlets(),
  chartMap(),
  fetchAtlets();
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
}

function initButton() {
  $('[key="rfs-atlet"]').on("click", function () {
    resetForm('atlet'),
    $('.select2').val('').trigger('change'),
    initializeData();
  });

  $('[key="add-atlet"]').on("click", function () {
    scope  = $(this).data("scope");
    value   = 'add';

    $('#modal-atlet .modal-title').text('Tambah Atlet Baru'),
    $('#modal-atlet [type="submit"]').text('Tambah');
  });

  $('[key="upd-avatar"]').on("click", function () {
    $('#image').click();
  });

  $('#image').on("change", function () {
    previewImage(this);
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

  $('[key="del-atlet"]').on('click', function () {
    deleteMethod(true, $(this).data("scope"), $(this).data("val"));
  });
}

function initModal() {
  $("#modal-atlet").on("hidden.bs.modal", function () {
    resetForm('atlet'),
    $('.select2').val('').trigger('change'),
    $('[key="avatar"]').attr('src', $('[key="avatar"]').data('src')),
    $('[data-provide="datepicker"]').val('').datepicker('update'),
    $('[data-provide="datepicker"]').prop('readonly', true),
    $('[data-provide="yearpicker"]').val('').datepicker('update'),
    $('[data-provide="yearpicker"]').prop('readonly', true),
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

function previewImage(input, target) {
  var file = event.target.files[0];
  var img = new Image();

  if (file && file.size >= 2*1024*1024) {
    document.getElementById('image').value = "",
    pushToastr('warning', '406 Not Acceptable', 'Ukuran maksimal gambar yang diizinkan hanya <strong>2MB</strong>!');
  } else if (file && !file.type.match('image/jp.*|image/png')) {
    document.getElementById('image').value = "",
    pushToastr('warning', '405 Method Not Allowed', 'Format gambar yang diizinkan hanya <strong>JPG, JPEG, dan PNG</strong>');
  } else if (file && input.files && input.files[0]) {
    var fileReader = new FileReader();
    fileReader.onload = function (e) {
      $('[key="avatar"]').attr('src', e.target.result);
    };
    fileReader.readAsDataURL(input.files[0]);
  }
}

function chartAtlets() {
  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'sport_atlets/show',
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

function chartMap() {
  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'sport_atlets/show',
    data: "scope="+$('#chartMap').data("scope")+"&format=JSON&id="+$('#chartMap').data("val"),
    type: "GET",
    success: function(response) {
      // Themes begin
      am4core.useTheme(am4themes_animated);

      // Create map instance
      var chart = am4core.create("chartMap", am4maps.MapChart);

      var title = chart.titles.create();
      title.text = "[bold font-size: 20]Persentase Atlet berdasarkan Wilayah[/]\nsource: Banten";
      title.textAlign = "middle";

      var mapData = response.data.districtData;

      // Set map definition
      chart.geodata = am4geodata_worldLow;

      // Set projection
      chart.projection = new am4maps.projections.Miller();

      // Create map polygon series
      var polygonSeries = chart.series.push(new am4maps.MapPolygonSeries());
      polygonSeries.exclude = ["AQ"];
      polygonSeries.useGeodata = true;

      // Use series data to set custom zoom points for countries
      polygonSeries.data = [{
        "id": response.data.provinceData.province_id,
        "zoomLevel": 70,
        "zoomGeoPoint": {
          "latitude": parseFloat(response.data.provinceData.province_latitude),
          "longitude": parseFloat(response.data.provinceData.province_longitude)
        }
      }];

      polygonSeries.dataFields.zoomLevel = "zoomLevel";
      polygonSeries.dataFields.zoomGeoPoint = "zoomGeoPoint";

      var imageSeries = chart.series.push(new am4maps.MapImageSeries());
      imageSeries.data = mapData;
      imageSeries.dataFields.value = "value";

      var imageTemplate = imageSeries.mapImages.template;
      imageTemplate.propertyFields.latitude = "latitude";
      imageTemplate.propertyFields.longitude = "longitude";
      imageTemplate.nonScaling = true

      var circle = imageTemplate.createChild(am4core.Circle);
      circle.fillOpacity = 0.7;
      circle.propertyFields.fill = "color";
      circle.tooltipText = "{name}: [bold]{value}[/]";

      imageSeries.heatRules.push({
        "target": circle,
        "property": "radius",
        "min": 5,
        "max": 15,
        "dataField": "value"
      })

      chart.events.on("ready", function(ev) {
        var target = polygonSeries.getPolygonById(response.data.provinceData.province_id);

        // Pre-zoom
        chart.zoomToMapObject(target);

        // Set active state
        setTimeout(function() {
          target.isActive = true;
        }, 1000);
      });
    },
  });

  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'sport_atlets/show',
    data: "scope="+$('#percentageMap').data("scope")+"&format=HTML&id="+$('#percentageMap').data("val"),
    type: "GET",
    dataType: "HTML",
  }).done(function(response) {
    $('#percentageMap').html(response);
  });
}

function fetchAtlets() {
  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'sport_atlets/count',
    data: "scope="+$('[key="total"]').data("scope")+"&format=JSON&id="+$('[key="total"]').data("val"),
    type: "GET",
    success: function(response) {
      $('[key="total"]').text(response.data);
    },
  });

  var dataTable = $('#dt_atlets').DataTable({
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
      url: $('meta[name=site-url]').attr("content")+"sport_atlets/list?scope="+$('meta[name=scope]').attr("content")+"&format=DT",
      type: "POST",
      processData: true,
    },
    "columnDefs": [{
      targets: [0, 5, 6, 7, 8,9],
      orderable: false,
    }],
    lengthMenu: [
      [10, 25, 50, 100, -1],
      ['10', '25', '50', '100', 'All']
    ],
  });

  $('[name="filter-gender"]').on('change', function () {
    dataTable.column(2).search(this.value).draw();
  });

  $('[name="filter-cabor"]').on('change', function () {
    dataTable.column(3).search(this.value).draw();
  });

  $('[name="filter-area"]').on('change', function () {
    dataTable.column(4).search(this.value).draw();
  });

  $(".dataTables_length select").addClass('form-select form-select-sm'),
  $("#dt_atlets_paginate").addClass("pagination-rounded");
}

$("#form-atlet").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'sport_atlets/store?scope='+scope+'&id='+value,
      data: new FormData($('#form-atlet')[0]),
      type: "POST",
      contentType: false,
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          pushToastr(response.type, response.header, response.message.success), requestSuccess('modal'), $('#modal-atlet').modal('hide');
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
    url:  $('meta[name=site-url]').attr("content")+'sport_atlets/update?scope='+scope+"&id="+id,
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
      pushSwalConfirmBeforeDelete(redirect, 'sport_atlets/delete?scope='+scope+'&id='+id);
    } else {
      pushSwalCancel()
    }
  });
}
