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
 * Event observers.
 *
 * @package local_chatfiles
 * @copyright 2021 Wunderbyte Gmbh <info@wunderbyte.at>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_chatfiles;

defined('MOODLE_INTERNAL') || die;

/**
 * An event observer.
 * @copyright  2021 Wunderbyte GmbH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class observer {
    /**
     * Checks if message_send Event has files
     *
     * @param object $event
     */
    public static function event($event) {
        global $DB, $USER;
        $entry = (object)$event->get_data();
        if (($entry->eventname == '\core\event\message_sent') || ($entry->eventname == '\core\event\group_message_sent')) {
            $msg = $DB->get_record("messages", array("id" => $entry->objectid));
            if (strpos($msg->fullmessage, 'local_chatfiles') !== false) {
                preg_match_all('#<a\s.*?(?:href=[\'"](.*?)[\'"]).*?>#is', $msg->fullmessage, $matches);
                $urlparts  = explode('/', $matches[1][0]);
                $filename = $urlparts[count($urlparts) - 1];
                $fname = str_replace("?forcedownload=1", "", $filename);
                chatfiles::create_chatfile($msg->useridfrom, $msg->conversationid, $fname);
            }
        } else if ($entry->eventname == '\core\event\message_deleted') {
            $msg = $DB->get_record("messages", array("id" => $entry->other["messageid"]));
            if (strpos($msg->fullmessage, 'local_chatfiles') !== false && $msg->useridfrom == $USER->id) {
                preg_match_all('#<a\s.*?(?:href=[\'"](.*?)[\'"]).*?>#is', $msg->fullmessage, $matches);
                $urlparts  = explode('/', $matches[1][0]);
                $filename = $urlparts[count($urlparts) - 1];
                $fname = str_replace("?forcedownload=1", "", $filename);
                chatfiles::delete_chatfile($fname);
            }
        }
            return true;
    }
}

