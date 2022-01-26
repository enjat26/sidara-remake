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
var province, district, subdistrict, village;

initialize();

function initialize() {
  initComponents(),
  initModal();
  initButton();
}

function initializeData() {
  fetchUser(),
  fetchSocial(),
  fetchActivity();
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

function initButton()
{
  $('[name="image"]').on('change', function () {
    var file = event.target.files[0];
    var img = new Image();

    if (file && file.size >= 2*1024*1024) {
      resetForm('image'),
      pushToastr('warning', '406 Not Acceptable', 'Ukuran maksimal gambar yang diizinkan hanya <strong>2MB</strong>!');
    } else if (file && !file.type.match('image/jp.*|image/png|image/gif')) {
      resetForm('image'),
      pushToastr('warning', '405 Method Not Allowed', 'Format gambar yang diizinkan hanya <strong>JPG, JPEG, PNG dan GIF</strong>');
    } else if (file && this.files && this.files[0]) {
      var fileReader = new FileReader();
      fileReader.onload = function (e) {
        $.ajax({
          url:  $('meta[name=site-url]').attr("content")+'profile/update?scope='+scope+'&id='+value,
          data: new FormData($('#form-image')[0]),
          type: "POST",
          contentType: false,
          success: function(response) {
            pushToastr(response.type, response.header, response.message.success), initializeData();
          },
        });
      };
      fileReader.readAsDataURL(this.files[0]);
    }
  });

  $('[key="del-cover"]').on('click', function () {
    deleteMethod(false, $(this).data("scope"), $(this).data("val"));
  });

  $('[key="del-avatar"]').on('click', function () {
    deleteMethod(true, $(this).data("scope"), $(this).data("val"));
  });

  $('[key="upd-profile"]').on('click', function () {
    scope = $(this).data("scope");

    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'profile/get',
      data: "scope="+$('meta[name=scope]').attr("content")+"&format=JSON",
      type: "GET",
      success: function(response) {
        $('[name="name"]').val(response.data.name),
        $('[name="bio"]').val(response.data.bio),

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

  $('[key="upd-password"]').on('click', function () {
    scope = $(this).data("scope");
  });

  $('[key="add-telegram"]').on('click', function () {
    scope  = $(this).data("scope");
    value   = 'add';
  });

  $('[key="pair-telegram"]').on('click', function () {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'profile/get',
      data: "scope="+scope+"&format=JSON",
      type: "GET",
      success: function(response) {
        $('#tkn-telegram pre').text(response.data.token),

        $('#gnr-telegram').hide(),
        $('#tkn-telegram').show();
      },
    });
  });

  $('[key="del-telegram"]').on('click', function () {
    deleteMethod(true, $(this).data("scope"), $(this).data("val"));
  });

  $('[key="add-social"]').on('click', function () {
    scope  = $(this).data("scope");
    value   = 'add';
  });

  $('[name="sosprov"]').on('change', function () {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'profile/get',
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

  $('[name="safe"]').on('click', function () {
    updateMethod(false, $(this).data("scope"), false);
  });

  $('[key="del-activity"]').on('click', function () {
    Swal.fire({
      title: "Apakah Anda yakin?",
      html: "Seluruh <strong>Aktivitas Login</strong> yang Anda lakukan akan dihapus seluruhnya!",
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
        pushSwalConfirmBeforeDelete(false, 'profile/delete?scope='+$(this).data("scope"));
      } else {
        pushSwalCancel()
      }
    });
  });
}

function initModal() {
  $("#modal-profile").on("hidden.bs.modal", function () {
    resetForm('profile'),
    $('.select2').val('').trigger('change'),
    $('[data-provide="datepicker"]').prop('readonly', true),
    initializeData();
  });

  $("#modal-password").on("hidden.bs.modal", function () {
    resetForm('password'),
    $('[name="password"]').attr('type', 'password'),
    $('#password-show').html('<i class="mdi mdi-eye-outline"></i>'),
    $('[name="repassword"]').attr('type', 'password'),
    $('#repassword-show').html('<i class="mdi mdi-eye-outline"></i>'),
    initializeData();
  });

  $("#modal-telegram").on("hidden.bs.modal", function () {
    $('#tkn-telegram pre').text(''),
    $('#gnr-telegram').show(),
    $('#tkn-telegram').hide();
  });

  $("#modal-social").on("hidden.bs.modal", function () {
    resetForm('social'),
    $('.select2').val('').trigger('change'),
    initializeData();
  });
}

function resetForm(form) {
  $('#form-'+form)[0].reset(),
  $('#form-'+form).removeClass('was-validated');
  $('.invalid-tooltip').remove();
  scope  = '';
  value   = '';
}

function fetchUser() {
  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'profile/get',
    data: "scope="+$('meta[name=scope]').attr("content")+"&format=HTML",
    type: "GET",
    success: function(response) {
      $('.cover').html(response.data.cover),
      $('.avatar').html(response.data.avatar),

      $('[key="uuid"]').text(response.data.uuid),
      $('[key="name"]').html(response.data.name),
      $('[key="username"]').text(response.data.username),
      $('[name="safe"]').prop('checked', stringToBool(response.data.safe)),

      $('[key="bio"]').html(response.data.bio),
      $('[key="address"]').html(response.data.address),

      $('[key="email"]').html(response.data.email),
      $('[key="phone"]').html(response.data.phone);

      $('[key="upd-cover"]').on('click', function () {
        scope  = $(this).data("scope");
        value   = $(this).data("val");
        $('#image').click();
      });

      $('[key="upd-avatar"]').on('click', function () {
        scope  = $(this).data("scope");
        value   = $(this).data("val");
        $('#image').click();
      });
    },
  });
}

function fetchSocial() {
  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'profile/list',
    data: "scope="+$('.social-media').data("scope")+"&format=HTML",
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

function fetchActivity() {
  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'profile/list',
    data: "scope="+$('.activity-list ul').data("scope")+"&format=HTML",
    type: "GET",
    dataType: "HTML",
  }).done(function(response) {
    if (!response) {
      $('.activity-list ul').html('<p class="text-muted text-center mb-0"><i>Tidak ada aktivitas login yang tercatat</i></p>');
    } else {
      $('.activity-list ul').html(response);
    }
  });
}

$("#form-profile").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'profile/store?scope='+scope,
      data: $('#form-profile').serialize(),
      type: "POST",
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          pushToastr(response.type, response.header, response.message.success), requestSuccess('modal'), $('#modal-profile').modal('hide');
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

$("#form-password").validate({
  rules: {
    password: {
      required: true,
    },
    repassword: {
      required: true,
      equalTo: '[name="password"]',
    },
  },
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'profile/store?scope='+scope,
      data: $('#form-password').serialize(),
      type: "POST",
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          pushToastr(response.type, response.header, response.message.success), requestSuccess('modal'), $('#modal-password').modal('hide');
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
      url:  $('meta[name=site-url]').attr("content")+'profile/store?scope='+scope+"&id="+value,
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

function updateMethod(redirect, scope, value) {
  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'profile/update?scope='+scope+'&id='+value,
    data: $('#form-'+value).serialize(),
    type: "POST",
    success: function(response) {
      if (redirect == true) {
        location.reload();
      } else {
        pushToastr(response.type, response.header, response.message.success), initializeData();
      }
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
          url: $('meta[name=site-url]').attr("content")+'profile/delete?scope='+scope+'&id='+id,
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
