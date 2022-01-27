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
var type, district, subdistrict, village;

initialize();

function initialize() {
  initComponents(),
  initButton(),
  initModal();
}

function initializeData() {
  fetchAtlet(),
  fetchSocial();
}

function initComponents() {
  $(".inputtags").tagify();

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
  $('[key="upd-image"]').on('click', function () {
    scope   = $(this).data("scope");
    value   = $(this).data("val");

    $('[name="image"]').trigger('click');
  });

  $('[key="del-image"]').on('click', function () {
    deleteMethod(false, $(this).data("scope"), $(this).data("val"));
  });

  $('[name="image"]').on('change', function () {
    var file = event.target.files[0];
    var img = new Image();

    if (file && file.size >= 2*1024*1024) {
      resetForm('image'),
      pushToastr('warning', '406 Not Acceptable', 'Ukuran maksimal gambar yang diizinkan hanya <strong>2MB</strong>!');
    } else if (file && !file.type.match('image/jp.*|image/png')) {
      resetForm('image'),
      pushToastr('warning', '405 Method Not Allowed', 'Format gambar yang diizinkan hanya <strong>JPG, JPEG, dan PNG</strong>');
    } else if (file && this.files && this.files[0]) {
      var fileReader = new FileReader();
      fileReader.onload = function (e) {
        $.ajax({
          url:  $('meta[name=site-url]').attr("content")+'sport_atlets/update?scope='+scope+'&id='+value,
          data: new FormData($('#form-image')[0]),
          type: "POST",
          contentType: false,
          success: function(response) {
            pushToastr(response.type, response.header, response.message.success), resetForm('image'), initializeData();
          },
        });
      };
      fileReader.readAsDataURL(this.files[0]);
    }
  });

  $('[key="upd-resub"]').on("click", function () {
    scope   = $(this).data("scope");
    value   = 'add';

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
          url:  $('meta[name=site-url]').attr("content")+'sport_atlets/store?scope='+scope+'&params='+$('meta[name=params]').attr("content")+'&id='+value,
          data: "action="+result.value,
          type: "POST",
          success: function(response) {
            setTimeout(function() {
              location.reload();
            }, 2e3)
          },
        });
      } else {
        pushSwalCancel()
      }
    });
  });

  $('[key="upd-verify"]').on("click", function () {
    scope   = $(this).data("scope");
    value   = 'add';

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
          url:  $('meta[name=site-url]').attr("content")+'sport_atlets/store?scope='+scope+'&params='+$('meta[name=params]').attr("content")+'&id='+value,
          data: "action="+result.value,
          type: "POST",
          success: function(response) {
            setTimeout(function() {
              location.reload();
            }, 2e3)
          },
        });
      } else {
        pushSwalCancel()
      }
    });
  });

  $('[key="del-atlet"]').on("click", function () {
    scope   = $('meta[name=scope]').attr("content");
    value   = $('meta[name=params]').attr("content");

    Swal.fire({
      title: "Hapus Data",
      text: "Anda akan menghapus Data ini dari sistem?",
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
          url:  $('meta[name=site-url]').attr("content")+'sport_atlets/delete?scope='+scope+'&params=purge'+'&id='+value,
          type: "DELETE",
          success: function(response) {
            setTimeout(function() {
              window.location.href = response.data.url;
            }, 2e3)
          },
        });
      } else {
        pushSwalCancel()
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

  $('#form-atlet [type="reset"]').on("click", function () {
    resetForm('atlet'),
    $('.select2').val('').trigger('change'),
    $('[data-provide="datepicker"]').val('').datepicker('update'),
    $('[data-provide="datepicker"]').prop('readonly', true),
    initializeData();
  });

  $('[key="add-social"]').on('click', function () {
    scope  = $(this).data("scope");
    value  = 'add';
  });

  $('[name="sosprov"]').on('change', function () {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'sport_atlets/get',
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
}

function initModal() {
  $("#modal-social").on("hidden.bs.modal", function () {
    resetForm('social'),
    $('.select2').val('').trigger('change'),
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

function fetchAtlet() {
  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'sport_atlets/get',
    data: "scope="+$('meta[name=scope]').attr("content")+"&format=HTML&id="+$('meta[name=params]').attr("content"),
    type: "GET",
    success: function(response) {
      $('[key="avatar"]').attr('src', response.data.avatar),
      $('[key="atletname"]').text(response.data.name),
      $('[key="code"]').text(response.data.code),
      $('[key="bio"]').html(response.data.bio),
      $('[key="cabor"]').html(response.data.cabor),
      $('[key="type"]').html(response.data.type),

      $('[key="address"]').text(response.data.address),
      $('[key="age"]').text(response.data.age),
      $('[key="birthday"]').text(response.data.birthday),
      $('[key="religion"]').text(response.data.religion),
      $('[key="gender"]').text(response.data.gender),

      $('[key="email"]').html(response.data.email),
      $('[key="phone"]').html(response.data.phone);
    },
  });

  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'sport_atlets/get',
    data: "scope="+$('meta[name=scope]').attr("content")+"&format=JSON&id="+$('meta[name=params]').attr("content"),
    type: "GET",
    success: function(response) {
      $('[name="cabor"]').val(response.data.cabor_id).trigger('change'),
      $('[name="fullname"]').val(response.data.sport_atlet_name),
      $('[name="level"]').val(response.data.sport_atlet_level).trigger('change'),
      $('[name="bio"]').val(response.data.sport_atlet_bio),
      $('[name="explanation"]').val(response.data.sport_atlet_explanation),
      $('[name="atlet_ownership"]').data('tagify').addTags(response.data.sport_atlet_ownership),

      $('[name="address"]').val(response.data.sport_atlet_address),
      $('[name="province"]').val(response.data.sport_atlet_province_id).trigger('change'),
      $('[name="zipcode"]').val(response.data.sport_atlet_zip_code),

      $('[name="pob"]').val(response.data.sport_atlet_pob),
      $('[name="dob"]').val(parseDatepicker(response.data.sport_atlet_dob)).datepicker('update'),
      $('[name="gender"]').val(response.data.sport_atlet_gender).trigger('change'),
      $('[name="religion"]').val(response.data.sport_atlet_religion).trigger('change'),

      $('[name="email"]').val(response.data.sport_atlet_email),
      $('[name="phone"]').val(response.data.sport_atlet_phone);
      
      district    = response.data.sport_atlet_district_id;
      subdistrict = response.data.sport_atlet_sub_district_id;
      village     = response.data.sport_atlet_village_id;
    },
  });
}

function fetchSocial() {
  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'sport_atlets/list',
    data: "scope="+$('.social-media').data("scope")+"&format=HTML&id="+$('meta[name=params]').attr("content"),
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

$("#form-atlet").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'sport_atlets/store?scope='+$('[key="upd-atlet"]').data("scope")+'&id='+$('meta[name=params]').attr("content"),
      data: $("#form-atlet").serialize(),
      type: "POST",
      beforeSend: function() {
        requestBefore('submit');
      },
      success: function(response) {
        setTimeout(function() {
          location.reload();
        }, 2e3)
      },
      error: function() {
        setTimeout(function() {
          requestSuccess('submit');
        }, 2e3)
      },
    });
  }
});

$("#form-social").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'sport_atlets/store?scope='+scope+"&params="+$('meta[name=params]').attr("content")+"&id="+value,
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

$("#form-attachment").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'sport_atlets/store?scope='+scope+'&id='+value,
      data: new FormData($('#form-attachment')[0]),
      type: "POST",
      contentType: false,
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          $('#modal-attachment').modal('hide'), location.reload();
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
          url: $('meta[name=site-url]').attr("content")+'sport_atlets/delete?scope='+scope+'&id='+id,
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
