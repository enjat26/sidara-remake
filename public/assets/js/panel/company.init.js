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

var scope , value;
var province, district, subdistrict, village;

initialize();

function initialize() {
  initComponents(),
  initButton(),
  initModal();
}

function initializeData() {
  fetchCompany(),
  fetchSocialMedia();
}

function initComponents() {
  $('.select2').select2({
    placeholder: function() {
      $(this).data('placeholder');
    },
  });

  $(".inputtags").tagify();

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
  $('[key="upd-company"]').on('click', function () {
    scope  = $(this).data("scope");

    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'company/get',
      data: "scope="+$('[key="upd-company"]').data("scope")+"&format=JSON",
      type: "GET",
      success: function(response) {
        $('[name="name"]').val(response.data.name),
        $('[name="code"]').val(response.data.code),
        $('[name="tags"]').data('tagify').addTags(response.data.tags),
        $('[name="type"]').val(response.data.type),
        $('[name="description"]').val(response.data.description),
        $('[name="domain"]').val(response.data.domain),

        $('[name="address"]').val(response.data.address),
        $('[name="country"]').val(response.data.country_id).trigger('change'),
        $('[name="zipcode"]').val(response.data.zip_code),

        $('[name="email"]').val(response.data.email),
        $('[name="phone"]').val(response.data.phone);

        province    = response.data.province_id;
        district    = response.data.district_id;
        subdistrict = response.data.sub_district_id;
        village     = response.data.village_id;
      },
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

  $('[name="subdistrict"]').on('change', function () {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'areas/list',
      data: "format=Dropdown&scope="+$(this).data("scope")+"&id="+$(this).val(),
      type: "GET",
      dataType: "HTML",
    }).done(function(response) {
      if (!response) {
        $('[name="village"]').html('<option></option>');
      } else {
        $('[name="village"]').html('<option></option>'+response);

        if (village) {
          $('[name="village"]').val(village).trigger('change');
        }
      }
    });
  });

  $('[key="add-social"]').on('click', function () {
    scope   = $(this).data("scope");
    value   = 'add';
  });

  $('[name="sosprov"]').on('change', function () {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'company/get',
      data: "scope="+$(this).data("scope")+"&format=JSON&id="+$(this).val(),
      type: "GET",
      success: function(response) {
        if (response.data) {
          $('[key="sosprov-url"]').text(response.data.sosprov_url);
          $('[name="sosmed"]').attr('placeholder', 'Masukan username '+response.data.sosprov_name);
        } else {
          $('[key="sosprov-url"]').text('URL'),
          $('[name="sosmed"]').attr('placeholder', 'Masukan username sosial media');
        }
      },
    });
  });

  $('[key="upd-square-light"]').on('click', function () {
    scope   = $(this).data("scope");
    value   = $(this).data("val");
    $('#modal-image .modal-title').text('Merubah Logo Aplikasi');
    $('#modal-image .card-caption').text('Merubah Logo Kotak (Cerah)');
  });

  $('[key="upd-square-dark"]').on('click', function () {
    scope   = $(this).data("scope");
    value   = $(this).data("val");
    $('#modal-image .modal-title').text('Merubah Logo Aplikasi');
    $('#modal-image .card-caption').text('Merubah Logo Kotak (Gelap)');
  });

  $('[key="upd-landscape-light"]').on('click', function () {
    scope   = $(this).data("scope");
    value   = $(this).data("val");
    $('#modal-image .modal-title').text('Merubah Logo Aplikasi');
    $('#modal-image .card-caption').text('Merubah Logo Panjang (Cerah)');
  });

  $('[key="upd-landscape-dark"]').on('click', function () {
    scope   = $(this).data("scope");
    value   = $(this).data("val");
    $('#modal-image .modal-title').text('Merubah Logo Aplikasi');
    $('#modal-image .card-caption').text('Merubah Logo Panjang (Gelap)');
  });

  $('[key="upd-qr"]').on('click', function () {
    scope   = $(this).data("scope");
    value   = $(this).data("val");
    $('#modal-image .modal-title').text('Merubah Logo QR');
    $('#modal-image .card-caption').text('Merubah Logo pada QR');
  });

  $('[key="upd-abstract"]').on('click', function () {
    scope   = $(this).data("scope");
    value   = $(this).data("val");
    $('#modal-image .modal-title').text('Merubah Latar Belakang');
    $('#modal-image .card-caption').text('Merubah Logo Latar Belakang (Abstrak)');
  });

  $('[key="upd-hero"]').on('click', function () {
    scope   = $(this).data("scope");
    value   = $(this).data("val");
    $('#modal-image .modal-title').text('Merubah Latar Belakang');
    $('#modal-image .card-caption').text('Merubah Logo Latar Belakang (Hero)');
  });

  $('[key="upd-page"]').on('click', function () {
    scope   = $(this).data("scope");
    value   = $(this).data("val");
    $('#modal-image .modal-title').text('Merubah Latar Belakang');
    $('#modal-image .card-caption').text('Merubah Logo Latar Belakang (Halaman)');
  });
}

