(function ($) {
  // Hamburger Menu
  $(document).on("click", ".hamburger", function () {
    if ($(window).width() < 1024) {
      $(this).closest(".navbar").toggleClass("is-active");
      $(this).toggleClass("is-active");
      $(this).closest(".navbar").find("#main-nav").toggleClass("collapse");
    } else {
      $("#site-navigation").toggleClass("open");
    }
  });

  // Search
  $(document).on("click", "button.search", function (e) {
    if ($("form#search").hasClass("active")) {
      $("form#search").submit();
    } else {
      e.preventDefault();
    }
    $("form#search").addClass("active");
  });
  $(document).on("click", "form#search span.close", function (e) {
    $("form#search").removeClass("active");
  });

  $(window).resize(function () {
    if ($(window).width() < 767) {
      $("#global-nav").addClass("d-none");
      $("#global-nav #primary-menu-wrap").addClass("collapse");
      $(".global-bar .hamburger").removeClass("is-active");
    }
    if ($(window).width() < 991) {
      $(".sidebar").stickySidebar("destroy");
      $(".sidebar").removeData("stickySidebar");
    } else {
      $(".sidebar").stickySidebar({
        topSpacing: 20,
        bottomSpacing: 20,
        innerWrapperSelector: ".inner",
        containerSelector: ".holder",
      });
    }
  });

  if ($(".breadcrumbs-top").length > 0) {
    $breadcrumbCount = $('.breadcrumbs-top span[property="itemListElement"]').length;
    $(".breadcrumbs-top").addClass("amount-" + $breadcrumbCount);
  }

  $(document).on("click", ".accordion-title", function () {
    $(this).find(".chevron").toggleClass("up bottom");
    $(this).toggleClass("open");
    $(this).next().toggleClass("open");
  });

  $(document).on("click", "#faqs .categories .category", function (e) {
    $("#faqs .categories .category").removeClass("active");
    $(this).toggleClass("active");
    $("#faqs .faq").hide();
    $("#faqs .faq." + $(this).attr("data-category")).toggle();
  });

  $(document).on("click", "#faqs .expand", function (e) {
    $("#faqs .faq .faq-content").toggle();
    $(this).toggleClass("active");

    if ($(this).hasClass("active")) {
      $(this).find("p").text("Disband");
      $("#faqs .faq .faq-title").find("i").text("expand_less");
    } else {
      $(this).find("p").text("Expand All");
      $("#faqs .faq .faq-title").find("i").text("expand_more");
    }
  });

  $(document).on("click", "#faqs .faq-title", function (e) {
    $(this).next().toggle();
    $(this).toggleClass("active");

    if ($(this).hasClass("active")) {
      $(this).find("i").text("expand_less");
    } else {
      $(this).find("i").text("expand_more");
    }
  });

  // On This Page Item Generation

  if ($(".single").length > 0) {
    $counter = 0;
    $(".single-column-content h2, .heading h2").each(function () {
      $section = $(this).text();
      $addToMenu = "";
      if ($section !== "" && !$(this).hasClass("no-menu")) {
        $counter++;
        $(this).attr("class", "point-" + $counter);
        $addToMenu +=
          '<li><a href="#point-' +
          $counter +
          '" id="' +
          $counter +
          '" class="circle dark-red"><span class="material-icons">arrow_forward</span><span class="text">' +
          $(this).text() +
          "</span></a></li>";
        $($addToMenu).insertBefore(".on-this-page ul li.cta");
      }
    });

    $(document).on("click", ".on-this-page a", function (e) {
      $target = $(this).attr("id");
      if (
        !$(this).parent().hasClass("member") &&
        !$(this).parent().hasClass("yellow") &&
        !$(this).parent().hasClass("external")
      ) {
        e.preventDefault();
        $("html,body").animate(
          {
            scrollTop: $("h2.point-" + $target).offset().top - 20,
          },
          5
        );
      }
    });
  }

  if (window.location.hash) {
    var hash = window.location.hash.replace("#", "");
    $("html,body").animate(
      {
        scrollTop: $("." + hash).offset().top - 20,
      },
      5
    );
  }

  $(document).on("click", ".lightbox a.cta", function (e) {
    $(this).closest(".lightbox").find(".is-close").trigger("click");
  });

  $(document).keypress(function (event) {
    if (event.key === "Enter") {
      $(".lightbox").find(".is-close").trigger("click");
    }
  });
  $(document).on("keypress", function (e) {
    if (e.which == 13) {
      $(".lightbox").find(".is-close").trigger("click");
    }
  });

  $(document).on("click", ".bio a.cta", function (e) {
    $("html,body").animate(
      {
        scrollTop: 0,
      },
      100
    );
  });

  $(document).on("click", ".page-tiles .the-card", function (e) {
    if (!$(this).closest(".page-tiles").hasClass("sf-data")) {
      $link = $(this).find("h2 a").attr("href");
      window.location = $link;
    }
  });

  if ($(window).width() > 991) {
    $(".sidebar").stickySidebar({
      topSpacing: 20,
      bottomSpacing: 20,
      innerWrapperSelector: ".inner",
      containerSelector: ".holder",
    });
  }

  // Redirect Thank you page after 5 seconds
  if ($(".page-id-1204").length > 0) {
    window.setTimeout(function () {
      window.location = "/login";
    }, 5000);
  }

  var map = {};
  $(document).keydown(function (e) {
    if (!$("body").hasClass("single-assessments")) {
      e = e || event; // to deal with IE
      delete map[e.keyCode];
      map[e.keyCode] = e.type == "keydown";

      if (map[17] == true && map[48] == true) {
        window.location = "/accessibility-of-and-website/";
      }
      if (map[17] == true && map[49] == true) {
        window.location = "/";
      }
      if (map[17] == true && map[50] == true) {
        $(".skip-link").trigger("click");
      }
      if (map[17] == true && map[51] == true) {
        window.location = "/sitemap.xml";
      }

      // console.log(map);
    }
  });

  $(document).on("click", ".member.cta a", function (e) {
    if ($(this).attr("href") == "/login") {
      e.preventDefault();
      $("#sfid-login-button").click();
    }
  });

  if (window.location.pathname == "/login/") {
    window.setTimeout(function () {
      $("#sfid-login-button").click();
    }, 5000);
  }

  // Current Opportunities Search
  $(document).on("submit", ".current-opportunities .search form", function (e) {
    $searchDiscipline = $('select[name="role"]').val();
    $searchLocation = $('select[name="location"]').val();

    $(".job").each(function (i, obj) {
      $(this).addClass("hide");

      $jobDiscipline = $(this).attr("data-discipline");
      $jobLocation = $(this).attr("data-location");

      if ($searchLocation == "All" && $searchDiscipline == $jobDiscipline) {
        $(this).removeClass("hide");
      }

      if ($jobLocation == "All" && $searchDiscipline == $jobDiscipline) {
        $(this).removeClass("hide");
      }

      if ($searchLocation !== "All" && $searchDiscipline == "Any" && $searchLocation == $jobLocation) {
        $(this).removeClass("hide");
      }

      if ($searchLocation == $jobLocation && $searchDiscipline == $jobDiscipline) {
        $(this).removeClass("hide");
      }

      if ($searchLocation == "All" && $searchDiscipline == "Any") {
        $(this).removeClass("hide");
      }
    });

    e.preventDefault();
  });

  $(document).on("click", "a.cta", function (e) {
    e.preventDefault();

    if (
      $(this).attr("href") ==
        "https://andau.force.com/forms/s/andforms?formtype=mentor_application&programid=a0k9q000000AIwUAAW" ||
      $(this).attr("href") ==
        "https://andau.force.com/forms/s/andforms?formtype=stepping_position&programid=a0k9q000000BkFbAAK"
    ) {
      if (getCookie("lgi") == "true") {
        window.location = $(this).attr("href");
      } else {
        if (
          $(this).attr("href") ==
          "https://andau.force.com/forms/s/andforms?formtype=stepping_position&programid=a0k9q000000BkFbAAK"
        ) {
          createCookie(
            "stepping",
            "https://andau.force.com/forms/s/andforms?formtype=stepping_position&programid=a0k9q000000BkFbAAK"
          );
        } else {
          createCookie(
            "mentor",
            "https://andau.force.com/forms/s/andforms?formtype=mentor_application&programid=a0k9q000000AIwUAAW"
          );
        }
        window.location = "/login";
      }
    } else {
      if (
        $(this).attr("href") !== "" &&
        $(this).attr("href") !== "#" &&
        $(this).attr("href") !== undefined &&
        $(this).attr("href") !== "undefined"
      ) {
        window.location = $(this).attr("href");
      }
    }
  });
})(jQuery);
