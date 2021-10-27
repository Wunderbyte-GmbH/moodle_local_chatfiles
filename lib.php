<?php
// This file is part of Moodle - http://moodle.org/
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
 * Libary file for chatfiles
 *
 * @package    local_chatfiles
 * @copyright  2021 Wunderbyte GmbH
 * @author     Thomas Winkler
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * Serves chatfiles files.
 *
 * @param stdClass $course course object
 * @param stdClass $cm course module object
 * @param stdClass $context context object
 * @param string $filearea file area
 * @param array $args extra arguments
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool false if file not found, does not return if found - justsend the file
 */
function local_chatfiles_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    global $USER;
    require_login();
    if ($context->contextlevel != CONTEXT_SYSTEM) {
        return false;
    }
    $fs = \get_file_storage();
    $filename = array_pop($args);
    if (!$args) {
         $filepath = '/';
    } else {
         $filepath = '/'.implode('/', $args).'/';
    }
    $file = $fs->get_file(1, 'local_chatfiles', 'chat', 0, $filepath, $filename);

    $conversationid = $file->get_author();
    $members = \core_message\api::get_conversation_members($USER->id, $conversationid);
    if (!array_key_exists($USER->id, $members)) {
        return false;
    }
    send_stored_file($file, 86400, 0, $forcedownload, $options);
}
