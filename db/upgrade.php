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
 * Jack question type upgrade code.
 *
 * @package    qtype_jack
 * @copyright  2022 Wunderbyte GmbH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Upgrade code for the jack question type.
 * @param int $oldversion the version we are upgrading from.
 */
function xmldb_qtype_jack_upgrade($oldversion) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2022112101) {

        // Define field lang to be added to qtype_jack_options.
        $table = new xmldb_table('qtype_jack_options');
        $field = new xmldb_field('lang', XMLDB_TYPE_CHAR, '255', null, null, null, null, 'filetypeslist');

        // Conditionally launch add field lang.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Jack savepoint reached.
        upgrade_plugin_savepoint(true, 2022112101, 'qtype', 'jack');
    }

    return true;
}
