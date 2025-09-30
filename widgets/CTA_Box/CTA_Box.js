jQuery(document).ready(function($) {
    // Handle background image picker
    $(document).on('click', '.background-picker', function(e) {
        e.preventDefault();
        
        var button = $(this);
        var input = button.siblings('.background_image');
        
        // Create the media frame
        var frame = wp.media({
            title: 'Hintergrundbild auswählen',
            button: {
                text: 'Bild verwenden'
            },
            multiple: false
        });
        
        // When an image is selected, run a callback
        frame.on('select', function() {
            var attachment = frame.state().get('selection').first().toJSON();
            input.val(attachment.url);
        });
        
        // Open the modal
        frame.open();
    });
    
    // Handle widget save and load
    $(document).on('widget-updated widget-added', function(event, widget) {
        // Reinitialize any custom functionality if needed
        if (widget.find('.background-picker').length) {
            // Widget has background picker, ensure it's working
            widget.find('.background-picker').off('click').on('click', function(e) {
                e.preventDefault();
                
                var button = $(this);
                var input = button.siblings('.background_image');
                
                var frame = wp.media({
                    title: 'Hintergrundbild auswählen',
                    button: {
                        text: 'Bild verwenden'
                    },
                    multiple: false
                });
                
                frame.on('select', function() {
                    var attachment = frame.state().get('selection').first().toJSON();
                    input.val(attachment.url);
                });
                
                frame.open();
            });
        }
    });
}); 