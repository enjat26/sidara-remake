/*
Company: Ionix Eternal Studio
Author: Uben Wisnu
Website: https://ionixeternal.co.id/
Contact: support@ionixeternal.co.id
File: JS File
*/

'use strict';

var language = localStorage.getItem('language');
// Default Language
var default_lang = $('html').attr('lang');

window.addEventListener('load', function() {
  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.getElementsByClassName('needs-validation');
  // Loop over them and prevent submission
  var validation = Array.prototype.filter.call(forms, function(form) {
    form.addEventListener('submit', function(event) {
      if (form.checkValidity() === false) {
        event.preventDefault(),
        event.stopPropagation(),
        pushSwalRequired('Sepertinya ada bidang yang tidak sesuai atau masih kosong'),
        form.classList.add('was-validated');
      } else {
        form.classList.remove('was-validated');
      }
    }, false);
  });
}, false);

function setLanguage(lang) {
    if(document.getElementById("header-lang-img")) {
        if(lang=='id') {
            document.getElementById("header-lang-img").src = $('#lang-id').attr("data-image");
        } else if(lang=='en') {
            document.getElementById("header-lang-img").src = $('#lang-en').attr("data-image");
        }
    localStorage.setItem('language', lang);
    language = localStorage.getItem('language');
    getLanguage();
    }
}

// Multi language setting
function getLanguage() {
    (language == null) ? setLanguage(default_lang) : false;
    $.getJSON($('meta[name=site-url]').attr("content")+'assets/lang/' + language + '.json', function(lang) {
        $('html').attr('lang', language);
        $.each(lang, function (index, val) {
            (index === 'head') ? $(document).attr("title", val['title']) : false;
            $("[key='"+index+"']").text(val);
            $("[key='"+index+"']").attr("placeholder", val);
        });
    });
}

function initLanguage() {
    moment.locale('id');

    // Auto Loader
    if (language != "null" && language !== default_lang)
        setLanguage(language);
    $('.language').on('click', function (e) {
        setLanguage($(this).attr('data-lang'));
    });
}

function isNumberKey(evt){
  var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode != 46 && charCode != 45 && charCode > 31
    && (charCode < 48 || charCode > 57))
    return false;
  return true;
}

function ucwords(str, force) {
    str=force ? str.toLowerCase() : str;
    return str.replace(/(\b)([a-zA-Z])/,
    function(firstLetter){
      return firstLetter.toUpperCase();
    });
}

function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return typeof sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
    return false;
};

function stringToBool(value) {
  if (value == 1) {
    return true;
  }

  return false;
}

function boolToString(value) {
  if (value == 1) {
    return 'true';
  }

  return 'false';
}

function parseDatepicker(value) {
  if (value) {
    return moment(value).format('L');
  }

  return '';
}

function requestBefore(target) {
  $('.form-control').prop('readonly', true);

  if (target == 'submit') {
    $('form [type="submit"]').prop('disabled', true);
    $('form [type="submit"]').append('<span id="spinner-loading" class="spinner-border spinner-border-sm ms-1" role="status" aria-hidden="true"></span>');
  } else if (target == 'modal') {
    $('.modal-footer [type="submit"]').prop('disabled', true);
    $('.modal-footer [type="submit"]').append('<span id="spinner-loading" class="spinner-border spinner-border-sm ms-1" role="status" aria-hidden="true"></span>');
  }
}

function requestSuccess(target) {
  $('.form-control').prop('readonly', false);

  if (target == 'submit') {
    $('form [type="submit"]').prop('disabled', false);
    $('button #spinner-loading').remove();
  } else if (target == 'modal') {
    $('.modal-footer [type="submit"]').prop('disabled', false);
    $('button #spinner-loading').remove();
  }
}

function initComponents() {
  // show password input value
  $("#password-show").on('click', function() {
      if($(this).siblings('input').length > 0) {
          $(this).siblings('input').attr('type') == "password" ? $(this).siblings('input').attr('type', 'text') : $(this).siblings('input').attr('type', 'password');
          $(this).siblings('input').attr('type') == "password" ? $('#password-show').html('<i class="mdi mdi-eye-outline"></i>') : $('#password-show').html('<i class="mdi mdi-eye-off-outline"></i>');
      }
  });

  // show password confirmation input value
  $("#repassword-show").on('click', function() {
      if($(this).siblings('input').length > 0) {
          $(this).siblings('input').attr('type') == "password" ? $(this).siblings('input').attr('type', 'text') : $(this).siblings('input').attr('type', 'password');
          $(this).siblings('input').attr('type') == "password" ? $('#repassword-show').html('<i class="mdi mdi-eye-outline"></i>') : $('#repassword-show').html('<i class="mdi mdi-eye-off-outline"></i>');
      }
  });

  $(function () {
    $('[data-provide="maxlength"]').maxlength({
      alwaysShow: !0,
      warningClass: "badge badge-soft-success",
      limitReachedClass: "badge badge-soft-danger",
      separator: " dari ",
      preText: "Anda mengetik ",
      postText: " karakter tersedia.",
      validate: !0
    });

    $('.image-popup-no-margins').magnificPopup({
  		type: 'image',
  		closeOnContentClick: true,
  		closeBtnInside: false,
  		fixedContentPos: true,
  		mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
  		image: {
  			verticalFit: true
  		},
  		zoom: {
  			enabled: true,
  			duration: 300 // don't foget to change the duration also in CSS
  		}
  	});
  })
}

function init() {
    initLanguage();
    initComponents();
}

init();
