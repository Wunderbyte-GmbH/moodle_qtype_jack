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
 * Function definition for the qtype_jack functions.
 *
 * @package    qtype_jack
 * @subpackage jack
 * @author     Benjamin Wolf <support@eledia.de>
 * @copyright  2021 eLeDia GmbH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = array(
    'qtype_jack_get_next_jack_question' => array(
        'classname' => 'qtype_jack_external',
        'methodname' => 'get_next_jackquestion',
        'classpath' => 'question/type/jack/externallib.php',
        'description' => 'provides the next jack question which needs grading',
        'type' => 'read',
        'capabilities' => 'qtype/jack:access',
        'services' => array(
            'qtype_jack_external'
        )
    ),
    'qtype_jack_set_jack_question_result' => array(
        'classname' => 'qtype_jack_external',
        'methodname' => 'set_jackquestion_result',
        'classpath' => 'question/type/jack/externallib.php',
        'description' => 'set the results for one jack question',
        'type' => 'read',
        'capabilities' => 'qtype/jack:access',
        'services' => array(
            'qtype_jack_external'
        )
    )
);

$services = array(
    'qtype Jack external' => array(
        'functions' => array (
            'qtype_jack_get_next_jack_question',
            'qtype_jack_set_jack_question_result'
        ),
        'restrictedusers' => 0,
        'enabled' => 1,
        'shortname' => 'qtype_jack_external',
        'downloadfiles' => 1,    // Allow file downloads.
        'uploadfiles'  => 1,      // Allow file uploads.
    )
);
