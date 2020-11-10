/*jslint browser: true*/
/*global jQuery */

$(document).ready(function() {
    $('.verticalExpand .header').on('click', function(e) {
        clickedElement = $(e.target);
        clickedElement.parent().toggleClass('expanded');
        if (clickedElement.closest('.no-expand', '.button').length === 0) {
            var caretContainer = $(this).find('span.header-caret');
            if (caretContainer.hasClass('fa')) {
                caretContainer.toggleClass('fa-chevron-down fa-chevron-right');
            } else {
                caretContainer.toggleClass('ui-icon-triangle-1-e ui-icon-triangle-1-s');
            }
            $(this).next().slideToggle('fast');
        }
    });
});
