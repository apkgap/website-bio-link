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
    
})(jQuery);
