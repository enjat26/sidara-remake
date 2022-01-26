/*
Company: Ionix Eternal Studio
Author: Uben Wisnu
Website: https://ionixeternal.co.id/
Contact: support@ionixeternal.co.id
File: JS File
*/

(function() {
  'use strict';

  $("#form-register").validate({
    rules: {
      name: {
        required: true,
      },
      username: {
        required: true,
      },
      email: {
        required: true,
        email: true,
      },
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
        url:  $('meta[name=site-url]').attr("content")+'register',
        data: $('#form-register').serialize(),
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
