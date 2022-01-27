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

initialize();

function initialize() {
  initComponents(),
  initButton(),
  initModal();
}

function initializeData() {
  fetchChampionship();
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
    $("#form-export").attr("action", $("meta[name=site-url]").attr("content")+"sport_cabors/export/"+param);
    $("#form-export").submit();
  });
}

function initButton() {
  submitExport('print');
  submitExport('pdf');
  submitExport('excel');
  
  $('[key="rfs-championship"]').on("click", function () {
    $('.select2').val('').trigger('change'),
    initializeData();
  });

  $('[key="add-championship"]').on("click", function () {
    scope  = $(this).data("scope");
    value   = 'add';

    $('#modal-championship .modal-title').text('Tambah Data Kejuaraan Olahraga'),
    $('#modal-championship [type="submit"]').text('Tambah');
  });

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
  $("#modal-championship").on("hidden.bs.modal", function () {
    resetForm('championship'),
    $('.select2').val('').trigger('change'),
    $('.dropify-clear').click(),
    $('[data-provide="yearpicker"]').val('').datepicker('update'),
    $('[data-provide="yearpicker"]').prop('readonly', true),
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

function fetchChampionship() {
  var dataTable = $('#dt_championships').DataTable({
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
      url: $('meta[name=site-url]').attr("content")+"sport_championships/list?scope="+$('meta[name=scope]').attr("content")+"&format=DT",
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
  $("#dt_championships_paginate").addClass("pagination-rounded");
}

$("#form-championship").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'sport_championships/store?scope='+scope+'&id='+value,
      data: $('#form-championship').serialize(),
      type: "POST",
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          pushToastr(response.type, response.header, response.message.success), requestSuccess('modal'), $('#modal-championship').modal('hide');
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

function putChampionship(method, id) {
  scope = method;
  value  = id;

  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'sport_championships/get',
    data: "scope="+scope+"&format=JSON&id="+id,
    type: "GET",
    success: function(response) {
      $('[name="code"]').val(response.data.sport_championship_code),
      $('[name="year"]').val(response.data.sport_championship_year).trigger('change'),
      $('#form-championship [name="name"]').val(response.data.sport_championship_name),
      $('[name="level"]').val(response.data.sport_championship_level).trigger('change'),
      $('[name="category"]').val(response.data.sport_championship_category).trigger('change'),
      $('[name="location"]').val(response.data.sport_championship_location)
      $('[name="explanation"]').val(response.data.sport_championship_explanation);

      $('#modal-championship .modal-title').text('Ubah Informasi Kejuaraan Olahraga'),
      $('#modal-championship [type="submit"]').text('Simpan');
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
