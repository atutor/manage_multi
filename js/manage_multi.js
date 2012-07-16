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

/*global jQuery, ATutor */

ATutor = ATutor || {};
ATutor.mods = ATutor.mods || {};
ATutor.mods.manage_multi = ATutor.mods.manage_multi || {};

(function () {
    jQuery(document).ready(function () {
        jQuery(".AT_subsites_row").click(ATutor.highlightTableRow);
    });
})();