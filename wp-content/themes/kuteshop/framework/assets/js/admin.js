;(function ($) {
    "use strict"; // Start of use strict
    
    $(document).on('click', '.ovic-field .delete-transients', function (e) {
        e.preventDefault();
        
        var $this    = $(this),
            $text    = $this.data('text-done'),
            $success = $this.parent().find('.ovic-text-success'),
            $spinner = $this.parent().find('.spinner');
        
        $spinner.addClass('is-active');
        $.post(
            ajaxurl,
            {
                action: 'kuteshop_delete_transients',
            },
            function (response) {
                $spinner.removeClass('is-active');
                $success.html($text.replace('%n', response));
            }
        );
    });
    
    $(document).on('click', '.ovic-field .update-database', function (e) {
        e.preventDefault();
        
        var $this    = $(this),
            $text    = $this.data('text-done'),
            $success = $this.parent().find('.ovic-text-success'),
            $spinner = $this.parent().find('.spinner');
        
        $spinner.addClass('is-active');
        $.post(
            ajaxurl,
            {
                action: 'ovic_update_database',
            },
            function (response) {
                $spinner.removeClass('is-active');
                $success.html($text.replace('%n', response));
            }
        );
    });
    
})(jQuery); // End of use strict