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
  fetchCabor();
}

function initComponents() {
  if ($("#content").length > 0) {
      tinyMCE.init({
          selector: "textarea#content",
          height: 800,
          theme : "silver",
          plugins: [
              "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
              "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
              "save table directionality emoticons template paste"
          ],
          toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
          theme_advanced_buttons1 : "save,cancel,|,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect,|,forecolor,backcolor",
          theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,cleanup,help,code,tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,advhr",
          theme_advanced_toolbar_location : "top",
          theme_advanced_toolbar_align : "left",
          theme_advanced_statusbar_location : "bottom",
          theme_advanced_resizing : true,
          style_formats: [
              {title: 'Bold text', inline: 'b'},
              {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
              {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
              {title: 'Example 1', inline: 'span', classes: 'example1'},
              {title: 'Example 2', inline: 'span', classes: 'example2'},
              {title: 'Table styles'},
              {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
          ],
          content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
          entity_encoding: 'raw',
          entities: '160,nbsp,38,amp,60,lt,62,gt',
      });
  }

  $('.select2').select2({
    placeholder: function() {
      $(this).data('placeholder');
    },
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
    } else if (file && !file.type.match('image/png')) {
      resetForm('image'),
      pushToastr('warning', '405 Method Not Allowed', 'Format gambar yang diizinkan hanya <strong>PNG</strong>');
    } else if (file && this.files && this.files[0]) {
      var fileReader = new FileReader();
      fileReader.onload = function (e) {
        $.ajax({
          url:  $('meta[name=site-url]').attr("content")+'sport_cabors/update?scope='+scope+'&id='+value,
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

  $('#form-sport_cabor [type="reset"]').on("click", function () {
    resetForm('cabor'),
    initializeData();
  });

  $('[key="add-type"]').on("click", function () {
    scope   = $(this).data("scope");
    value   = 'add';

    $('#modal-type .modal-title').text('Tambah Jenis Cabang Olahraga'),
    $('#modal-type [type="submit"]').text('Tambah');
  });

  $('[key="del-type"]').on('click', function () {
    deleteMethod(true, $(this).data("scope"), $(this).data("val"));
  });
}

function initModal() {
  $("#modal-type").on("hidden.bs.modal", function () {
    resetForm('type'),
    $('.select2').val('').trigger('change');
  });
}

function resetForm(form) {
  $('#form-'+form)[0].reset(),
  $('#form-'+form).removeClass('was-validated'),
  $('.invalid-tooltip').remove(),
  scope   = '';
  value   = '';
}

function fetchCabor() {
  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'sport_cabors/get',
    data: "scope="+$('meta[name=scope]').attr("content")+"&format=HTML&id="+$('meta[name=params]').attr("content"),
    type: "GET",
    success: function(response) {
      $('[key="avatar"]').attr('src', response.data.avatar),

      $('[key="caborname"]').text(response.data.name),
      $('[key="code"]').text(response.data.code),
      $('[key="description"]').html(response.data.description),

      $('[key="content"]').html(response.data.content);
    },
  });

  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'sport_cabors/get',
    data: "scope="+$('meta[name=scope]').attr("content")+"&format=JSON&id="+$('meta[name=params]').attr("content"),
    type: "GET",
    success: function(response) {
      $('[name="code"]').val(response.data.sport_cabor_code),
      $('[name="name"]').val(response.data.sport_cabor_name),
      $('[name="description"]').val(response.data.sport_cabor_description);

      if (response.data.sport_cabor_content) {
        $('[name="content"]').html(response.data.sport_cabor_content);
      }
    },
  });
}

$("#form-cabor").validate({
  submitHandler: function(form) {
    var formData = new FormData($('#form-cabor')[0]);
    formData.append('content', tinyMCE.get('content').getContent());

    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'sport_cabors/store?scope='+$('meta[name=scope]').attr("content")+'&id='+$('meta[name=params]').attr("content"),
      data: formData,
      type: "POST",
      contentType: false,
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

$("#form-type").validate({
  submitHandler: function(form) {
    $.ajax({
      url:  $('meta[name=site-url]').attr("content")+'sport_cabors/store?scope='+scope+"&params="+$('meta[name=params]').attr("content")+"&id="+value,
      data: $('#form-type').serialize(),
      type: "POST",
      beforeSend: function() {
        requestBefore('modal');
      },
      success: function(response) {
        setTimeout(function() {
          $('#modal-type').modal('hide'), location.reload();
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
      pushSwalConfirmBeforeDelete(redirect, 'sport_cabors/delete?scope='+scope+'&id='+id);
    } else {
      pushSwalCancel()
    }
  });
}
