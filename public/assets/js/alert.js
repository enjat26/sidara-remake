/*
Company: Ionix Eternal Studio
Author: Uben Wisnu
Website: https://ionixeternal.co.id/
Contact: support@ionixeternal.co.id
File: JS File
*/

'use strict';

function pushToastr(type, header, message) {
  if (notificationTone == true) {
    new Audio($('meta[property="og:media"]').attr("content")+"audio/"+type+".mp3").play();
  }

  if (type == 'info') {
    iziToast.info({
      title: header,
      message: message,
      position: 'topRight'
    });
  } else if (type == 'success') {
    iziToast.success({
      title: header,
      message: message,
      position: 'topRight'
    });
  } else if (type == 'warning') {
    iziToast.warning({
      title: header,
      message: message,
      position: 'topRight'
    });
  } else if (type == 'error') {
    iziToast.error({
      title: header,
      message: message,
      position: 'topRight'
    });
  }
}

function pushNotification(title, message, url) {
  var notification = new Notification(title, {
    icon: $('meta[property="og:image"]').attr("content"),
    body: message,
  });

  notification.onclick = function(event) {
    event.preventDefault();
    window.open(url, '_blank');
  }
}

// ========================================================== SWEET ALERT 2

function pushSwal(type, header, message) {
  if (notificationTone == true) {
    new Audio($('meta[property="og:media"]').attr("content")+"audio/"+type+".mp3").play();
  }

  Swal.fire({
    title: header,
    html: message,
    icon: type,
    confirmButtonText: "Tutup",
    customClass: {
      confirmButton: 'btn btn-primary',
    },
    buttonsStyling: false
  });
}

function pushSwalCancel() {
  if (notificationTone == true) {
    new Audio($('meta[property="og:media"]').attr("content")+"audio/cancel.mp3").play();
  }

  Swal.fire({
      title: "Membatalkan!",
      html: "Aksi tersebut dibatalkan!",
      icon: "error",
      confirmButtonText: "Tutup",
      customClass: {
        confirmButton: 'btn btn-primary',
      },
      buttonsStyling: false
  });
}

function pushSwalRequired(message) {
  if (notificationTone == true) {
    new Audio($('meta[property="og:media"]').attr("content")+"audio/required.mp3").play();
  }

  const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  });
  Toast.fire({
    icon: "warning",
    title: "411 Length Required",
    html: message,
  });
}

function pushSwalConfirmBeforeDelete(redirect, url) {
  if (notificationTone == true) {
    new Audio($('meta[property="og:media"]').attr("content")+"audio/warning.mp3").play();
  }

  Swal.fire({
      title: "Memastikan!",
      html: "Jika Anda benar-benar yakin ingin menghapus ini, kemungkinan data lain yang terkait dengannya juga akan dihapus!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonText: "Ya, lanjutkan!",
      cancelButtonText: "Tidak, Batalkan!",
      customClass: {
        confirmButton: 'btn btn-success me-2',
        cancelButton: 'btn btn-danger'
      },
      buttonsStyling: false
  }).then((result) => {
      if(result.value) {
          $.ajax({
              url: $('meta[name=site-url]').attr("content")+url,
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

function pushSwalError() {
  if (notificationTone == true) {
    new Audio($('meta[property="og:media"]').attr("content")+"audio/error.mp3").play();
  }

  Swal.fire({
    title: "500 Internal Server Error",
    html: "Ooppss...! Sepertinya terjadi kesalahan...",
    icon: "error",
    confirmButtonText: "Tutup",
    customClass: {
      confirmButton: 'btn btn-primary',
    },
    buttonsStyling: false
  });
}
