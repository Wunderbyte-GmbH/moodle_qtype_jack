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
 * Defines the editing form for the jack question type.
 *
 * @package    qtype_jack
 * @copyright  2007 Jamie Pratt me@jamiep.org
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/question/type/jack/questiontype.php');
require_once($CFG->dirroot.'/question/type/jack/lib.php');

/**
 * jack question type editing form.
 *
 * @copyright  2007 Jamie Pratt me@jamiep.org
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_jack_edit_form extends question_edit_form {

    /**
     * Defnitions inner
     *
     * @param object $mform
     * @return void
     */
    protected function definition_inner($mform) {

        global $CFG;

        /** @var qtype_jack $qtype */
        $qtype = question_bank::get_qtype('jack');

        // Get the working translations from the list of translations.
        $alltranslations = get_string_manager()->get_list_of_translations();
        $translations = [];
        foreach ($alltranslations as $key => $value) {
            if (in_array($key, SUPPORTED_LANGUAGES)) {
                $translations[$key] = $value;
            }
        }

        $mform->addElement('header', 'jackoptions', get_string('jackoptions', 'qtype_jack'));
        $mform->setExpanded('jackoptions');

        $mform->addElement('select', 'lang', get_string('setlanguage', 'qtype_jack'),
            $translations, []);
        $lang = $CFG->lang === 'de' ? 'de' : 'en';
        $mform->setDefault('lang', $lang);

        $mform->addElement('textarea', 'testdriver', get_string('testdriver', 'qtype_jack'),
            array('rows' => 10, 'cols' => 100));

        $mform->addElement('textarea', 'ruleset', get_string('ruleset', 'qtype_jack'),
            array('rows' => 10, 'cols' => 100));

        $mform->addElement('header', 'responseoptions', get_string('responseoptions', 'qtype_jack'));
        $mform->setExpanded('responseoptions');

        $mform->addElement('hidden', 'responseformat', 'plain');
        $mform->setType('responseformat', PARAM_RAW);

        $mform->addElement('hidden', 'responserequired', 1);
        $mform->setType('responserequired', PARAM_RAW);

        $mform->addElement('select', 'responsefieldlines',
                get_string('responsefieldlines', 'qtype_jack'), $qtype->response_sizes());
        $mform->setDefault('responsefieldlines', 15);
        $mform->disabledIf('responsefieldlines', 'attachments', 'neq', 0);

        $mform->addElement('select', 'attachments',
                get_string('allowattachments', 'qtype_jack'), $qtype->attachment_options());
        $mform->setDefault('attachments', 0);

        $mform->addElement('select', 'filetypeslist',
                           get_string('acceptedfiletypes', 'qtype_jack'),
                           $qtype->filetypeslist_options());
        $mform->setDefault('filetypeslist', 0);
        $mform->disabledIf('filetypeslist', 'attachments', 'eq', 0);

        $mform->addElement('header', 'responsetemplateheader', get_string('responsetemplateheader', 'qtype_jack'));
        $mform->setExpanded('responsetemplateheader');
        $maxbytes = get_config('core', 'maxbytes');
        $mform->addElement(
            'filemanager',
            'responsefiletemplate',
            get_string('sourcecodetemplatefile', 'qtype_jack'),
            null,
            [
                'subdirs' => 0,
                'maxbytes' => $maxbytes,
                'areamaxbytes' => 10485760,
                'maxfiles' => 1,
                'accepted_types' => ['.jar', '.java'],
                'return_types' => FILE_INTERNAL | FILE_EXTERNAL,
            ]
        );

        $types = $qtype->filetypeslist_options();

        $mform->addElement('textarea', 'responsetemplate', get_string('responsetemplate', 'qtype_jack'),
                array('rows' => 10, 'cols' => 100));
        $mform->addHelpButton('responsetemplate', 'responsetemplate', 'qtype_jack');

        $mform->addElement('header', 'graderinfoheader', get_string('graderinfoheader', 'qtype_jack'));
        $mform->setExpanded('graderinfoheader');
        $mform->addElement('editor', 'graderinfo', get_string('graderinfo', 'qtype_jack'),
                array('rows' => 10), $this->editoroptions);
    }

    /**
     * Data preprocessing
     *
     * @param object $question
     * @return object
     */
    protected function data_preprocessing($question) {

        global $PAGE;

        $question = parent::data_preprocessing($question);

        if (empty($question->options)) {
            return $question;
        }

        $question->responseformat = $question->options->responseformat;
        $question->responserequired = $question->options->responserequired;
        $question->responsefieldlines = $question->options->responsefieldlines;
        $question->attachments = $question->options->attachments;
        $question->attachmentsrequired = $question->options->attachmentsrequired;
        $question->filetypeslist = $question->options->filetypeslist;
        $question->lang = $question->options->lang ?? '';

        // Get an unused draft itemid which will be used for this form.
        $draftid = file_get_submitted_draft_itemid('responsefiletemplate');

        // Copy the existing files which were previously uploaded
        // into the draft area used by this form.
        file_prepare_draft_area(
            $draftid,
            $this->context->id,
            'qtype_jack',
            'responsefiletemplate',
            $question->id,
            $this->fileoptions,
        );
        $question->responsefiletemplate = $draftid;

        $draftid = file_get_submitted_draft_itemid('graderinfo');
        $question->graderinfo = array();
        $question->graderinfo['text'] = file_prepare_draft_area(
            $draftid,           // Draftid.
            $this->context->id, // Context.
            'qtype_jack',      // Component.
            'graderinfo',       // Filarea.
            !empty($question->id) ? (int) $question->id : null, // Itemid.
            $this->fileoptions, // Options.
            $question->options->graderinfo // Text.
        );
        $question->graderinfo['format'] = $question->options->graderinfoformat;
        $question->graderinfo['itemid'] = $draftid;

        $question->responsetemplate = $question->options->responsetemplate;

        return $question;
    }

    /**
     * Qtype
     *
     * @return string
     */
    public function qtype() {
        return 'jack';
    }
}
