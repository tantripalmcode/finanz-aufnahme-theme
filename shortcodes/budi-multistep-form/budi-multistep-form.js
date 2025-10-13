jQuery(document).ready(function($) {
    'use strict';

    // Initialize progress dots for multistep forms
    function initProgressDots() {
        $('.budi-multistep-form-widget').each(function() {
            var $widget = $(this);
            var $wrapper = $widget.find('.fieldset-cf7mls-wrapper');
            var $fieldsets = $wrapper.find('.fieldset-cf7mls');
            
            if ($fieldsets.length === 0) return;
            
            // Check if progress dots already exist
            if ($widget.find('.budi-progress-dots').length > 0) return;
            
            // Create progress dots container
            var $progressContainer = $('<div class="budi-progress-dots"></div>');
            
            // Create dots for each fieldset
            $fieldsets.each(function(index) {
                var $dot = $('<div class="budi-progress-dot" data-step="' + index + '"></div>');
                $progressContainer.append($dot);
            });
            
            // Insert progress dots before the wrapper
            $wrapper.before($progressContainer);
            
            // Update progress on step change
            function updateProgress() {
                var currentStep = $wrapper.find('.cf7mls_current_fs').index();
                $progressContainer.find('.budi-progress-dot').each(function(index) {
                    var $dot = $(this);
                    $dot.removeClass('current');
                    
                    if (index === currentStep) {
                        // Current step - make it wider
                        $dot.addClass('current');
                    }
                });
            }
            
            // Initial update
            updateProgress();
            
            // Update on navigation
            $wrapper.on('click', '.cf7mls_next, .cf7mls_back', function() {
                setTimeout(updateProgress, 100);
            });
            
            // Listen for CF7 Multi Step events
            $(document).on('cf7mls_step_changed', function() {
                setTimeout(updateProgress, 100);
            });
            
            // Also listen for form step changes via mutation observer
            if (window.MutationObserver) {
                var observer = new MutationObserver(function(mutations) {
                    mutations.forEach(function(mutation) {
                        if (mutation.type === 'attributes' && 
                            mutation.attributeName === 'class' && 
                            $(mutation.target).hasClass('cf7mls_current_fs')) {
                            updateProgress();
                        }
                    });
                });
                
                $fieldsets.each(function() {
                    observer.observe(this, { attributes: true, attributeFilter: ['class'] });
                });
            }
        });
    }
    
    // Initialize on page load
    initProgressDots();
    
    // Re-initialize if content is dynamically loaded
    $(document).on('DOMNodeInserted', function(e) {
        if ($(e.target).find('.budi-multistep-form-widget').length > 0) {
            initProgressDots();
        }
    });
    
    // Also initialize when CF7 Multi Step plugin loads
    $(document).on('cf7mls_initialized', function() {
        initProgressDots();
    });
});
