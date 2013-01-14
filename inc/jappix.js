jQuery(document).ready( function () {

if (window.rcmail) {
    // Save login and password into JS session database at login (case of default auth)
    rcmail.addEventListener('init', function (evt) {
        if (rcmail.task == 'login') {
            if (location.search == "?_task=logout") {
                disconnectMini();
                resetDB();
            }
            document.getElementsByName('form')[0].onsubmit = function () {
                setDB('jappix-mini-login', 'xid', document.getElementById("rcmloginuser").value);
                setDB('jappix-mini-login', 'pwd', document.getElementById("rcmloginpwd").value);
                setDB('jappix-mini', 'dom', true);
            };
            jQuery('#jappix_mini').remove();
        }
    });
}

// Case insensitive jQuery expression (":Contains")
jQuery.expr[':'].Contains = function (a, i, m) {
    return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
};

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
}

});
