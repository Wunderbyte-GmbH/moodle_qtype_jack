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
 * Provides the information to backup jack questions
 *
 * @package    moodlecore
 * @subpackage backup-moodle2
 * @copyright  2010 onwards Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class backup_qtype_jack_plugin extends backup_qtype_plugin {

    /**
     * Returns the qtype information to attach to question element
     */
    protected function define_question_plugin_structure() {

        // Define the virtual plugin element with the condition to fulfill.
        $plugin = $this->get_plugin_element(null, '../../qtype', 'jack');

        // Create one standard named plugin element (the visible container).
        $pluginwrapper = new backup_nested_element($this->get_recommended_name());

        // Connect the visible container ASAP.
        $plugin->add_child($pluginwrapper);

        // Now create the qtype own structures.
        $jack = new backup_nested_element('jack', array('id'), array(
                'responseformat', 'responserequired', 'responsefieldlines',
                'attachments', 'attachmentsrequired', 'graderinfo',
                'graderinfoformat', 'responsetemplate', 'responsetemplateformat', 'filetypeslist',
            'testdriver', 'ruleset'));

        // Now the own qtype tree.
        $pluginwrapper->add_child($jack);

        // Set source to populate the data.
        $jack->set_source_sql("SELECT qo.id, qo.responseformat, qo.responserequired, qo.responsefieldlines,
                qo.attachments, qo.attachmentsrequired, qo.graderinfo, qo.graderinfoformat, qo.responsetemplate,
                qo.responsetemplateformat, qo.filetypeslist, q.testdriver, q.ruleset
               FROM {qtype_jack_options} qo, {question_jack} q
              WHERE qo.questionid = ?
              AND q.questionid = ?",
            array(backup::VAR_PARENTID, backup::VAR_PARENTID));

        // Don't need to annotate ids nor files.

        return $plugin;
    }

    /**
     * Get qtype fileareas
     *
     * Returns one array with filearea => mappingname elements for the qtype
     * Used by get_components_and_fileareas to know about all the qtype
     * files to be processed both in backup and restore.
     *
     * @return array
     */
    public static function get_qtype_fileareas() {
        return array(
            'graderinfo' => 'question_created',
        );
    }
}
