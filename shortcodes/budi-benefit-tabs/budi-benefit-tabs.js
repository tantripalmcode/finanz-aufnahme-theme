(function($) {
    'use strict';

    $(document).ready(function() {
        
        // Function to update floating icon based on active tab
        function updateFloatingIcon($wrapper, $activeContent) {
            const $iconContainer = $wrapper.find('.budi-benefit-tabs__floating-icon-container');
            const floatingIconId = $activeContent.data('floating-icon');
            const iconPosition = $activeContent.data('icon-position') || 'right';
            const iconWidth = $activeContent.data('icon-width') || '80px';
            
            if (floatingIconId) {
                // Update the floating icon with fade transition
                $iconContainer.fadeOut(200, function() {
                    const iconHtml = `<div class="budi-benefit-tabs__floating-icon budi-benefit-tabs__floating-icon--${iconPosition}" style="--icon-width: ${iconWidth};">
                        <img src="${floatingIconId}" class="budi-benefit-tabs__floating-icon-img" style="max-width: ${iconWidth};" alt="Floating Icon">
                    </div>`;
                    $iconContainer.html(iconHtml).fadeIn(200);
                });
            } else {
                // Hide floating icon if none for this tab
                $iconContainer.fadeOut(200, function() {
                    $iconContainer.empty();
                });
            }
        }

        // Initialize benefit tabs
        $('.budi-benefit-tabs__wrapper').each(function() {
            const $wrapper = $(this);
            const $navItems = $wrapper.find('.budi-benefit-tabs__nav-item');
            const $contentItems = $wrapper.find('.budi-benefit-tabs__content-item');
            
            // Tab click handler
            $navItems.on('click', function(e) {
                e.preventDefault();
                
                const $this = $(this);
                const tabIndex = $this.data('tab');
                
                // Remove active class from all nav items
                $navItems.removeClass('active');
                
                // Add active class to clicked nav item
                $this.addClass('active');
                
                // Hide all content items
                $contentItems.removeClass('active');
                
                // Show selected content item with animation
                const $targetContent = $contentItems.filter('[data-tab="' + tabIndex + '"]');
                if ($targetContent.length) {
                    $targetContent.addClass('active');
                    
                    // Update floating icon based on active tab
                    updateFloatingIcon($wrapper, $targetContent);
                    
                    // Trigger custom event for any additional animations
                    $wrapper.trigger('budi-tab-changed', [tabIndex, $targetContent]);
                }
            });
            
            // Initialize first tab as active
            if ($navItems.length > 0) {
                $navItems.first().trigger('click');
            }
        });
        
        // Keyboard navigation support
        $(document).on('keydown', '.budi-benefit-tabs__nav-item', function(e) {
            const $this = $(this);
            const $wrapper = $this.closest('.budi-benefit-tabs__wrapper');
            const $navItems = $wrapper.find('.budi-benefit-tabs__nav-item');
            const currentIndex = $navItems.index($this);
            
            let newIndex = currentIndex;
            
            switch(e.keyCode) {
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
            $navItems.eq(newIndex).trigger('click').focus();
        });
        
        // Auto-advance functionality (optional)
        $('.budi-benefit-tabs__wrapper[data-auto-advance]').each(function() {
            const $wrapper = $(this);
            const autoAdvanceDelay = parseInt($wrapper.data('auto-advance')) || 5000;
            const $navItems = $wrapper.find('.budi-benefit-tabs__nav-item');
            let currentIndex = 0;
            let autoAdvanceInterval;
            
            function startAutoAdvance() {
                autoAdvanceInterval = setInterval(function() {
                    currentIndex = (currentIndex + 1) % $navItems.length;
                    $navItems.eq(currentIndex).trigger('click');
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
            $wrapper.on('mouseenter', stopAutoAdvance);
            $wrapper.on('mouseleave', startAutoAdvance);
            
            // Pause on focus
            $wrapper.on('focusin', stopAutoAdvance);
            $wrapper.on('focusout', startAutoAdvance);
        });
        
        // Smooth scroll to content on mobile
        $(window).on('resize', function() {
            if ($(window).width() < 768) {
                $('.budi-benefit-tabs__nav-item').on('click', function() {
                    const $wrapper = $(this).closest('.budi-benefit-tabs__wrapper');
                    const $content = $wrapper.find('.budi-benefit-tabs__content');
                    
                    setTimeout(function() {
                        $('html, body').animate({
                            scrollTop: $content.offset().top - 100
                        }, 500);
                    }, 300);
                });
            }
        });
        
        // Lazy loading for images
        $('.budi-benefit-tabs__image').each(function() {
            const $img = $(this);
            if ($img.data('src')) {
                $img.attr('src', $img.data('src')).removeAttr('data-src');
            }
        });
        
        // Intersection Observer for animations
        if ('IntersectionObserver' in window) {
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('budi-animate-in');
                    }
                });
            }, {
                threshold: 0.1
            });
            
            $('.budi-benefit-tabs__wrapper').each(function() {
                observer.observe(this);
            });
        }
        
    });
    
})(jQuery);
