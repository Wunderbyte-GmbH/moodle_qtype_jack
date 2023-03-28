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
 * Strings for component 'qtype_jack', language 'en'
 *
 * @package    qtype_jack
 * @subpackage jack
 * @copyright  1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

$string['acceptedfiletypes'] = 'Accepted file types';
$string['acceptedfiletypes_help'] = 'Accepted file types can be restricted by entering a list of file extensions. If the field is left empty, then all file types are allowed.';
$string['allowattachments'] = 'Allow attachments';
$string['attachmentsoptional'] = 'Attachments are optional';
$string['attachmentsrequired'] = 'Require attachments';
$string['attachmentsrequired_help'] = 'This option specifies the minimum number of attachments required for a response to be considered gradable.';

$string['formateditor'] = 'HTML editor';
$string['formateditorfilepicker'] = 'HTML editor with file picker';
$string['formatmonospaced'] = 'Plain text, monospaced font';
$string['formatnoinline'] = 'No online text';
$string['formatplain'] = 'Plain text';

$string['graderinfo'] = 'Information for graders';
$string['graderinfoheader'] = 'Grader Information';

$string['jackoptions'] = 'Jack settings';

$string['mustattach'] = 'When "No online text" is selected, or responses are optional, you must allow at least one attachment.';
$string['mustrequire'] = 'When "No online text" is selected, or responses are optional, you must require at least one attachment.';
$string['mustrequirefewer'] = 'You cannot require more attachments than you allow.';

$string['nlines'] = '{$a} lines';
$string['nonexistentfiletypes'] = 'The following file types were not recognised: {$a}';

$string['pluginname'] = 'JACK';
$string['pluginname_help'] = 'In response to a question, the respondent may upload one or more files and/or enter text online. A response template may be provided. Responses must be graded manually.';
$string['pluginname_link'] = 'question/type/jack';
$string['pluginnameadding'] = 'Adding an jack question';
$string['pluginnameediting'] = 'Editing an jack question';
$string['pluginnamesummary'] = 'The jack question type allows a response as a file upload and/or online text. This will be graded automaticly in the background.';
$string['privacy:metadata'] = 'The jack question type plugin does not store any personal data.';

$string['responsefieldlines'] = 'Input box size';
$string['responseformat'] = 'Response format';
$string['responseoptions'] = 'Response Options';
$string['responserequired'] = 'Require text';
$string['responsenotrequired'] = 'Text input is optional';
$string['responseisrequired'] = 'Require the student to enter text';
$string['responsetemplate'] = 'Template: Input box';
$string['responsetemplateheader'] = 'Source code template';
$string['responsetemplate_help'] = 'Any text entered here will be displayed in the input box when a new attempt at the question starts. The input box is only visible to students when “Allow attachments” is set to “No”.';
$string['sourcecodetemplatefile'] = 'Template: File';
$string['sourcecodetemplatefile_help'] = "Additional files, such as source code templates, may be added. Download links for the files will then be displayed below the question text.";
$string['workwithsourcecodetemplatefile'] = 'Download and change the source code template';

$string['ruleset'] = 'Rule set';

$string['testdriver'] = 'Testdriver';
$string['jack:access'] = '';
$string['setlanguage'] = 'Set language';
