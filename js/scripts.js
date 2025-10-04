(function ($) {
  const $document = $(document);
  const $window = $(window);
  const $body = $("body");
  const offsetScroll = 20;
  let debounceTimeout;

  /**
   * Debounce function
   */
  function debounce(func, delay) {
    clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(func, delay);
  }

  /**
   * Add a class to the body when scrolling
   */
  function setBodyClassOnScroll() {
    console.log("setBodyClassOnScroll");
    function adjustLogoColor() {
      // Detect screen size and adjust logo color accordingly
      var $logoNew = $(".budi-simplistic-header-logo .logo");
      var $hero = $(".budi-hero");
      var $hero_border = $(".budi-hero-border");
      if ($logoNew.length) {
        var companyLogoColor = $logoNew.attr("data-company-logo-color");
        var companyLogoWhite = $logoNew.attr("data-company-logo-white");
        if (window.innerWidth <= 767) {
          // On mobile, use color logo if scrolled, white if not
          if ($body.hasClass("budi-scrolled")) {
            if (companyLogoColor && $logoNew.attr("src") !== companyLogoColor) {
              $logoNew.attr("src", companyLogoColor);
            }
          } else {
            if (
              companyLogoWhite &&
              $logoNew.attr("src") !== companyLogoWhite &&
              ($hero.length > 0 || $hero_border.length > 0)
            ) {
              $logoNew.attr("src", companyLogoWhite);
            }
          }
        } else {
          $logoNew.attr("src", companyLogoColor);
        }
      }
    }

    function handleScroll() {
      let scroll = $window.scrollTop();

      if (scroll >= offsetScroll) {
        if (!$body.hasClass("budi-scrolled")) {
          $body.addClass("budi-scrolled");
        }
      } else {
        if ($body.hasClass("budi-scrolled")) {
          $body.removeClass("budi-scrolled");
        }
      }
      adjustLogoColor();
    }

    function handleResize() {
      adjustLogoColor();
    }

    handleScroll(); // Initial check on page load

    $window.scroll(function () {
      debounce(handleScroll, 100); // Debounce scroll event
    });

    $window.resize(function () {
      debounce(handleResize, 100); // Debounce resize event
    });
  }

  /**
   * Set Padding top of content when the header is fixed
   */
  function setPaddingTopPageContent() {
    const $hero = $("#hero");
    const $header = $("#simplistic-header");

    function updatePadding() {
      const headerHeight = $header.outerHeight();
      if ($(window).width() > 767) {
        $hero.css("padding-top", headerHeight);
      } else {
        $hero.css("padding-top", 0);
      }
    }

    $window.on("load resize", updatePadding);
    updatePadding(); // Initial call to set padding on page load
  }

  /**
   * Check all tag a html when the a href is go to external site then make it open new tab
   */
  function openNewTabForExternalUrl() {
    $document.ready(function () {
      // Select all anchor tags on the page
      $("a").each(function () {
        var link = $(this).attr("href");

        // Check if the link is external
        if (
          link &&
          link.startsWith("http") &&
          !link.includes(window.location.hostname)
        ) {
          // Open external links in a new tab
          $(this).attr("target", "_blank");
        }
      });
    });
  }

  /**
   * Custom WP Bakery Default Accordion
   */
  function customAccordionWPBakery() {
    $document.on(
      "click",
      ".vc_tta-panel.vc_active .vc_tta-panel-heading",
      function (e) {
        e.preventDefault();
        const $this = $(this);
        const $vc_panel = $this.parents(".vc_tta-panel");
        $vc_panel.removeClass("vc_active");
        $vc_panel.addClass("vc_animating");
        $vc_panel.find(".vc_tta-panel-body").slideUp(300, function () {
          $vc_panel.removeClass("vc_animating");
        });
      }
    );
  }

  /**
   * Auto selected job in form when on single job page
   */
  function autoSelectedJobForm() {
    if ($("body").hasClass("single-job_listing")) {
      const jobTitle = $(".budi-job-detail__title").text().trim();
      $("select[name='job_position']").val(jobTitle);
    }
  }

  /**
   * Set top position of popup menu
   */
  function setTopPositionPopupMenu() {
    const updatePosition = function () {
      const $popupMenu = $(".budi-simplistic-popup-menu__wrapper");
      const $header = $("#simplistic-header");
      let headerHeight = 0;
      let wpAdminBarHeight = 0;

      // Always get header height regardless of screen size
      headerHeight = $header.length > 0 ? $header.outerHeight() : 0;
      
      // Get WP admin bar height only on desktop
      if (window.innerWidth >= 768) {
        const $wpAdminBar = $("#wpadminbar");
        wpAdminBarHeight = $wpAdminBar.length > 0 ? $wpAdminBar.outerHeight() : 0;
      }

      const totalHeight = headerHeight + wpAdminBarHeight;
      
      // Set CSS custom property for header height
      document.documentElement.style.setProperty('--header-height', totalHeight + 'px');
    };

    // Initial call
    updatePosition();

    // Update position on window scroll
    let scrollStatus = false;
    $(window).on("scroll", function () {
      let scroll = $window.scrollTop();
      if (
        scroll >= offsetScroll &&
        $(".budi-simplistic-popup-menu__wrapper").is(":visible")
      ) {
        if (!scrollStatus) {
          setTimeout(() => {
            updatePosition();
            scrollStatus = true;
          }, 300);
        }
      } else {
        scrollStatus = false;
        console.log("scrollStatus", scrollStatus);
        setTimeout(() => {
          updatePosition();
        }, 300);
      }
    });
  }

  /**
   * Popup Menu Hamburger Menu
   */
  function popupMenuHamburgerMenu() {
    const $hamburgerMenu = $(".budi-hamburger-menu-button");
    const $popupMenu = $(".budi-simplistic-popup-menu__wrapper");
    const $overlay = $("#primary-navigation-overlay");
    
    $hamburgerMenu.on("click", function () {
      const isOpen = $popupMenu.hasClass("show");
      
      if (isOpen) {
        // Close menu
        $popupMenu.removeClass("show");
        $("body").removeClass("budi-popup-menu-open");
        $hamburgerMenu.removeClass("active");
      } else {
        // Open menu
        $popupMenu.addClass("show");
        $("body").addClass("budi-popup-menu-open");
        $hamburgerMenu.addClass("active");
        setTopPositionPopupMenu();
      }
    });

    // Close menu when clicking on overlay
    $overlay.on("click", function () {
      $popupMenu.removeClass("show");
      $("body").removeClass("budi-popup-menu-open");
      $hamburgerMenu.removeClass("active");
    });

    // Close menu when clicking on menu links
    $popupMenu.find("a").on("click", function () {
      $popupMenu.removeClass("show");
      $("body").removeClass("budi-popup-menu-open");
      $hamburgerMenu.removeClass("active");
    });
  }

  /**
   * Set margin top for main content
   */
  function setMarginTopForMainContent() {
    const $mainContent = $(".page-content");
    const $header = $(".simplistic-header-container");

    function updateMarginTop() {
      let marginTop = 0;
      // Check if on mobile (width <= 767px)
      if (window.innerWidth > 767) {
        marginTop = $header.length > 0 ? $header.outerHeight() : 0;
      } else {
        marginTop = 0;
      }
      $mainContent.css("margin-top", marginTop);
    }

    // Initial call
    updateMarginTop();

    // Detect when screen size changes (window resize)
    $(window).on("resize", function () {
      updateMarginTop();
    });
  }

  /**
   * Rellax js
   */
  function rellaxJs() {
    var rellaxVertical = new Rellax(".rellax-vertical", {
      center: false,
      wrapper: null,
      round: true,
      vertical: true,
      horizontal: false,
    });


    var rellaxHorizontal = new Rellax(".rellax-horizontal", {
      horizontal: true,
      vertical: false,
    });
  }

  /**
   * Heartbeat Button JS
   */
  function heartbeatButton() {
    $(".btn-heartbeat").each(function () {
      var $btn = $(this);
      var isHovering = false;

      // Hover masuk → tandai sedang hover
      $btn.on("mouseenter", function () {
        isHovering = true;
      });

      // Hover keluar → reset
      $btn.on("mouseleave", function () {
        isHovering = false;
        $btn.css("animation-play-state", "running");
      });

      // Saat animasi selesai 1 loop
      $btn.on("animationiteration webkitAnimationIteration", function () {
        if (isHovering) {
          $btn.css("animation-play-state", "paused");
          $btn.css("transform", "scale(1)"); // pastikan berhenti normal
        }
      });
    });
  }

  /**
   * Initialize JS Function
   */
  setBodyClassOnScroll();
  setPaddingTopPageContent();
  openNewTabForExternalUrl();
  customAccordionWPBakery();
  autoSelectedJobForm();
  popupMenuHamburgerMenu();
  setMarginTopForMainContent();
  rellaxJs();
  heartbeatButton();
})(jQuery);
