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
var province, district;

initialize();

function initialize() {
  initComponents(),
  initButton(),
  initModal();
}

function initializeData() {
  fetchSubdistricts();
}

function initComponents() {
  $('.select2').select2({
    placeholder: function() {
      $(this).data('placeholder');
    },
  });
}

function initButton() {
  $('[key="rfs-subdistrict"]').on("click", function () {
    resetForm('subdistrict'),
    $('.select2').val('').trigger('change'),
    initializeData();
  });

  $('[key="add-subdistrict"]').on("click", function () {
    value   = 'add';

    $('#modal-subdistrict .modal-title').text('Tambah Kecamatan Baru'),
    $('#modal-subdistrict [type="submit"]').text('Tambah');
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
}

function initModal() {
  $("#modal-subdistrict").on("hidden.bs.modal", function () {
    resetForm('subdistrict'),
    $('.select2').val('').trigger('change'),
    initializeData();
  });
}

function resetForm(form) {
  $('#form-'+form)[0].reset(),
  $('#form-'+form).removeClass('was-validated'),
  $('.invalid-tooltip').remove(),
  scope    = '';
  value     = '';
  province  = '';
  district  = '';
}

function fetchSubdistricts() {
  var dataTable = $('#dt_subdistricts').DataTable({
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
      url: $('meta[name=site-url]').attr("content")+"subdistricts/list?scope="+$('meta[name=scope]').attr("content")+"&format=DT",
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

  $('[name="filter-district"]').on('change', function () {
    dataTable.column(2).search(this.value).draw();
  });

  $('[name="filter-province"]').on('change', function () {
    dataTable.column(3).search(this.value).draw();
  });

  $('[name="filter-country"]').on('change', function () {
    dataTable.column(4).search(this.value).draw();
  });

  $(".dataTables_length select").addClass('form-select form-select-sm'),
  $("#dt_subdistricts_paginate").addClass("pagination-rounded");
}

$("#form-subdistrict").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'subdistricts/store?scope='+$('meta[name=scope]').attr("content")+'&id='+value,
      data: $('#form-subdistrict').serialize(),
      type: "POST",
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          pushToastr(response.type, response.header, response.message.success), requestSuccess('modal'), $('#modal-subdistrict').modal('hide');
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

function putSubDistrict(id) {
  value  = id;

  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'subdistricts/get',
    data: "scope="+$('meta[name=scope]').attr("content")+"&format=JSON&id="+id,
    type: "GET",
    success: function(response) {
      $('[name="country"]').val(response.data.country_id).trigger('change'),
      $('[name="name"]').val(response.data.sub_district_name),
      $('[name="latitude"]').val(response.data.sub_district_latitude),
      $('[name="longitude"]').val(response.data.sub_district_longitude),

      province = response.data.province_id;
      district = response.data.district_id;

      $('#modal-subdistrict .modal-title').text('Ubah Informasi Kecamatan'),
      $('#modal-subdistrict [type="submit"]').text('Simpan');
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
      pushSwalConfirmBeforeDelete(redirect, 'subdistricts/delete?scope='+scope+'&id='+id);
    } else {
      pushSwalCancel()
    }
  });
}
