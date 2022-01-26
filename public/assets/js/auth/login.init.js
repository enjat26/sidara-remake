/*
Company: Ionix Eternal Studio
Author: Uben Wisnu
Website: https://ionixeternal.co.id/
Contact: support@ionixeternal.co.id
File: JS File
*/

(function() {
  'use strict';

  initialize();

  function initialize() {
    initComponents();
  }

  function initComponents() {
    $('.select2').select2({
      placeholder: function() {
        $(this).data('placeholder');
      },
    });
  }

  $("#form-login").validate({
    rules: {
      identity: {
        required: true,
      },
      password: {
        required: true,
      },
    },
    submitHandler: function(form) {
      $.ajax({
        url:  $('meta[name=site-url]').attr("content")+'login',
        data: $('#form-login').serialize(),
        type: "POST",
        beforeSend: function() {
          requestBefore('submit');
        },
        success: function(response) {
          setTimeout(function() {
            location.href = response.data.url;
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

})();
