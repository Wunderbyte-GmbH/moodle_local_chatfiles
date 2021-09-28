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
 * @package local_chatfiles
 * @category external
 * @copyright 2021 Wunderbyte Gmbh <info@wunderbyte.at>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
define('AJAX_SCRIPT', true);

require_once('../../config.php');

require_login();
require_sesskey();


$tempdir = make_temp_directory('chatfiles/');


if ( 0 < $_FILES['file']['error'] ) {
    echo 'Error: ' . $_FILES['file']['error'] . '<br>';
} else {
    $pathparts = pathinfo($_FILES["file"]["name"]);
    \core\antivirus\manager::scan_file($_FILES["file"]["tmp_name"], $_FILES["file"]["name"], true);
    $filename = $pathparts['filename'].'_'.time().'.'.$pathparts['extension'];
    $dlurl = new moodle_url('/pluginfile.php/1/local_chatfiles/chat/') . $filename . '?forcedownload=1';
    move_uploaded_file($_FILES['file']['tmp_name'], $tempdir . $filename);
}

echo json_encode(array("url" => $dlurl, "filename" => $filename));
