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
 * The main class for the chatfiles plugin
 * 
 * @package local_chatfiles
 * @copyright 2021 Wunderbyte Gmbh <info@wunderbyte.at>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_chatfiles;

defined('MOODLE_INTERNAL') || die();

global $CFG;


/**
 * Class chatfiles
 *
 * @package local_chatfiles
 */
class chatfiles {

    /**
     * Not sure we need a constructor.
     */
    public function __construct() {
    }
    /**
     * Copy File from temporary Folder to every private user Folder
     */
    public static function create_chatfile($userid, $conversationid, $filename) {
        global $CFG;
        $tempfile = $CFG->dataroot . '/temp/chatfiles/' . $filename;
        $fs = get_file_storage();
        $context = \context_system::instance();
        $file = array('contextid' => $context->id, 'component' => 'local_chatfiles', 'filearea' => 'chat',
        'itemid' => 0, 'filepath' => '/', 'filename' => $filename, 'author' => $conversationid);
        $fs->create_file_from_pathname($file, $tempfile);
        unlink($tempfile);
    }
    /**
     * Delete File from temporary Folder to every private user Folder
     */
    public static function delete_chatfile($filename) {
        $fs = get_file_storage();
        $context = \context_system::instance();
        $file = $fs->get_file($context->id, 'local_chatfiles', 'chat', 0, '/', $filename);
        if ($file) {
            $file->delete();
        }
    }
}
