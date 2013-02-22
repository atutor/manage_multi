/************************************************************************/
/* ATutor                                                               */
/************************************************************************/
/* Copyright (c) 2012                                                   */
/* Inclusive Design Institute                                           */
/* http://atutor.ca                                                     */
/*                                                                      */
/* This program is free software. You can redistribute it and/or        */
/* modify it under the terms of the GNU General Public License          */
/* as published by the Free Software Foundation.                        */
/************************************************************************/
// $Id: $

/*global jQuery, ATutor*/

ATutor = ATutor || {};
ATutor.mods = ATutor.mods || {};
ATutor.mods.manage_multi = ATutor.mods.manage_multi || {};

(function () {

    jQuery(document).ready(function () {
        var handleSummaryCheckbox = function () {
            var checkboxChildren = jQuery(".AT_subsites_row").find("input[type=checkbox]");
            var checkedCheckboxes = jQuery(".AT_subsites_row").find("input[type=checkbox]:checked");
            checkboxChildren.length === checkedCheckboxes.length ? 
                jQuery("#AT_upgrade_all").attr('checked', 'checked') : jQuery("#AT_upgrade_all").removeAttr('checked');
        };

        jQuery(".AT_subsites_row").click({callback: handleSummaryCheckbox}, ATutor.highlightTableRow);
        
        jQuery("#AT_upgrade_all").click(function (ev) {
            var allCheckboxes = jQuery(this).closest("table").find("input:checkbox");
            
            jQuery(this).is(':checked') ? allCheckboxes.attr('checked', 'checked') : allCheckboxes.removeAttr('checked');
        });
    });

})();