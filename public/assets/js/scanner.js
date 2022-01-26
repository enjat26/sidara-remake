/*
Author: Ionix Eternal Studio
Website: https://ionixeternal.co.id/
Contact: support@ionixeternal.co.id
File: Datatables Js File
*/

'use strict'

initScannerElement();

function initScannerElement() {
  $('[key="qr-scan"]').on("click", function () {
    // $('[key="start-scanner"]').trigger('click');
    $('#modal-scanner .modal-title').text('Pindai Kode QR');
  });

  $("#modal-scanner").on("hidden.bs.modal", function () {
    // $('[key="stop-scanner"]').trigger('click');
  });
}

function onScanFailure(error) {
  // handle scan failure, usually better to ignore and keep scanning.
  // for example:
  // console.warn(`Code scan error = ${error}`);
}

let html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", {
  fps: 10,
  qrbox: 250
}, /* verbose= */ false);

html5QrcodeScanner.render(onScanSuccess, onScanFailure);

$('#qr-reader__dashboard_section_swaplink').css("display", "none");
