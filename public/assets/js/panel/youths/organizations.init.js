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
  fetchOrganization();
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
  $('[key="rfs-organization"]').on("click", function () {
    $('.select2').val('').trigger('change'),
    initializeData();
  });

  $('[key="add-organization"]').on("click", function () {
    scope  = $(this).data("scope");
    value   = 'add';

    $('#modal-organization .modal-title').text('Tambah Data Organisasi Pemuda'),
    $('#modal-organization [type="submit"]').text('Tambah');
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
  $("#modal-organization").on("hidden.bs.modal", function () {
    resetForm('organization'),
    $('.select2').val('').trigger('change'),
    $('.dropify-clear').click(),
    $('[data-provide="yearpicker"]').datepicker('setDate', null),
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

function fetchOrganization() {
  var dataTable = $('#dt_organizations').DataTable({
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
      url: $('meta[name=site-url]').attr("content")+"youth_organizations/list?scope="+$('meta[name=scope]').attr("content")+"&format=DT",
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

  $('[name="filter-district"]').on('change', function () {
    dataTable.column(3).search(this.value).draw();
  });

  $(".dataTables_length select").addClass('form-select form-select-sm'),
  $("#dt_organizations_paginate").addClass("pagination-rounded");
}

$("#form-organization").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'youth_organizations/store?scope='+scope+'&id='+value,
      data: new FormData($('#form-organization')[0]),
      type: "POST",
      contentType: false,
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          pushToastr(response.type, response.header, response.message.success), requestSuccess('modal'), $('#modal-organization').modal('hide');
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

function putOrganization(method, id) {
  scope = method;
  value  = id;

  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'youth_organizations/get',
    data: "scope="+scope+"&format=JSON&id="+id,
    type: "GET",
    success: function(response) {
      $('[name="code"]').val(response.data.youth_organization_code),
      $('[name="year_start"]').val(response.data.youth_organization_year_start).trigger('change'),
      $('[name="year_end"]').val(response.data.youth_organization_year_end).trigger('change'),
      $('#form-organization [name="name"]').val(response.data.youth_organization_name),
      $('[name="leader"]').val(response.data.youth_organization_leader),
      $('[name="number_of_member"]').val(response.data.youth_organization_number_of_member),
      $('[name="address"]').val(response.data.youth_organization_address),
      $('[name="province"]').val(response.data.province_id).trigger('change');

      if (response.data.youth_organization_file_id) {
        $('[key="file-existing"]').show();
      } else {
        $('[key="file-missing"]').show();
      }

      district  = response.data.district_id;

      $('#modal-organization .modal-title').text('Ubah Informasi Organisasi Pemuda'),
      $('#modal-organization [type="submit"]').text('Simpan');
    },
  });
}

function updateResub(redirect, scope, value) {
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
        url:  $('meta[name=site-url]').attr("content")+'youth_organizations/store?scope='+scope+'&id='+value,
        data: "action="+result.value,
        type: "POST",
        success: function(response) {
          setTimeout(function() {
            if (redirect == true) {
              location.reload();
            } else {
              pushToastr(response.type, response.header, response.message.success), initializeData();
            }
          }, 2e3)
        },
      });
    } else {
      pushSwalCancel()
    }
  });
}

function updateVerify(redirect, scope, value) {
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
        url:  $('meta[name=site-url]').attr("content")+'youth_organizations/store?scope='+scope+'&id='+value,
        data: "action="+result.value,
        type: "POST",
        success: function(response) {
          setTimeout(function() {
            if (redirect == true) {
              location.reload();
            } else {
              pushToastr(response.type, response.header, response.message.success), initializeData();
            }
          }, 2e3)
        },
      });
    } else {
      pushSwalCancel()
    }
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
          url: $('meta[name=site-url]').attr("content")+'youth_organizations/delete?scope='+scope+'&id='+id,
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
