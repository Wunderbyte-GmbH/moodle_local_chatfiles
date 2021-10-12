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
global $CFG, $USER;
require_login();
require_sesskey();


$tempdir = make_temp_directory('chatfiles/');
$tousername = $_POST['name'];
$username = $USER->firstname. " " .$USER->lastname;
if ($username == $tousername) {
    echo json_encode(array("error" => get_string('error:sameuser', 'local_chatfiles')));
    die();
}
if ( 0 < $_FILES['file']['error'] ) {
    echo 'Error: ' . $_FILES['file']['error'] . '<br>';
} else {
    $pathparts = pathinfo($_FILES["file"]["name"]);


    $filepath = $_FILES['file']['tmp_name'];
    $filesize = filesize($filepath);
    $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
    $filetype = finfo_file($fileinfo, $filepath);
  

    $filetypes = get_config('chatfiles', 'filetypes');
    $util = new \core_form\filetypes_util();
    $sets = $util->normalize_file_types($filetypes);
    $maxbytes = get_config('chatfiles', 'maxbytes');
    if (!$maxbytes) {
        $maxbytes = $CFG->maxbytes;
    }

    if ($filesize === 0) {
        echo json_encode(array("error" => get_string('error:zero', 'local_chatfiles')));
        die();
    }
    if ($filesize > $maxbytes) { 
        echo json_encode(array("error" => get_string('error:filesize', 'local_chatfiles')));
        die();
    }

    $tmpfilename = "tmp" . mimeinfo_from_type('extension', $filetype);
    if(!file_extension_in_typegroup($tmpfilename, $sets, true)) {
        echo json_encode(array("url" => '', "filename" => '', "error" => get_string('error:extension', 'local_chatfiles')));
        die();
    }
       
    \core\antivirus\manager::scan_file($_FILES["file"]["tmp_name"], $_FILES["file"]["name"], true);



    $filename = $pathparts['filename'].'_'.time().'.'.$pathparts['extension'];
    $dlurl = new moodle_url('/pluginfile.php/1/local_chatfiles/chat/') . $filename . '?forcedownload=1';
    move_uploaded_file($_FILES['file']['tmp_name'], $tempdir . $filename);
    $event = \core\event\message_sent::create(array(
        'objectid' => 3,
        'userid' => 1,
        'context'  => context_system::instance(),
        'relateduserid' => 2,
        'other' => array(
            'courseid' => 4
        )
    ));
    $event->trigger();
}

echo json_encode(array("url" => $dlurl, "filename" => $filename));
