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
var province, district, subdistrict;

initialize();

function initialize() {
  initComponents(),
  initButton(),
  initModal();
}

function initializeData() {
  fetchVillages();
}

function initComponents() {
  $('.select2').select2({
    placeholder: function() {
      $(this).data('placeholder');
    },
  });
}

function initButton() {
  $('[key="rfs-village"]').on("click", function () {
    resetForm('village'),
    $('.select2').val('').trigger('change'),
    initializeData();
  });

  $('[key="add-village"]').on("click", function () {
    value   = 'add';

    $('#modal-village .modal-title').text('Tambah Desa/Kelurahan Baru'),
    $('#modal-village [type="submit"]').text('Tambah');
  });

  $('[name="filter-country"]').on('change', function () {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'areas/list',
      data: "format=Dropdown&scope="+$(this).data("scope")+"&id="+$(this).val(),
      type: "GET",
      dataType: "HTML",
    }).done(function(response) {
      if (!response) {
        $('[name="filter-province"]').html('<option></option>');
      } else {
        $('[name="filter-province"]').html('<option></option>'+response);
      }
    });
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
      }
    });
  });

  $('[name="filter-district"]').on('change', function () {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'areas/list',
      data: "format=Dropdown&scope="+$(this).data("scope")+"&id="+$(this).val(),
      type: "GET",
      dataType: "HTML",
    }).done(function(response) {
      if (!response) {
        $('[name="filter-subdistrict"]').html('<option></option>');
      } else {
        $('[name="filter-subdistrict"]').html('<option></option>'+response);
      }
    });
  });

  $('[name="country"]').on('change', function () {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'areas/list',
      data: "format=Dropdown&scope="+$(this).data("scope")+"&id="+$(this).val(),
      type: "GET",
      dataType: "HTML",
    }).done(function(response) {
      if (!response) {
        $('[name="province"]').html('<option></option>');
      } else {
        $('[name="province"]').html('<option></option>'+response);

        if (province) {
          $('[name="province"]').val(province).trigger('change');
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

  $('[name="district"]').on('change', function () {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'areas/list',
      data: "format=Dropdown&scope="+$(this).data("scope")+"&id="+$(this).val(),
      type: "GET",
      dataType: "HTML",
    }).done(function(response) {
      if (!response) {
        $('[name="subdistrict"]').html('<option></option>');
      } else {
        $('[name="subdistrict"]').html('<option></option>'+response);

        if (subdistrict) {
          $('[name="subdistrict"]').val(subdistrict).trigger('change');
        }
      }
    });
  });
}

function initModal() {
  $("#modal-village").on("hidden.bs.modal", function () {
    resetForm('village'),
    $('.select2').val('').trigger('change'),
    initializeData();
  });
}

function resetForm(form) {
  $('#form-'+form)[0].reset(),
  $('#form-'+form).removeClass('was-validated'),
  $('.invalid-tooltip').remove(),
  scope       = '';
  value       = '';
  province    = '';
  district    = '';
  subdistrict = '';
}

function fetchVillages() {
  var dataTable = $('#dt_villages').DataTable({
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
      url: $('meta[name=site-url]').attr("content")+"villages/list?scope="+$('meta[name=scope]').attr("content")+"&format=DT",
      type: "POST",
      processData: true,
    },
    "columnDefs": [{
      targets: [0, 6, 7],
      orderable: false,
    }],
    lengthMenu: [
      [10, 25, 50, 100, -1],
      ['10', '25', '50', '100', 'All']
    ],
  });

  $('[name="filter-subdistrict"]').on('change', function () {
    dataTable.column(2).search(this.value).draw();
  });

  $('[name="filter-district"]').on('change', function () {
    dataTable.column(3).search(this.value).draw();
  });

  $('[name="filter-province"]').on('change', function () {
    dataTable.column(4).search(this.value).draw();
  });

  $('[name="filter-country"]').on('change', function () {
    dataTable.column(5).search(this.value).draw();
  });

  $(".dataTables_length select").addClass('form-select form-select-sm'),
  $("#dt_villages_paginate").addClass("pagination-rounded");
}

$("#form-village").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'villages/store?scope='+$('meta[name=scope]').attr("content")+'&id='+value,
      data: $('#form-village').serialize(),
      type: "POST",
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          pushToastr(response.type, response.header, response.message.success), requestSuccess('modal'), $('#modal-village').modal('hide');
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

function putVillage(id) {
  value  = id;

  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'villages/get',
    data: "scope="+$('meta[name=scope]').attr("content")+"&format=JSON&id="+id,
    type: "GET",
    success: function(response) {
      $('[name="country"]').val(response.data.country_id).trigger('change'),
      $('[name="type"]').val(response.data.village_type).trigger('change'),
      $('[name="name"]').val(response.data.village_name),
      $('[name="latitude"]').val(response.data.village_latitude),
      $('[name="longitude"]').val(response.data.village_longitude),

      $('#modal-village .modal-title').text('Ubah Informasi Desa/Kelurahan'),
      $('#modal-village [type="submit"]').text('Simpan');

      province      = response.data.province_id;
      district      = response.data.district_id;
      subdistrict   = response.data.sub_district_id;
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
      pushSwalConfirmBeforeDelete(redirect, 'villages/delete?scope='+scope+'&id='+id);
    } else {
      pushSwalCancel()
    }
  });
}
