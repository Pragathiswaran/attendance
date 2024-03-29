<?php

// This file is part of Moodle Course Rollover Plugin
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package     local_attendance
 * @author      Prgathiswaran
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @var stdClass $plugin
 */

function local_attendance_extend_navigation(global_navigation $navigation)
{
    // Check if the current user has the capability to configure the site
    if (!has_capability('moodle/site:config', context_system::instance())) {
        return; // If the user doesn't have the capability, exit the function
    }

    // Add a new node to the global navigation menu
    $main_node = $navigation->add(
       'Reports', // Node name
        new moodle_url('/local/attendance/manage.php/'), // Node URL
        navigation_node::TYPE_CUSTOM, // Node type
        'Report', // Node display text
        null, // Node key
        new pix_icon('i/report', "report"), // Icon
        null // Node sorting order
    );

    // Set properties for the newly added node
    $main_node->nodetype = 1;
    $main_node->collapse = false;
    $main_node->forceopen = true;
    $main_node->isexpandable = false;
    $main_node->showinflatnavigation = true;
}

//this commit is only for resolveing error
//this commit is only for resolveing error
//Added by PrgathiSwaran   