$(function () {
  "use strict";

  /* scrollar - with safety checks */
  if (document.querySelector(".notify-list")) {
    new PerfectScrollbar(".notify-list");
  }

  if (document.querySelector(".search-content")) {
    new PerfectScrollbar(".search-content");
  }

  /* toggle button */
  $(".btn-toggle").click(function () {
    $("body").hasClass("toggled") ? ($("body").removeClass("toggled"), $(".sidebar-wrapper").unbind("hover")) : ($("body").addClass("toggled"), $(".sidebar-wrapper").hover(function () {
      $("body").addClass("sidebar-hovered")
    }, function () {
      $("body").removeClass("sidebar-hovered")
    }))
  })

  /* menu */
  $(function () {
    if (document.querySelector('#sidenav')) {
      $('#sidenav').metisMenu();
    }
  });

  $(".sidebar-close").on("click", function () {
    $("body").removeClass("toggled")
  })

  /* dark mode button */
  $(".dark-mode i").click(function () {
    $(this).text(function (i, v) {
      return v === 'dark_mode' ? 'light_mode' : 'dark_mode'
    })
  });

  $(".dark-mode").click(function () {
    $("html").attr("data-bs-theme", function (i, v) {
      return v === 'dark' ? 'light' : 'dark';
    })
  })

  /* sticky header */
  $(document).ready(function () {
    $(window).on("scroll", function () {
      if ($(this).scrollTop() > 60) {
        $('.top-header .navbar').addClass('sticky-header');
      } else {
        $('.top-header .navbar').removeClass('sticky-header');
      }
    });
  });

  /* email */
  $(".email-toggle-btn").on("click", function() {
    $(".email-wrapper").toggleClass("email-toggled")
  }), $(".email-toggle-btn-mobile").on("click", function() {
    $(".email-wrapper").removeClass("email-toggled")
  }), $(".compose-mail-btn").on("click", function() {
    $(".compose-mail-popup").show()
  }), $(".compose-mail-close").on("click", function() {
    $(".compose-mail-popup").hide()
  }), 

  /* chat */
  $(".chat-toggle-btn").on("click", function() {
    $(".chat-wrapper").toggleClass("chat-toggled")
  }), $(".chat-toggle-btn-mobile").on("click", function() {
    $(".chat-wrapper").removeClass("chat-toggled")
  }),

  /* switcher */
  $("#BlueTheme").on("click", function () {
    $("html").attr("data-bs-theme", "blue-theme")
  }),

  $("#LightTheme").on("click", function () {
    $("html").attr("data-bs-theme", "light")
  }),

  $("#DarkTheme").on("click", function () {
    $("html").attr("data-bs-theme", "dark")
  }),

  $("#SemiDark").on("click", function () {
    $("html").attr("data-bs-theme", "semi-dark")
  }),

  $("#BorderedTheme").on("click", function () {
    $("html").attr("data-bs-theme", "bordered-theme")
  }),

  /* search */
  $(".search-close").on("click", function () {
    $(".search-bar").removeClass("search-bar-show")
  }),

  $(".mobile-search-btn").on("click", function () {
    $(".search-bar").addClass("search-bar-show")
  }),

  /* notification */
  $(".notification-close").on("click", function () {
    $(".notification-popup").hide()
  }),

  /* email */
  $(".email-close").on("click", function () {
    $(".email-popup").hide()
  }),

  /* chat */
  $(".chat-close").on("click", function () {
    $(".chat-popup").hide()
  }),

  /* switcher */
  $(".switcher-close").on("click", function () {
    $(".switcher-wrapper").removeClass("switcher-toggled")
  }),

  $(".switcher-btn").on("click", function () {
    $(".switcher-wrapper").addClass("switcher-toggled")
  }),

  /* loader */
  $(window).on("load", function () {
    $(".loader-wrapper").fadeOut("slow");
  });

  /* tooltip */
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  })

  /* popover */
  var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
  var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl)
  })

  /* toast */
  var toastElList = [].slice.call(document.querySelectorAll('.toast'))
  var toastList = toastElList.map(function (toastEl) {
    return new bootstrap.Toast(toastEl)
  })

  /* alert */
  var alertList = document.querySelectorAll('.alert')
  alertList.forEach(function (alert) {
    new bootstrap.Alert(alert)
  })

  /* modal */
  var modalTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="modal"]'))
  var modalList = modalTriggerList.map(function (modalTriggerEl) {
    return new bootstrap.Modal(modalTriggerEl)
  })

  /* collapse */
  var collapseElementList = [].slice.call(document.querySelectorAll('.collapse'))
  var collapseList = collapseElementList.map(function (collapseEl) {
    return new bootstrap.Collapse(collapseEl)
  })

  /* dropdown */
  var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
  var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
    return new bootstrap.Dropdown(dropdownToggleEl)
  })

  /* tab */
  var triggerTabList = [].slice.call(document.querySelectorAll('#myTab button'))
  triggerTabList.forEach(function (triggerEl) {
    var tabTrigger = new bootstrap.Tab(triggerEl)

    triggerEl.addEventListener('click', function (event) {
      event.preventDefault()
      tabTrigger.show()
    })
  })

  /* offcanvas */
  var offcanvasElementList = [].slice.call(document.querySelectorAll('.offcanvas'))
  var offcanvasList = offcanvasElementList.map(function (offcanvasEl) {
    return new bootstrap.Offcanvas(offcanvasEl)
  })

  /* carousel */
  var carouselElementList = [].slice.call(document.querySelectorAll('.carousel'))
  var carouselList = carouselElementList.map(function (carouselEl) {
    return new bootstrap.Carousel(carouselEl)
  })

  /* scrollspy */
  var scrollSpyElementList = [].slice.call(document.querySelectorAll('[data-bs-spy="scroll"]'))
  var scrollSpyList = scrollSpyElementList.map(function (scrollSpyEl) {
    return new bootstrap.ScrollSpy(scrollSpyEl)
  })

  /* toastr */
  if (typeof toastr !== 'undefined') {
    toastr.options = {
      "closeButton": true,
      "debug": false,
      "newestOnTop": false,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "5000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }
  }

  /* sweetalert2 */
  if (typeof Swal !== 'undefined') {
    window.Swal = Swal;
  }

});
