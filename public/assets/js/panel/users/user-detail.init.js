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
var workunit, province, district, subdistrict, village;

initialize();

function initialize() {
  initComponents(),
  initButton(),
  initModal();
}

function initializeData() {
  fetchUser();
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

  $('#form-profile [type="reset"]').on("click", function () {
    resetForm('profile'),
    $('.select2').val('').trigger('change'),
    $('[data-provide="datepicker"]').prop('readonly', true),
    initializeData();
  });

  $('#form-password [type="reset"]').on("click", function () {
    resetForm('password'),
    initializeData();
  });
}

function initModal() {

}

function resetForm(form) {
  $('#form-'+form)[0].reset(),
  $('#form-'+form).removeClass('was-validated'),
  $('.invalid-tooltip').remove(),
  scope   = '';
  value   = '';
}

function fetchUser() {
  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'users/get',
    data: "scope="+$('meta[name=scope]').attr("content")+"&format=HTML&id="+$('meta[name=params]').attr("content"),
    type: "GET",
    success: function(response) {
      $('[key="cover"]').html(response.data.cover),
      $('[key="avatar"]').html(response.data.avatar),

      $('[key="uuid"]').text(response.data.uuid),
      $('[key="username"]').text(response.data.username),

      $('[key="fullname"]').text(response.data.name),
      $('[key="active"]').html(response.data.active),

      $('[key="bio"]').html(response.data.bio),
      $('[key="address"]').html(response.data.address),

      $('[key="email"]').html(response.data.email),
      $('[key="phone"]').html(response.data.phone);
    },
  });

  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'users/get',
    data: "scope="+$('meta[name=scope]').attr("content")+"&format=JSON&id="+$('meta[name=params]').attr("content"),
    type: "GET",
    success: function(response) {
      $('[name="role"]').val(response.data.role_code).trigger('change'),

      $('[name="branch"]').val(response.data.branch_id).trigger('change'),

      $('[name="fullname"]').val(response.data.name),
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
}

$("#form-profile").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'users/store?scope='+$('meta[name=scope]').attr("content")+'&id='+$('meta[name=params]').attr("content"),
      data: $('#form-profile').serialize(),
      type: "POST",
      beforeSend: function() {
        requestBefore('submit');
      },
      success: function(response) {
        setTimeout(function() {
          pushToastr(response.type, response.header, response.message.success), requestSuccess('submit'), $('#form-profile [type="reset"]').click();
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
      url:  $('meta[name=site-url]').attr("content")+'users/store?scope='+$('[key="upd-password"]').data("scope")+'&id='+$('meta[name=params]').attr("content"),
      data: $('#form-password').serialize(),
      type: "POST",
      beforeSend: function() {
        requestBefore('submit');
      },
      success: function(response) {
        setTimeout(function() {
          pushToastr(response.type, response.header, response.message.success), requestSuccess('submit'), $('#form-password [type="reset"]').click();
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
