(function ($) {
  "use strict";

  // Parallax Animation Handler
  class ParallaxHandler {
    constructor() {
      this.parallaxElements = [];
      this.isScrolling = false;
      this.ticking = false;
      this.init();
    }

    init() {
      this.bindEvents();
      this.findParallaxElements();
    }

    bindEvents() {
      // Throttled scroll event for better performance
      $(window).on('scroll', () => {
        if (!this.ticking) {
          requestAnimationFrame(() => {
            this.handleScroll();
            this.ticking = false;
          });
          this.ticking = true;
        }
      });

      // Handle resize events
      $(window).on('resize', () => {
        this.updateElementPositions();
      });
    }

    findParallaxElements() {
      $('.budi-benefit-tabs__floating-icon').each((index, element) => {
        const $element = $(element);
        const $container = $element.closest('.budi-benefit-tabs__wrapper');
        
        if ($container.length) {
          this.parallaxElements.push({
            element: $element,
            container: $container,
            containerOffset: $container.offset().top,
            containerHeight: $container.outerHeight(),
            parallaxSpeed: 0.2, // Adjust this value to control parallax intensity
            isVisible: false
          });
        }
      });
    }

    handleScroll() {
      const scrollTop = $(window).scrollTop();
      const windowHeight = $(window).height();
      const documentHeight = $(document).height();

      this.parallaxElements.forEach(item => {
        this.updateParallaxElement(item, scrollTop, windowHeight);
      });
    }

    updateParallaxElement(item, scrollTop, windowHeight) {
      const { element, container, containerOffset, containerHeight, parallaxSpeed } = item;
      
      // Check if element is in viewport
      const elementTop = containerOffset;
      const elementBottom = containerOffset + containerHeight;
      const viewportTop = scrollTop;
      const viewportBottom = scrollTop + windowHeight;

      const isInViewport = elementBottom > viewportTop && elementTop < viewportBottom;
      
      if (isInViewport && !item.isVisible) {
        element.addClass('parallax-active');
        item.isVisible = true;
      } else if (!isInViewport && item.isVisible) {
        element.removeClass('parallax-active');
        item.isVisible = false;
        return;
      }

      if (!isInViewport) return;

      // Calculate parallax offset relative to when element first enters viewport
      const scrolled = scrollTop - elementTop;
      const parallaxOffset = scrolled * parallaxSpeed;
      
      // Get base transform (original CSS positioning)
      const baseTransform = this.getBaseTransform(element);
      
      // Combine base transform with parallax offset
      const newTransform = this.combineTransforms(baseTransform, {
        translateY: parallaxOffset
      });
      
      element.css('transform', newTransform);
    }

    getBaseTransform($element) {
      // Get the base transform from the element's class
      if ($element.hasClass('budi-benefit-tabs__floating-icon--top-left')) {
        return 'translateX(-50%)';
      } else if ($element.hasClass('budi-benefit-tabs__floating-icon--top-right')) {
        return 'translateX(50%)';
      } else if ($element.hasClass('budi-benefit-tabs__floating-icon--middle-left')) {
        return 'translateY(-50%) translateX(-50%)';
      } else if ($element.hasClass('budi-benefit-tabs__floating-icon--middle-right')) {
        return 'translateY(-50%) translateX(50%)';
      } else if ($element.hasClass('budi-benefit-tabs__floating-icon--bottom-left')) {
        return 'translateX(-50%)';
      } else if ($element.hasClass('budi-benefit-tabs__floating-icon--bottom-right')) {
        return 'translateX(50%)';
      } else {
        // Fallback to top-right for backward compatibility
        return 'translateX(50%)';
      }
    }

    combineTransforms(baseTransform, parallaxTransform) {
      // Parse and combine transforms
      const transforms = [];
      
      // Add base transform
      if (baseTransform && baseTransform !== 'none') {
        transforms.push(baseTransform);
      }
      
      // Add parallax transform
      if (parallaxTransform.translateY !== undefined) {
        transforms.push(`translateY(${parallaxTransform.translateY}px)`);
      }
      
      return transforms.join(' ');
    }

    updateElementPositions() {
      this.parallaxElements.forEach(item => {
        item.containerOffset = item.container.offset().top;
        item.containerHeight = item.container.outerHeight();
      });
    }

    // Method to add new parallax elements dynamically
    addParallaxElement($element) {
      const $container = $element.closest('.budi-benefit-tabs__wrapper');
      
      if ($container.length) {
        this.parallaxElements.push({
          element: $element,
          container: $container,
          containerOffset: $container.offset().top,
          containerHeight: $container.outerHeight(),
          parallaxSpeed: 0.2,
          isVisible: false
        });
      }
    }

    // Method to remove parallax elements
    removeParallaxElement($element) {
      this.parallaxElements = this.parallaxElements.filter(item => 
        !item.element.is($element)
      );
    }
  }

  // Initialize global parallax handler
  const parallaxHandler = new ParallaxHandler();

  $(document).ready(function () {
    // Function to update floating icon based on active tab
    function updateFloatingIcon($wrapper, $activeContent) {
      const $iconContainer = $wrapper.find(
        ".budi-benefit-tabs__floating-icon-container"
      );
      const floatingIconId = $activeContent.data("floating-icon");
      const iconPosition = $activeContent.data("icon-position") || "top-right";
      const iconPositionTablet = $activeContent.data("icon-position-tablet") || "top-right";
      const iconPositionMobile = $activeContent.data("icon-position-mobile") || "top-right";
      const iconWidth = $activeContent.data("icon-width") || "80px";
      const iconWidthTablet = $activeContent.data("icon-width-tablet") || "100px";
      const iconWidthMobile = $activeContent.data("icon-width-mobile") || "60px";

      if (floatingIconId) {
        // Update the floating icon with fade transition
        $iconContainer.fadeOut(200, function () {
          const iconHtml = `<div class="budi-benefit-tabs__floating-icon budi-benefit-tabs__floating-icon--${iconPosition}" style="--icon-width: ${iconWidth}; --icon-width-tablet: ${iconWidthTablet}; --icon-width-mobile: ${iconWidthMobile};" data-icon-position-tablet="${iconPositionTablet}" data-icon-position-mobile="${iconPositionMobile}">
                        <img src="${floatingIconId}" class="budi-benefit-tabs__floating-icon-img" style="max-width: ${iconWidth};" alt="Floating Icon">
                    </div>`;
          $iconContainer.html(iconHtml).fadeIn(200, function() {
            // Add the new floating icon to parallax handler after it's rendered
            const $newIcon = $iconContainer.find('.budi-benefit-tabs__floating-icon');
            if ($newIcon.length) {
              // Set initial transform to prevent glitch
              const baseTransform = parallaxHandler.getBaseTransform($newIcon);
              $newIcon.css('transform', baseTransform);
              
              // Add to parallax handler
              parallaxHandler.addParallaxElement($newIcon);
              
              // Immediately apply current scroll position to prevent glitch
              const scrollTop = $(window).scrollTop();
              const containerOffset = $wrapper.offset().top;
              const scrolled = scrollTop - containerOffset;
              const parallaxOffset = scrolled * 0.2; // Use same speed as parallax
              
              // Only apply parallax if we're scrolled past the container start
              if (scrolled > 0) {
                const newTransform = parallaxHandler.combineTransforms(baseTransform, {
                  translateY: parallaxOffset
                });
                $newIcon.css('transform', newTransform);
              } else {
                // Keep original CSS positioning when at the top
                $newIcon.css('transform', baseTransform);
              }
            }
          });
        });
      } else {
        // Hide floating icon if none for this tab
        $iconContainer.fadeOut(200, function () {
          // Remove from parallax handler before clearing
          const $currentIcon = $iconContainer.find('.budi-benefit-tabs__floating-icon');
          if ($currentIcon.length) {
            parallaxHandler.removeParallaxElement($currentIcon);
          }
          $iconContainer.empty();
        });
      }
    }

    // Initialize benefit tabs
    $(".budi-benefit-tabs__wrapper").each(function () {
      const $wrapper = $(this);
      const $navItems = $wrapper.find(".budi-benefit-tabs__nav-item");
      const $contentItems = $wrapper.find(".budi-benefit-tabs__content-item");

      // Tab click handler
      $navItems.on("click", function (e) {
        e.preventDefault();

        const $this = $(this);
        const tabIndex = $this.data("tab");

        // Remove active class from all nav items
        $navItems.removeClass("active");

        // Add active class to clicked nav item
        $this.addClass("active");

        // Hide all content items
        $contentItems.removeClass("active");

        // Show selected content item with animation
        const $targetContent = $contentItems.filter(
          '[data-tab="' + tabIndex + '"]'
        );
        if ($targetContent.length) {
          $targetContent.addClass("active");

          // Update floating icon based on active tab
          updateFloatingIcon($wrapper, $targetContent);

          // Trigger custom event for any additional animations
          $wrapper.trigger("budi-tab-changed", [tabIndex, $targetContent]);
        }
      });

      // Initialize first tab as active
      if ($navItems.length > 0) {
        $navItems.first().trigger("click");
      }
    });

    // Keyboard navigation support
    $(document).on("keydown", ".budi-benefit-tabs__nav-item", function (e) {
      const $this = $(this);
      const $wrapper = $this.closest(".budi-benefit-tabs__wrapper");
      const $navItems = $wrapper.find(".budi-benefit-tabs__nav-item");
      const currentIndex = $navItems.index($this);

      let newIndex = currentIndex;

      switch (e.keyCode) {
        case 37: // Left arrow
          newIndex = currentIndex > 0 ? currentIndex - 1 : $navItems.length - 1;
          break;
        case 39: // Right arrow
          newIndex = currentIndex < $navItems.length - 1 ? currentIndex + 1 : 0;
          break;
        case 36: // Home
          newIndex = 0;
          break;
        case 35: // End
          newIndex = $navItems.length - 1;
          break;
        default:
          return; // Don't prevent default for other keys
      }

      e.preventDefault();
      $navItems.eq(newIndex).trigger("click").focus();
    });

    // Auto-advance functionality (optional)
    $(".budi-benefit-tabs__wrapper[data-auto-advance]").each(function () {
      const $wrapper = $(this);
      const autoAdvanceDelay = parseInt($wrapper.data("auto-advance")) || 5000;
      const $navItems = $wrapper.find(".budi-benefit-tabs__nav-item");
      let currentIndex = 0;
      let autoAdvanceInterval;

      function startAutoAdvance() {
        autoAdvanceInterval = setInterval(function () {
          currentIndex = (currentIndex + 1) % $navItems.length;
          $navItems.eq(currentIndex).trigger("click");
        }, autoAdvanceDelay);
      }

      function stopAutoAdvance() {
        if (autoAdvanceInterval) {
          clearInterval(autoAdvanceInterval);
        }
      }

      // Start auto-advance
      startAutoAdvance();

      // Pause on hover
      $wrapper.on("mouseenter", stopAutoAdvance);
      $wrapper.on("mouseleave", startAutoAdvance);

      // Pause on focus
      $wrapper.on("focusin", stopAutoAdvance);
      $wrapper.on("focusout", startAutoAdvance);
    });

    // Smooth scroll to content on mobile
    $(window).on("resize", function () {
      if ($(window).width() < 768) {
        $(".budi-benefit-tabs__nav-item").on("click", function () {
          const $wrapper = $(this).closest(".budi-benefit-tabs__wrapper");
          const $content = $wrapper.find(".budi-benefit-tabs__content");

          setTimeout(function () {
            $("html, body").animate(
              {
                scrollTop: $content.offset().top - 100,
              },
              500
            );
          }, 300);
        });
      }
    });

    // Lazy loading for images
    $(".budi-benefit-tabs__image").each(function () {
      const $img = $(this);
      if ($img.data("src")) {
        $img.attr("src", $img.data("src")).removeAttr("data-src");
      }
    });

    // Intersection Observer for animations
    if ("IntersectionObserver" in window) {
      const observer = new IntersectionObserver(
        function (entries) {
          entries.forEach(function (entry) {
            if (entry.isIntersecting) {
              entry.target.classList.add("budi-animate-in");
            }
          });
        },
        {
          threshold: 0.1,
        }
      );

      $(".budi-benefit-tabs__wrapper").each(function () {
        observer.observe(this);
      });
    }

    // Performance optimization: Pause parallax on reduced motion preference
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      parallaxHandler.parallaxElements.forEach(item => {
        item.parallaxSpeed = 0;
      });
    }

    // Performance optimization: Pause parallax when tab is not in focus
    $(document).on('visibilitychange', function() {
      if (document.hidden) {
        parallaxHandler.isScrolling = false;
      }
    });

    // Performance optimization: Clean up on page unload
    $(window).on('beforeunload', function() {
      parallaxHandler.parallaxElements = [];
    });
  });
})(jQuery);
