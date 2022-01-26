/*
Author: Ionix Eternal Studio
Website: https://ionixeternal.co.id/
Contact: support@ionixeternal.co.id
File: Datatables Js File
*/

"use strict";

$(document).ready(function () {
  initializeData();
});

var scope, value;

initialize();

function initialize() {
  initComponents(), initButton(), initModal();
}

function initializeData() {
  fetchNavigation();
}

function initComponents() {
  $(".select2").select2({
    placeholder: function () {
      $(this).data("placeholder");
    },
  });
}

function initButton() {
  $('[key="rfs-navigation"]').on("click", function () {
    resetForm("navigations"),
    initializeData();
  });

  $('[key="add-navigation"]').on("click", function () {
    value = "add";

    $("#modal-navigations .modal-title").text("Tambah Navigasi Baru"),
    $('#modal-navigations [type="submit"]').text("Tambah");
  });
}

function initModal() {
  $("#modal-navigations").on("hidden.bs.modal", function () {
    resetForm("navigations"),
    $('.select2').val('').trigger('change'),
    initializeData();
  });
}

function resetForm(form) {
  $("#form-" + form)[0].reset(),
  $("#form-" + form).removeClass("was-validated"),
  $(".invalid-tooltip").remove(),
  scope = '';
  value = '';
}

function fetchNavigation() {
  var dataTable = $("#dt_navigations").DataTable({
    oLanguage: {
      sProcessing: "Sedang memuat...",
      sSearch: "Cari:",
      oPaginate: {
        sLast: ">>",
        sNext: ">",
        sPrevious: "<",
        sFirst: "<<",
      },
      sZeroRecords: "Tidak dapat menemukan data yang cocok",
    },
    destroy: true,
    processing: true,
    serverSide: true,
    responsive: true,
    order: [],
    ajax: {
      url:
        $("meta[name=site-url]").attr("content") +
        "navigations/list?scope=" +
        $("meta[name=scope]").attr("content") +
        "&format=DT",
      type: "POST",
      processData: true,
    },
    columnDefs: [
      {
        targets: [0, 4, 5],
        orderable: false,
      },
    ],
    lengthMenu: [
      [10, 25, 50, 100, -1],
      ["10", "25", "50", "100", "All"],
    ],
  });

  $(".dataTables_length select").addClass("form-select form-select-sm"),
    $("#dt_navigations_paginate").addClass("pagination-rounded");
}

$("#form-navigations").validate({
  submitHandler: function (form) {
    $.ajax({
      url: $("meta[name=site-url]").attr("content")+"navigations/store?scope="+$("meta[name=scope]").attr("content")+"&id=" +value,
      data: $("#form-navigations").serialize(),
      type: "POST",
      beforeSend: function () {
        requestBefore("modal");
      },
      success: function (response) {
        setTimeout(function () {
          pushToastr(response.type, response.header, response.message.success), requestSuccess("modal"), $("#modal-navigations").modal("hide");
        }, 2e3);
      },
      error: function () {
        setTimeout(function () {
          requestSuccess("modal");
        }, 2e3);
      },
    });
  },
});

function putNavigation(id) {
  value = id;

  $.ajax({
    url: $("meta[name=site-url]").attr("content")+"navigations/get",
    data: "scope="+$("meta[name=scope]").attr("content")+"&format=JSON&id=" +id,
    type: "GET",
    success: function (response) {
        $('[name="group"]').val(response.data.group_id).trigger("change"),
        $('[name="parent"]').val(response.data.menu_parent).trigger("change"),

        $('[name="link"]').val(response.data.menu_link),
        $('[name="title"]').val(response.data.menu_title),
        $('[name="icon"]').val(response.data.menu_icon),
        
        $('[name="order"]').val(response.data.menu_order),
        $('[name="previlege"]').val(response.data.menu_previlege).trigger("change"),

        $("#modal-navigations .modal-title").text("Ubah Informasi Navigasi"),
        $('#modal-navigations [type="submit"]').text("Simpan");
    },
  });
}

function deleteMethod(redirect, scope, id) {
  Swal.fire({
    title: "Apakah Anda yakin?",
    text: "Anda tidak akan dapat mengembalikan ini!",
    icon: "question",
    showCancelButton: true,
    confirmButtonText: "Hapus",
    cancelButtonText: "Batal",
    customClass: {
      confirmButton: "btn btn-success me-2",
      cancelButton: "btn btn-danger",
    },
    buttonsStyling: false,
  }).then((result) => {
    if (result.value) {
      pushSwalConfirmBeforeDelete(redirect, "navigations/delete?scope="+scope+"&id="+id);
    } else {
      pushSwalCancel();
    }
  });
}
