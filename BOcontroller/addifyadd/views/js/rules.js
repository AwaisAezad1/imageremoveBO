$(document).ready(function () {
  // initially hide all elements associated with switches
  $(".table-bordered").parent().parent().parent().parent().hide();
  $("#restricted_product_input")
    .parent()
    .parent()
    .parent()
    .parent()
    .parent()
    .hide();
  $(".tree-panel-heading-controls").parent().parent().parent().hide();

  $("#product_active_on").parent().parent().parent().hide();
  $("#product_active_off").parent().parent().parent().hide();
  $("#featurename_active_on").parent().parent().parent().hide();
  $("#featurename_active_off").parent().parent().parent().hide();
  $("#featurevalue_active_on").parent().parent().parent().hide();
  $("#featurevalue_active_off").parent().parent().parent().hide();
  $("#group_active_on").parent().parent().parent().hide();
  $("#group_active_off").parent().parent().parent().hide();
  $("#category_active_on").parent().parent().parent().hide();
  $("#category_active_off").parent().parent().parent().hide();

  // Show/hide elements when switches are clicked
  $("#active_product_page_on, #active_product_page_off").click(function () {
    if ($("#active_product_page_on,#active_quickview_page_on").prop("checked")) {
      $("#product_active_on").parent().parent().parent().show();
      $("#product_active_off").parent().parent().parent().show();
      $("#featurename_active_on").parent().parent().parent().show();
      $("#featurename_active_off").parent().parent().parent().show();
      $("#featurevalue_active_on").parent().parent().parent().show();
      $("#featurevalue_active_off").parent().parent().parent().show();
      $("#group_active_on").parent().parent().parent().show();
      $("#group_active_off").parent().parent().parent().show();
      $("#category_active_on").parent().parent().parent().show();
      $("#category_active_off").parent().parent().parent().show();
    } else {
      $("#product_active_on").parent().parent().parent().hide();
      $("#product_active_off").parent().parent().parent().hide();
      $("#featurename_active_on").parent().parent().parent().hide();
      $("#featurename_active_off").parent().parent().parent().hide();
      $("#featurevalue_active_on").parent().parent().parent().hide();
      $("#featurevalue_active_off").parent().parent().parent().hide();
      $("#group_active_on").parent().parent().parent().hide();
      $("#group_active_off").parent().parent().parent().hide();
      $("#category_active_on").parent().parent().parent().hide();
      $("#category_active_off").parent().parent().parent().hide();
    }
  });
  $("#active_quickview_page_on, #active_quickview_page_off ").click(function () {
    if ($("#active_quickview_page_on").prop("checked")) {
      $("#featurename_active_on").parent().parent().parent().show();
      $("#featurename_active_off").parent().parent().parent().show();
      $("#featurevalue_active_on").parent().parent().parent().show();
      $("#featurevalue_active_off").parent().parent().parent().show();
      $("#product_active_on").parent().parent().parent().show();
      $("#product_active_off").parent().parent().parent().show();
      $("#group_active_on").parent().parent().parent().show();
      $("#group_active_off").parent().parent().parent().show();
      $("#category_active_on").parent().parent().parent().show();
      $("#category_active_off").parent().parent().parent().show();
    } else {
      $("#featurename_active_on").parent().parent().parent().hide();
      $("#featurename_active_off").parent().parent().parent().hide();
      $("#featurevalue_active_on").parent().parent().parent().hide();
      $("#featurevalue_active_off").parent().parent().parent().hide();
      $("#product_active_on").parent().parent().parent().hide();
      $("#product_active_off").parent().parent().parent().hide();
      $("#group_active_on").parent().parent().parent().hide();
      $("#group_active_off").parent().parent().parent().hide();
      $("#category_active_on").parent().parent().parent().hide();
      $("#category_active_off").parent().parent().parent().hide();
    }
  });
  $("#group_active_on, #group_active_off").click(function () {
    if ($("#group_active_on").prop("checked")) {
      $(".table-bordered").parent().parent().parent().parent().show();
    } else {
      $(".table-bordered").parent().parent().parent().parent().hide();
    }
  });

  $("#product_active_on, #product_active_off").click(function () {
    if ($("#product_active_on").prop("checked")) {
      $("#restricted_product_input")
        .parent()
        .parent()
        .parent()
        .parent()
        .parent()
        .show();
    } else {
      $("#restricted_product_input")
        .parent()
        .parent()
        .parent()
        .parent()
        .parent()
        .hide();
    }
  });

  $("#category_active_on, #category_active_off").click(function () {
    if ($("#category_active_on").prop("checked")) {
      $(".tree-panel-heading-controls").parent().parent().parent().show();
    } else {
      $(".tree-panel-heading-controls").parent().parent().parent().hide();
    }
  });
});
