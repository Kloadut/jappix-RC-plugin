jQuery(document).ready( function () {

installPopUp = function () {

    // Affect all mailto links of the DOM
    $('a[href^="mailto"]').each(function () {

        // Abstract "mailto:"
        var mail = $(this).attr('href').substr(7);

        // Check if the mail match with a JabberID loaded in the #jappix_mini window
        if ($('.jm_buddies [data-xid="' + mail + '"]', window.parent.document).size() > 0) {

            // Get usefull values
            var nick = $('.jm_buddies [data-xid="' + mail + '"]', window.parent.document).text();
            var hash = $('.jm_buddies [data-xid="' + mail + '"]', window.parent.document).attr('data-hash');
            var statut = '';

            // And statut with the span class
            if ($('.jm_buddies [data-xid="' + mail + '"] span.jm_available', window.parent.document).size() > 0) {
                var statut = 'available';
            } else if ($('.jm_buddies [data-xid="' + mail + '"] span.jm_away', window.parent.document).size() > 0) {
                var statut = 'away';
            } else if ($('.jm_buddies [data-xid="' + mail + '"] span.jm_xa', window.parent.document).size() > 0) {
                var statut = 'busy';
            } else {
                var statut = 'unavailable';
            }

            // Apply the tweaks
            $(this)
            .after($('<a href="#" title="Discuter avec '+ nick +' ('+ statut +')" onclick="window.parent.chatMini(\'chat\', \''+ mail +'\', \''+ nick +'\', \''+ hash +'\' );"><img src="plugins/jappix/inc/icons/chat-icon-'+ statut +'.png" alt="chat-icon"  height="11px" /></a>')
            .attr('class', $('.jm_buddies [data-xid="' + mail + '"]', window.parent.document)
            .attr('class')))
            .css('margin-right', '5px');
    }
    });
    //.hover(displayPopUp, hidePopUp);
}

});
