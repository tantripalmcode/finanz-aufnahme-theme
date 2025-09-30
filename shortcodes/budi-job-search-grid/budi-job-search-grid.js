(function ($) {
    $(document).ready(function (e) {
        
        // Initialize Custom Dropdown
        function initCustomDropdown() {
            $('.budi-job-custom-dropdown').each(function() {
                const dropdown = $(this);
                const trigger = dropdown.find('.budi-job-dropdown-trigger');
                const menu = dropdown.find('.budi-job-dropdown-menu');
                const items = dropdown.find('.budi-job-dropdown-item');
                const hiddenInput = dropdown.find('input[type="hidden"]');
                const textElement = dropdown.find('.budi-job-dropdown-text');

                // Toggle dropdown
                trigger.on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Close other dropdowns
                    $('.budi-job-custom-dropdown').not(dropdown).removeClass('active');
                    
                    // Toggle current dropdown
                    dropdown.toggleClass('active');
                });

                // Handle item selection
                items.on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const value = $(this).data('value');
                    const text = $(this).text();
                    
                    // Update hidden input
                    hiddenInput.val(value);
                    
                    // Update display text
                    textElement.text(text);
                    
                    // Update trigger data-value
                    trigger.attr('data-value', value);
                    
                    // Update selected state
                    items.removeClass('selected');
                    $(this).addClass('selected');
                    
                    // Close dropdown
                    dropdown.removeClass('active');
                });

                // Close dropdown when clicking outside
                $(document).on('click', function(e) {
                    if (!dropdown.is(e.target) && dropdown.has(e.target).length === 0) {
                        dropdown.removeClass('active');
                    }
                });

                // Close dropdown on escape key
                $(document).on('keydown', function(e) {
                    if (e.key === 'Escape') {
                        dropdown.removeClass('active');
                    }
                });
            });
        }

        // Initialize Google Maps Autocomplete
        function initGoogleMapsAutocomplete() {
            // Check if Google Maps is loaded
            if (typeof google !== 'undefined' && google.maps && google.maps.places) {
                try {
                    var autocomplete = new google.maps.places.Autocomplete(
                        $("#budi-job-location")[0],
                        {
                            types: ['geocode'],
                            componentRestrictions: { country: 'de' } // Restrict to Germany
                        }
                    );

                    google.maps.event.addListener(autocomplete, "place_changed", function () {
                        const place = autocomplete.getPlace();
                        
                        if (place.geometry && place.geometry.location) {
                            const latitude = place.geometry.location.lat();
                            const longitude = place.geometry.location.lng();

                            $("#budi-lat").val(latitude);
                            $("#budi-lng").val(longitude);
                        } else {
                            console.warn('No geometry found for selected place');
                        }
                    });

                    // Clear coordinates when location input is manually changed
                    $(document).on("input", "#budi-job-location", function() {
                        const currentValue = $(this).val();
                        // Only clear if the value doesn't match a selected place
                        if (currentValue.length > 0 && !$(this).data('place-selected')) {
                            $("#budi-lat").val("");
                            $("#budi-lng").val("");
                        }
                    });

                    // Mark when a place is selected
                    $(document).on("focus", "#budi-job-location", function() {
                        $(this).data('place-selected', false);
                    });

                } catch (error) {
                    console.error('Error initializing Google Maps Autocomplete:', error);
                }
            } else {
                console.warn('Google Maps API not loaded or not available');
                // Fallback: Try to geocode manually entered locations
                initFallbackGeocoding();
            }
        }

        // Fallback geocoding function
        function initFallbackGeocoding() {
            $(document).on("blur", "#budi-job-location", function() {
                const location = $(this).val().trim();
                if (location && !$("#budi-lat").val() && !$("#budi-lng").val()) {
                    // Try to get coordinates using a simple geocoding service
                    geocodeLocation(location);
                }
            });
        }

        // Simple geocoding function (fallback)
        function geocodeLocation(address) {
            // Using a free geocoding service as fallback
            const geocodeUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&countrycodes=de&limit=1`;
            
            fetch(geocodeUrl)
                .then(response => response.json())
                .then(data => {
                    if (data && data.length > 0) {
                        const lat = parseFloat(data[0].lat);
                        const lng = parseFloat(data[0].lon);
                        $("#budi-lat").val(lat);
                        $("#budi-lng").val(lng);
                    }
                })
                .catch(error => {
                    console.error('Fallback geocoding failed:', error);
                });
        }

        // Handle Search Form Submission
        function handleSearchForm() {
            $(document).on("submit", "#budi-job-search-grid-form", function(e) {
                e.preventDefault();
                
                const $form = $(this);
                const $wrapper = $(".budi-job-search-grid__wrapper");
                const $grid = $("#budi-job-search-grid");
                
                // Get form data
                const formData = {
                    keyword: $form.find('input[name="keyword"]').val().trim(),
                    location: $form.find('input[name="location"]').val().trim(),
                    radius: $form.find('input[name="radius"]').val(),
                    lat: $form.find('input[name="lat"]').val(),
                    lng: $form.find('input[name="lng"]').val(),
                    paged: 1
                };

                // If location is provided but no coordinates, try to geocode first
                if (formData.location && (!formData.lat || !formData.lng)) {
                    geocodeLocationAndSearch(formData, $form, $wrapper, $grid);
                    return;
                }
                
                // Update wrapper data attributes
                updateWrapperData($wrapper, formData);
                
                // Perform AJAX search
                ajaxJobSearchGrid(formData);
            });
        }

        // Geocode location and then search
        function geocodeLocationAndSearch(formData, $form, $wrapper, $grid) {
            const location = formData.location;
            
            // Show loading state
            $grid.addClass("budi-processing");
            $grid.html("");
            
            // Try Google Maps geocoding first if available
            if (typeof google !== 'undefined' && google.maps && google.maps.places) {
                const geocoder = new google.maps.Geocoder();
                geocoder.geocode({ address: location + ', Germany' }, function(results, status) {
                    if (status === 'OK' && results[0]) {
                        const lat = results[0].geometry.location.lat();
                        const lng = results[0].geometry.location.lng();
                        
                        // Update form with coordinates
                        $form.find('input[name="lat"]').val(lat);
                        $form.find('input[name="lng"]').val(lng);
                        
                        formData.lat = lat;
                        formData.lng = lng;
                        
                        // Update wrapper data and search
                        updateWrapperData($wrapper, formData);
                        ajaxJobSearchGrid(formData);
                    } else {
                        console.warn('Google geocoding failed:', status);
                        // Try fallback geocoding
                        tryFallbackGeocodingAndSearch(formData, $form, $wrapper, $grid);
                    }
                });
            } else {
                // Try fallback geocoding
                tryFallbackGeocodingAndSearch(formData, $form, $wrapper, $grid);
            }
        }

        // Try fallback geocoding and search
        function tryFallbackGeocodingAndSearch(formData, $form, $wrapper, $grid) {
            const geocodeUrl = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(formData.location)}&countrycodes=de&limit=1`;
            
            fetch(geocodeUrl)
                .then(response => response.json())
                .then(data => {
                    if (data && data.length > 0) {
                        const lat = parseFloat(data[0].lat);
                        const lng = parseFloat(data[0].lon);
                        
                        // Update form with coordinates
                        $form.find('input[name="lat"]').val(lat);
                        $form.find('input[name="lng"]').val(lng);
                        
                        formData.lat = lat;
                        formData.lng = lng;
                        
                        // Update wrapper data and search
                        updateWrapperData($wrapper, formData);
                        ajaxJobSearchGrid(formData);
                    } else {
                        console.warn('Fallback geocoding failed, searching without coordinates');
                        // Search without coordinates
                        updateWrapperData($wrapper, formData);
                        ajaxJobSearchGrid(formData);
                    }
                })
                .catch(error => {
                    console.error('Fallback geocoding error:', error);
                    // Search without coordinates
                    updateWrapperData($wrapper, formData);
                    ajaxJobSearchGrid(formData);
                });
        }

        // Update wrapper data attributes
        function updateWrapperData($wrapper, formData) {
            $wrapper.attr("data-keyword", formData.keyword);
            $wrapper.attr("data-location", formData.location);
            $wrapper.attr("data-radius", formData.radius);
            $wrapper.attr("data-lat", formData.lat);
            $wrapper.attr("data-lng", formData.lng);
            $wrapper.attr("data-paged", 1);
        }

        // Handle Load More Button
        function handleLoadMore() {
            $(document).on("click", "#budi-load-more-btn", function(e) {
                e.preventDefault();
                
                const $wrapper = $(".budi-job-search-grid__wrapper");
                const currentPaged = parseInt($wrapper.attr("data-paged"));
                const maxNumPages = parseInt($wrapper.attr("data-max_num_pages"));
                
                if (currentPaged >= maxNumPages) {
                    return;
                }
                
                const nextPaged = currentPaged + 1;
                $wrapper.attr("data-paged", nextPaged);
                
                // Get current search parameters
                const searchData = {
                    keyword: $wrapper.attr("data-keyword"),
                    location: $wrapper.attr("data-location"),
                    radius: $wrapper.attr("data-radius"),
                    lat: $wrapper.attr("data-lat"),
                    lng: $wrapper.attr("data-lng"),
                    paged: nextPaged
                };
                
                // Load more jobs
                ajaxJobSearchGrid(searchData, true);
            });
        }

        // AJAX Job Search Grid
        function ajaxJobSearchGrid(searchData, isLoadMore = false) {
            const $wrapper = $(".budi-job-search-grid__wrapper");
            const $grid = $("#budi-job-search-grid");
            const $searchForm = $("#budi-job-search-grid-form");
            const $loadMoreBtn = $("#budi-load-more-btn");
            
            const postData = {
                action: "budi_job_search_grid_filter",
                post_type: $wrapper.attr("data-post_type"),
                posts_per_page: $wrapper.attr("data-posts_per_page"),
                keyword: searchData.keyword,
                location: searchData.location,
                radius: searchData.radius,
                lat: searchData.lat,
                lng: searchData.lng,
                paged: searchData.paged,
                max_num_pages: $wrapper.attr("data-max_num_pages")
            };

            // Show loading state
            if (!isLoadMore) {
                $grid.addClass("budi-processing");
                $grid.html("");
            } else {
                $loadMoreBtn.prop("disabled", true).text("Laden...");
            }

            $grid.removeClass("bj-not-found");

            $.ajax({
                type: "POST",
                url: _budigital.ajaxurl,
                data: postData,
                statusCode: {
                    400: function () {
                        location.reload();
                    },
                    403: function () {
                        location.reload();
                    },
                    500: function () {
                        location.reload();
                    },
                },
                success: function (response) {
                    if (response.success) {
                        if (isLoadMore) {
                            // Append new jobs to existing grid
                            $grid.append(response.html);
                        } else {
                            // Replace grid content
                            $grid.html(response.html);
                        }
                        
                        // Update max pages
                        $wrapper.attr("data-max_num_pages", response.max_num_pages);
                        
                        // Update load more button visibility
                        const currentPaged = parseInt($wrapper.attr("data-paged"));
                        const maxNumPages = parseInt(response.max_num_pages);
                        
                        if (currentPaged >= maxNumPages) {
                            $loadMoreBtn.hide();
                        } else {
                            $loadMoreBtn.show();
                        }
                        
                    } else {
                        if (!isLoadMore) {
                            $grid.addClass("bj-not-found");
                            $grid.html(response.html);
                        }
                    }
                    
                    // Remove loading state
                    $grid.removeClass("budi-processing");
                    if (isLoadMore) {
                        $loadMoreBtn.prop("disabled", false).text("Mehr anzeigen");
                    }
                    
                    // Scroll to results if not loading more
                    if (!isLoadMore) {
                        $("html, body").animate({
                            scrollTop: $grid.offset().top - 180 - $searchForm.outerHeight()
                        }, 600);
                    }
                },
                error: function() {
                    // Remove loading state on error
                    $grid.removeClass("budi-processing");
                    if (isLoadMore) {
                        $loadMoreBtn.prop("disabled", false).text("Mehr anzeigen");
                    }
                }
            });
        }

        // Handle Job Item Clicks
        function handleJobItemClicks() {
            $(document).on("click", ".budi-job-search-grid__item", function(e) {
                // Let the link work normally
                // This is just for any additional click handling if needed
            });
        }

        // Initialize all functions
        function init() {
            initCustomDropdown();
            initGoogleMapsAutocomplete();
            handleSearchForm();
            handleLoadMore();
            handleJobItemClicks();
        }

        // Start initialization
        init();
    });
})(jQuery);
