jQuery(document).ready(function($) {

    // 1. RUN HEALTH SCAN
    $("#wpsd-scan").on('click', function() {
        $("#wpsd-result").html("Scanning website...");

        $.post(ajaxurl, {
            action: "wpsd_run_scan",
            security: wpsd_vars.nonce // This is the required security token
        }, function(response) {
            $("#wpsd-result").html(
                "<b>Plugins:</b> " + response.plugins + "<br>" +
                "<b>WP Version:</b> " + response.wp + "<br>" +
                "<b>Theme:</b> " + response.theme + "<br>" +
                "<b>Posts:</b> " + response.posts + "<br>" +
                "<b>Database Size:</b> " + response.db
            );
        });
    });

    // 2. CLEAN CACHE / TEMP DATA
    $("#wpsd-clean").on('click', function() {
        $("#wpsd-result").html("Cleaning junk files...");

        $.post(ajaxurl, {
            action: "wpsd_cleanup",
            security: wpsd_vars.nonce // This is the required security token
        }, function(response) {
            $("#wpsd-result").html(
                "<b>Items cleaned:</b> " + response.cleaned + "<br>" +
                "<b>Remaining junk:</b> " + response.size
            );
        });
    });

    // 3. FIX ISSUES AUTOMATICALLY
    $("#wpsd-fix").on('click', function() {
        $("#wpsd-result").html("Fixing issues...");

        $.post(ajaxurl, {
            action: "wpsd_fix_issues",
            security: wpsd_vars.nonce // This is the required security token
        }, function(response) {
            $("#wpsd-result").html(response.message);
        });
    });

});