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
var province;

initialize();

function initialize() {
  initComponents(),
  initButton(),
  initModal();
}

function initializeData() {
  fetchDistricts();
}

function initComponents() {
  $('.select2').select2({
    placeholder: function() {
      $(this).data('placeholder');
    },
  });
}

function initButton() {
  $('[key="rfs-district"]').on("click", function () {
    resetForm('district'),
    $('.select2').val('').trigger('change'),
    initializeData();
  });

  $('[key="add-district"]').on("click", function () {
    value   = 'add';

    $('#modal-district .modal-title').text('Tambah Daerah Baru'),
    $('#modal-district [type="submit"]').text('Tambah');
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
}

function initModal() {
  $("#modal-district").on("hidden.bs.modal", function () {
    resetForm('district'),
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
  province  = '';
}

function fetchDistricts() {
  var dataTable = $('#dt_districts').DataTable({
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
      url: $('meta[name=site-url]').attr("content")+"districts/list?scope="+$('meta[name=scope]').attr("content")+"&format=DT",
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

  $('[name="filter-type"]').on('change', function () {
    dataTable.column(1).search(this.value).draw();
  });

  $('[name="filter-province"]').on('change', function () {
    dataTable.column(3).search(this.value).draw();
  });

  $('[name="filter-country"]').on('change', function () {
    dataTable.column(4).search(this.value).draw();
  });

  $(".dataTables_length select").addClass('form-select form-select-sm'),
  $("#dt_districts_paginate").addClass("pagination-rounded");
}

$("#form-district").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'districts/store?scope='+$('meta[name=scope]').attr("content")+'&id='+value,
      data: $('#form-district').serialize(),
      type: "POST",
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          pushToastr(response.type, response.header, response.message.success), requestSuccess('modal'), $('#modal-district').modal('hide');
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

function putDistrict(id) {
  value  = id;

  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'districts/get',
    data: "scope="+$('meta[name=scope]').attr("content")+"&format=JSON&id="+id,
    type: "GET",
    success: function(response) {
      $('[name="country"]').val(response.data.country_id).trigger('change'),
      $('[name="type"]').val(response.data.district_type).trigger('change'),
      $('[name="name"]').val(response.data.district_name),
      $('[name="latitude"]').val(response.data.district_latitude),
      $('[name="longitude"]').val(response.data.district_longitude);

      province = response.data.province_id;

      $('#modal-district .modal-title').text('Ubah Informasi Daerah'),
      $('#modal-district [type="submit"]').text('Simpan');
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
      pushSwalConfirmBeforeDelete(redirect, 'districts/delete?scope='+scope+'&id='+id);
    } else {
      pushSwalCancel()
    }
  });
}
