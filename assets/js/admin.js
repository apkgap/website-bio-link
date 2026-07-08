/**
 * Admin JavaScript for Social Links Repeater
 */
(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        var rowIndex = $('.wbl-social-repeater-row').length;
        
        /**
         * Add new row
         */
        $(document).on('click', '.wbl-social-add-row', function(e) {
            e.preventDefault();
            
            // Get template
            var template = $('.wbl-social-repeater-row-template').first().clone();
            
            // Replace index placeholder
            var html = template.html().replace(/\{\{INDEX\}\}/g, rowIndex);
            
            // Create new row
            var newRow = $('<tr class="wbl-social-repeater-row border-b border-gray-200"></tr>').html(html);
            
            // Append to tbody
            $('.wbl-social-repeater-body').append(newRow);
            
            // Increment index
            rowIndex++;
            
            // Add animation
            newRow.hide().fadeIn(300);
        });
        
        /**
         * Remove row
         */
        $(document).on('click', '.wbl-social-remove-row', function(e) {
            e.preventDefault();
            
            var row = $(this).closest('tr');
            
            // Confirm deletion
            if (typeof wblSocialAdmin !== 'undefined' && wblSocialAdmin.confirmDelete) {
                if (!confirm(wblSocialAdmin.confirmDelete)) {
                    return;
                }
            }
            
            // Remove with animation
            row.fadeOut(300, function() {
                $(this).remove();
            });
        });
        
        /**
         * Make rows sortable
         */
        if ($.fn.sortable) {
            $('.wbl-social-repeater-body').sortable({
                handle: '.dashicons-menu',
                placeholder: 'ui-state-highlight',
                axis: 'y',
                cursor: 'move',
                opacity: 0.8,
                helper: function(e, tr) {
                    var $originals = tr.children();
                    var $helper = tr.clone();
                    $helper.children().each(function(index) {
                        $(this).width($originals.eq(index).width());
                    });
                    return $helper;
                },
                start: function(e, ui) {
                    ui.placeholder.height(ui.item.height());
                    ui.placeholder.css('background-color', '#f3f4f6');
                },
                stop: function(e, ui) {
                    // Update row indices after sorting
                    updateRowIndices();
                }
            });
        }
        
        /**
         * Update row indices after sorting
         */
        function updateRowIndices() {
            $('.wbl-social-repeater-row').each(function(index) {
                $(this).find('select, input').each(function() {
                    var name = $(this).attr('name');
                    if (name) {
                        var newName = name.replace(/\[\d+\]/, '[' + index + ']');
                        $(this).attr('name', newName);
                    }
                });
            });
        }
        
        /**
         * Platform change handler - could add icon preview
         */
        $(document).on('change', '.wbl-social-platform', function() {
            var platform = $(this).val();
            var row = $(this).closest('tr');
            
            // You could add visual feedback here
            // For example, show the platform icon or color
        });
        
    });
    
    // Settings and Meta Box Scripts
    $(document).ready(function() {
        // Initialize color pickers for meta box and settings
        if (typeof $.fn.wpColorPicker !== 'undefined') {
            $('.wbl-color-picker').wpColorPicker();
            $('.wbl-color-picker-small').wpColorPicker();
        }

        // Function to update color fields based on icon style in Meta Box
        function updateColorFields() {
            var iconStyle = $('#wbl_icon_style').val();
            var useCustomColors = $('#wbl_use_custom_colors').is(':checked');

            // Hide all color groups first
            $('.wbl-color-group').hide();
            $('#wbl_no_style_warning').hide();

            if (useCustomColors) {
                $('#wbl_custom_colors_fields').show();

                if (iconStyle && iconStyle !== '') {
                    // Show relevant color group based on selected style
                    $('.wbl-color-group').each(function() {
                        var styles = $(this).data('styles');
                        if (styles) {
                            styles = styles.toString().split(',');
                            if (styles.indexOf(iconStyle) !== -1) {
                                $(this).show();
                            }
                        }
                    });
                } else {
                    // Show warning if no style selected
                    $('#wbl_no_style_warning').show();
                }
            } else {
                $('#wbl_custom_colors_fields').hide();
            }
        }

        // Show/hide custom size input
        $('#wbl_icon_size_preset').on('change', function() {
            if ($(this).val() === 'custom') {
                $('#wbl_icon_size_custom').show().prop('disabled', false).attr('min', '10').attr('max', '100');
            } else {
                $('#wbl_icon_size_custom').hide().prop('disabled', true).removeAttr('min').removeAttr('max');
            }
        });

        // Show/hide custom gap input
        $('#wbl_gap_preset').on('change', function() {
            if ($(this).val() === 'custom') {
                $('#wbl_gap_custom').show().prop('disabled', false).attr('min', '0').attr('max', '100');
            } else {
                $('#wbl_gap_custom').hide().prop('disabled', true).removeAttr('min').removeAttr('max');
            }
        });

        // Show/hide grid columns
        $('#wbl_layout_type').on('change', function() {
            if ($(this).val() === 'grid') {
                $('#wbl_grid_columns_field').show();
                $('#wbl_grid_columns').prop('disabled', false).attr('min', '1').attr('max', '12');
            } else {
                $('#wbl_grid_columns_field').hide();
                $('#wbl_grid_columns').prop('disabled', true).removeAttr('min').removeAttr('max');
            }
        });

        // Update color fields when icon style changes
        $('#wbl_icon_style').on('change', updateColorFields);

        // Update color fields when custom colors toggle changes
        $('#wbl_use_custom_colors').on('change', updateColorFields);

        // Initial update on page load if elements exist
        if ($('#wbl_icon_style').length > 0) {
            updateColorFields();
        }
    });
})(jQuery);
