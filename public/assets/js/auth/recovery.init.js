/*
Application Name: SIPAS - CV. Ionix Eternal Studio
Author: Uben Wisnu
Website: https://ionixeternal.co.id/
Contact: support@ionixeternal.co.id
File: JS File
*/

(function() {
  'use strict';

  $("#form-recovery").validate({
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
        url:  $('meta[name=site-url]').attr("content")+'recovery',
        data: $('#form-recovery').serialize()+"&token="+$('meta[name=token]').attr("content"),
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
