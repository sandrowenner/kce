jQuery(function($){
    $(document).off('click.kce-accordion').on('click.kce-accordion', '.kce-accordion-toggle', function(e){
        var $btn = $(this);
        var $content = $btn.next('.kce-accordion-content');
        var expanded = $btn.attr('aria-expanded') === 'true';
        if (expanded) {
            $btn.attr('aria-expanded', 'false');
        } else {
            $btn.attr('aria-expanded', 'true');
        }
    });
});