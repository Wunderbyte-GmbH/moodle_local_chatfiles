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

/*
    $filepath = $_FILES['myFile']['tmp_name'];
    $filesize = filesize($filepath);
    $fileinfo = finfo_open(FILEINFO_MIME_TYPE);
    $filetype = finfo_file($fileinfo, $filepath);
    if ($filesize === 0) {
        die("The file is empty.");
    }
    if ($filesize > 3145728) { // 3 MB (1 byte * 1024 * 1024 * 3 (for 3 MB))
        die("The file is too large");
    }
    $allowedtypes = [
       'image/png' => 'png',
       'image/jpeg' => 'jpg',
       'image/jpeg' => 'jpeg',
       'application/pdf' => 'pdf',
       'application/zip' => 'zip',

    ];
    if (!in_array($filetype, array_keys($allowedtypes))) {
        die("File not allowed.");
    }*/
    \core\antivirus\manager::scan_file($_FILES["file"]["tmp_name"], $_FILES["file"]["name"], true);



    $filename = $pathparts['filename'].'_'.time().'.'.$pathparts['extension'];
    $dlurl = new moodle_url('/pluginfile.php/1/local_chatfiles/chat/') . $filename . '?forcedownload=1';
    move_uploaded_file($_FILES['file']['tmp_name'], $tempdir . $filename);
}

echo json_encode(array("url" => $dlurl, "filename" => $filename));
