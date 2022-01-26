/*
Author: Ionix Eternal Studio
Website: https://ionixeternal.co.id/
Contact: support@ionixeternal.co.id
File: Datatables Js File
*/

'use strict'

// Enable pusher logging - don't include this in production
Pusher.logToConsole = false;

var pusher = new Pusher(pusherAppKey, {
  cluster: pusherAppCluster
});

var channel = pusher.subscribe($('meta[name=application-name]').attr("content"));
channel.bind('notification', function(data) {
  fetchNotification();
});

$(document).ready(function() {
  Notification.requestPermission();

  if (Notification.permission === "granted") {
    $('#ask-notification').hide();
    $('.notification-container').show();
  } else {
    $('#ask-notification').show();
    $('.notification-container').hide();
  }

  if (Notification.permission === "granted") {
    fetchNotification();
  }
});

initAdditionalComponents();

function initAdditionalComponents() {
  $('[key="ask-notification"]').on('click', function () {
    if (!("Notification" in window)) {
      pushLolibox('error', 'This browser does not support desktop notification')
    } else if (Notification.permission === "granted") {
      location.reload();
    } else if (Notification.permission !== "denied") {
      sessionStorage.removeItem("is_notification");

      Notification.requestPermission().then(function (permission) {
        if (permission === "granted") {
          location.reload();
        }
      });
    }
  });

  $('[key="mark-notification"]').on('click', function () {
    updateNotification(false, $("#notifications").data("scope"), $(this).data("val"));
  });

  $('[key="del-notification"]').on('click', function () {
    deleteNotification(true, $("#notifications").data("scope"), $(this).data("val"));
  });

  $('button[key="upd-notification"]').on('click', function () {
    deleteNotification(false, $("#notifications").data("scope"), $(this).data("val"));
  });

  $('button[key="del-notification"]').on('click', function () {
    deleteNotification(true, $("#notifications").data("scope"), $(this).data("val"));
  });
}

function fetchNotification() {
  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'notifications/count',
    data: "scope="+$("#notifications").data("scope")+"&format=JSON",
    type: "GET",
    success: function(response) {
      if (response.data > 0) {
        $('#page-header-notifications-dropdown span').text(response.data), $('#page-header-notifications-dropdown span').show();
      } else {
        $('#page-header-notifications-dropdown span').hide();
      }
    },
  });

  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'notifications/get',
    data: "scope="+$("#notifications").data("scope")+"&format=Javascript",
    type: "GET",
    success: function(response) {
      if (Notification.permission === "granted" && response.data) {
        pushNotification(response.data.title, response.data.message, response.data.url);
        pushToastr('info', response.data.title, response.data.message);
      }
    },
  });

  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'notifications/list',
    data: "scope="+$("#notifications").data("scope")+"&format=HTML",
    type: "GET",
    contentType: false,
    dataType: "HTML",
  }).done(function(response){
      if (response) {
        $('.notifications-list').html(''), $('.notifications-list').append(response);
      } else {
        $('.notifications-list').html(''), $('.notifications-list').append('<div class="text-center my-4"><i>Tidak ada notifikasi</i></div>');
      }
  });
}

function updateNotification(redirect, params, id) {
  $.ajax({
    url:  $('meta[name=site-url]').attr("content")+'notifications/update?scope='+params+'&id='+id,
    type: "POST",
    success: function(response) {
      if (redirect == true) {
        location.reload();
      } else {
        pushToastr(response.type, response.header, response.message.success);
      }
    },
    error: function() {
      setTimeout(function() {
        initializeData();
      }, 2e3)
    },
  });
}

function deleteNotification(redirect, params, id) {
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
          url: $('meta[name=site-url]').attr("content")+'notifications/delete?scope='+params+'&id='+id,
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
