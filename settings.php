<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin administration pages are defined here.
 *
 * @package     local_chatfiles
 * @category    admin
 * @copyright   2021 Wunderbyte GmbH <info@wunderbyte.at>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    // phpcs:ignore Generic.CodeAnalysis.EmptyStatement.DetectedIf

    $settings = new admin_settingpage( 'local_chatfiles_settings', 'Chatfiles'); // We ommit the label, so that it does not show the heading.
    $ADMIN->add('localplugins', new admin_category('local_chatfiles', get_string('pluginname', 'local_chatfiles')));
    $ADMIN->add('localplugins', $settings);


    $settings->add(new admin_setting_filetypes('chatfiles/filetypes',
        new lang_string('acceptedfiletypes', 'local_chatfiles'),
        '', '', array('onlytypes' => array('archive', 'document', 'image'))));

    if (isset($CFG->maxbytes)) {
        $name = get_string('maximumsubmissionsize', 'local_chatfiles');

        $maxbytes = get_config('chatfiles', 'maxbytes');
        $element = new admin_setting_configselect('chatfiles/maxbytes',
                                                $name,
                                                '',
                                                $CFG->maxbytes,
                                                get_max_upload_sizes($CFG->maxbytes, 0, 0, $maxbytes));
        $settings->add($element);
    }
}
