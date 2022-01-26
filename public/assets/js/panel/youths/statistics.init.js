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

var statisticLineChartOptions = {
    chart: {
      height: 350,
      type: 'area',
      toolbar: {
          show: false,
      },
      toolbar: {
				show: true
			},
      dropShadow: {
				enabled: true,
				top: 3,
				left: 14,
				blur: 4,
				opacity: 0.10,
			},
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      curve: 'smooth',
      width: 3,
    },
    series: [{
      name: "Laki-laki",
      data: []
    }, {
      name: "Perempuan",
      data: []
    }],
    fill: {
      type: "gradient",
      gradient: {
        shade: 'light',
        shadeIntensity: 1,
        opacityFrom: 0.7,
        opacityTo: 0.9,
        stops: [0, 90, 100]
      }
    },
    markers: {
			size: 4,
			colors: ['#556ee6', '#34c38f'],
			strokeColors: "#fff",
			strokeWidth: 2,
			hover: {
				size: 7,
			}
		},
    colors: ['#556ee6', '#34c38f'],
    xaxis: {
        categories: [],
    },
    grid: {
        borderColor: '#f1f1f1',
    },
}

var statisticLineChart = new ApexCharts(
    document.querySelector("#lineChartStatistic"),
    statisticLineChartOptions
);

statisticLineChart.render();

initialize();

function initialize() {
  initComponents(),
  initButton(),
  initModal();
}

function initializeData() {
  lineChartStatistic(),
  chartMap(),
  fetchStatistics();
}

function initComponents() {
  $('.select2').select2({
    placeholder: function() {
      $(this).data('placeholder');
    },
  });
}

function initButton() {
  $('[key="rfs-statistic"]').on("click", function () {
    resetForm('statistic'),
    initializeData();
  });

  $('[key="add-statistic"]').on("click", function () {
    value   = 'add';

    $('#modal-statistic .modal-title').text('Tambah Statistik Baru'),
    $('#modal-statistic [type="submit"]').text('Tambah');
  });

  $('[key="export-print"]').on("click", function () {
    window.open($('meta[name=site-url]').attr("content")+'youth_statistics/export/print', '_blank');
  });

  $('[key="export-pdf"]').on("click", function () {
    window.open($('meta[name=site-url]').attr("content")+'youth_statistics/export/pdf', '_blank');
  });

  $('[key="export-excel"]').on("click", function () {
    window.open($('meta[name=site-url]').attr("content")+'youth_statistics/export/excel', '_blank');
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
  $("#modal-statistic").on("hidden.bs.modal", function () {
    resetForm('statistic'),
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
  district  = '';
}

function fetchStatistics() {
  var dataTable = $('#dt_statistics').DataTable({
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
      url: $('meta[name=site-url]').attr("content")+"youth_statistics/list?scope="+$('meta[name=scope]').attr("content")+"&format=DT",
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

  $(".dataTables_length select").addClass('form-select form-select-sm'),
  $("#dt_statistics_paginate").addClass("pagination-rounded");
}

function lineChartStatistic() {
  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'youth_statistics/show',
    data: "scope="+$('#lineChartStatistic').data("scope")+"&format=JSON&id="+$('#lineChartStatistic').data("val"),
    type: "GET",
    success: function(response) {
      statisticLineChart.updateOptions({
        xaxis: {
          categories: response.data.year,
        },
      });

      statisticLineChart.updateSeries([{
        data: response.data.male,
      }, {
        data: response.data.female,
      }]);
    },
  });
}

function chartMap() {
  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'youth_statistics/show',
    data: "scope="+$('#chartMap').data("scope")+"&format=JSON&id="+$('#chartMap').data("val"),
    type: "GET",
    success: function(response) {
      // Themes begin
      am4core.useTheme(am4themes_animated);

      // Create map instance
      var chart = am4core.create("chartMap", am4maps.MapChart);

      var title = chart.titles.create();
      title.text = "[bold font-size: 20]Persentase Pemuda berdasarkan Kota/Kab[/]\nsource: Banten";
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
    url:  $('meta[name=site-url]').attr("content")+'youth_statistics/show',
    data: "scope="+$('#percentageMap').data("scope")+"&format=HTML&id="+$('#percentageMap').data("val"),
    type: "GET",
    dataType: "HTML",
  }).done(function(response) {
    $('#percentageMap').html(response);
  });
}

$("#form-statistic").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'youth_statistics/store?scope='+$('meta[name=scope]').attr("content")+'&id='+value,
      data: $('#form-statistic').serialize(),
      type: "POST",
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          pushToastr(response.type, response.header, response.message.success), requestSuccess('modal'), $('#modal-statistic').modal('hide');
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

function putStatistic(scope, id) {
  value  = id;

  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'youth_statistics/get',
    data: "scope="+scope+"&format=JSON&id="+id,
    type: "GET",
    success: function(response) {
      $('[name="province"]').val(response.data.province_id).trigger('change'),
      $('[name="male"]').val(response.data.statistic_male),
      $('[name="female"]').val(response.data.statistic_female),
      $('[name="explanation"]').val(response.data.statistic_explanation);

      district = response.data.district_id;

      $('#modal-statistic .modal-title').text('Ubah Informasi Statistik'),
      $('#modal-statistic [type="submit"]').text('Simpan');
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
          url: $('meta[name=site-url]').attr("content")+'youth_statistics/delete?scope='+scope+'&id='+id,
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
