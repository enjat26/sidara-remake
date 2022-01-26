/*
Application Name: SIPAS - CV. Ionix Eternal Studio
Author: Uben Wisnu
Website: https://ionixeternal.co.id/
Contact: support@ionixeternal.co.id
File: JS File
*/

(function() {
  'use strict';

  $("#form-forgot").validate({
    rules: {
      email: {
        required: true,
      },
    },
    submitHandler: function(form) {
      $.ajax({
        url:  $('meta[name=site-url]').attr("content")+'forgot',
        data: $('#form-forgot').serialize(),
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