function initModal() {
  $("#modal-company").on("hidden.bs.modal", function () {
    resetForm('company'),
    $('[name="tags"]').data('tagify').removeAllTags(),
    initializeData();
  });

  $("#modal-social").on("hidden.bs.modal", function () {
    resetForm('social'),
    $('.select2').val('').trigger('change'),
    initializeData();
  });

  $("#modal-image").on("hidden.bs.modal", function () {
    resetForm('image'),
    $('.dropify-clear').click(),
    initializeData();
  });
}

function resetForm(form) {
  $('#form-'+form)[0].reset(),
  $('#form-'+form).removeClass('was-validated');
  $('.invalid-tooltip').remove();
  scope   = '';
  value   = '';
}

function fetchCompany() {
  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'company/get',
    data: "scope="+$('[key="upd-company"]').data("scope")+"&format=HTML",
    type: "GET",
    success: function(response) {
      $('[key="type"]').text(response.data.type);
      $('[key="comname"]').text(response.data.name);
      $('[key="email"]').text(response.data.email);
      $('[key="description"]').text(response.data.description);
      $('[key="address"]').html(response.data.address);
      $('[key="phone"]').text(response.data.phone);
      $('[key="tags"]').html(response.data.tags);
      $('[key="domain"]').html(response.data.domain);

      $('[key="logo|square-light"]').attr('src', response.data.logo_square_light);
      $('[key="logo|square-dark"]').attr('src', response.data.logo_square_dark);
      $('[key="logo|landscape-light"]').attr('src', response.data.logo_landscape_light);
      $('[key="logo|landscape-dark"]').attr('src', response.data.logo_landscape_dark);

      $('[key="upd-square-light"] img').attr('src', response.data.logo_square_light);
      $('[key="upd-square-dark"] img').attr('src', response.data.logo_square_dark);
      $('[key="upd-landscape-light"] img').attr('src', response.data.logo_landscape_light);
      $('[key="upd-landscape-dark"] img').attr('src', response.data.logo_landscape_dark);
      $('[key="upd-qr"] img').attr('src', response.data.logo_qr);
      $('[key="upd-abstract"] img').attr('src', response.data.background_abstract);
      $('[key="upd-hero"] img').attr('src', response.data.background_hero);
      $('[key="upd-page"] img').attr('src', response.data.background_page);
    },
  });
}

function fetchSocialMedia() {
  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'company/list',
    data: "scope="+$('[key="add-social"]').data("scope")+"&format=HTML",
    type: "GET",
    dataType: "HTML",
  }).done(function(response) {
    if (!response) {
      $('.social-media').html('<p class="text-muted text-center mb-0"><i>Belum menautkan media sosial</i></p>');
    } else {
      $('.social-media').html(response);
    }

    $('[key="del-social"]').on('click', function () {
      deleteMethod(false, $(this).data("scope"), $(this).data("val"));
    });
  });
}

$("#form-company").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'company/store?scope='+scope ,
      data: $('#form-company').serialize(),
      type: "POST",
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          pushToastr(response.type, response.header, response.message.success), requestSuccess('modal'), $('#modal-company').modal('hide');
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

$("#form-social").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'company/store?scope='+scope +"&id="+value,
      data: $('#form-social').serialize(),
      type: "POST",
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          pushToastr(response.type, response.header, response.message.success), requestSuccess('modal'), $('#modal-social').modal('hide');
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

$("#form-image").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'company/update?scope='+scope +"&id="+value,
      data: new FormData($('#form-image')[0]),
      type: "POST",
      contentType: false,
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        pushToastr(response.type, response.header, response.message.success), requestSuccess('modal'), $('#modal-image').modal('hide');
      },
      error: function() {
        setTimeout(function() {
          requestSuccess('modal');
        }, 2e3)
      },
    });
  }
});

function deleteMethod(redirect, scope , id) {
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
          url: $('meta[name=site-url]').attr("content")+'company/delete?scope='+scope +'&id='+id,
          type: "DELETE",
          success: function(response){
            if (redirect == true) {
              location.reload();
            } else {
              pushSwal(response.type, response.header, response.message.success), initializeData();
            }
          },
      });
    } else {
      pushSwalCancel()
    }
  });
}
