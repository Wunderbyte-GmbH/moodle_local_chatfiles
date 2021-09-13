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
//

/**
 * This class is used to override renderers. It has to be called from the config file in used theme.
 */
class local_chatfiles_renderer_factory extends theme_overridden_renderer_factory {
    public function __construct(theme_config $theme) {
        parent::__construct($theme);
        array_unshift($this->prefixes, 'local_chatfiles');
    }




}