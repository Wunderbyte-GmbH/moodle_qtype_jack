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
 * jack question definition class.
 *
 * @package    qtype_jack
 * @subpackage jack
 * @copyright  2022 Wunderbyte GmbH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/question/type/questionbase.php');

/**
 * Represents an jack question.
 *
 * @copyright  2022 Wunderbyte GmbH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_jack_question extends question_with_responses {


    /** @var mixed */
    public $responseformat;

    /** @var int Indicates whether an inline response is required ('0') or optional ('1')  */
    public $responserequired;

    /** @var mixed */
    public $responsefieldlines;

    /** @var mixed */
    public $attachments;

    /** @var int The number of attachments required for a response to be complete. */
    public $attachmentsrequired;

    /** @var mixed */
    public $graderinfo;

    /** @var mixed */
    public $graderinfoformat;

    /** @var mixed */
    public $responsetemplate;

    /** @var mixed */
    public $responsetemplateformat;

    /** @var int Converted to an actual list of file types in get_filetypeslist **/
    public $filetypeslist;

    /** @var string */
    public $lang;

    /** @var int */
    public $reponserequired;

    /** @var int */
    public $attachmensrequired;

    /**
     * Make behaviour
     *
     * @param question_attempt $qa
     * @param string $preferredbehaviour
     * @return question_behaviour
     */
    public function make_behaviour(question_attempt $qa, $preferredbehaviour): question_behaviour {
        return question_engine::make_behaviour('manualgraded', $qa, $preferredbehaviour);
    }

    /**
     * Get format renderer
     *
     * @param moodle_page $page
     * @return render_base
     */
    public function get_format_renderer(moodle_page $page) {
        return $page->get_renderer('qtype_jack', 'format_' . $this->responseformat);
    }

    /**
     * Get expected data
     *
     * @return array
     */
    public function get_expected_data() {
        if ($this->responseformat == 'editorfilepicker') {
            $expecteddata = array('answer' => question_attempt::PARAM_RAW_FILES);
        } else {
            $expecteddata = array('answer' => PARAM_RAW);
        }
        $expecteddata['answerformat'] = PARAM_ALPHANUMEXT;
        if ($this->attachments != 0) {
            $expecteddata['attachments'] = question_attempt::PARAM_FILES;
        }
        return $expecteddata;
    }

    /**
     * Summarise response
     *
     * @param array $response
     * @return float|string|null
     */
    public function summarise_response(array $response) {
        if (isset($response['answer'])) {
            return question_utils::to_plain_text($response['answer'],
                    $response['answerformat'], array('para' => false));
        } else {
            return null;
        }
    }

    /**
     * Un summarise response
     *
     * @param string $summary
     * @return array
     */
    public function un_summarise_response(string $summary) {
        if (!empty($summary)) {
            return ['answer' => text_to_html($summary)];
        } else {
            return [];
        }
    }

    /**
     * Get correct response
     *
     * @return null
     */
    public function get_correct_response() {
        return null;
    }

    /**
     * Is complete response
     *
     * @param array $response
     * @return bool
     */
    public function is_complete_response(array $response) {
        // Determine if the given response has online text and attachments.
        $hasinlinetext = array_key_exists('answer', $response) && ($response['answer'] !== '');
        $hasattachments = array_key_exists('attachments', $response)
            && $response['attachments'] instanceof question_response_files;

        // Determine the number of attachments present.
        if ($hasattachments) {
            // Check the filetypes.
            $filetypesutil = new \core_form\filetypes_util();
            $whitelist = $this->get_filetypeslist();
            $wrongfiles = array();
            foreach ($response['attachments']->get_files() as $file) {
                if (!$filetypesutil->is_allowed_file_type($file->get_filename(), $whitelist)) {
                    $wrongfiles[] = $file->get_filename();
                }
            }
            if ($wrongfiles) { // At least one filetype is wrong.
                return false;
            }
            $attachcount = count($response['attachments']->get_files());
        } else {
            $attachcount = 0;
        }

        // Determine if we have /some/ content to be graded.
        $hascontent = $hasinlinetext || ($attachcount > 0);

        // Determine if we meet the optional requirements.
        $meetsinlinereq = $hasinlinetext || (!$this->responserequired) || ($this->responseformat == 'noinline');
        $meetsattachmentreq = ($attachcount >= $this->attachmentsrequired);

        // The response is complete iff all of our requirements are met.
        return $hascontent && $meetsinlinereq && $meetsattachmentreq;
    }

    /**
     * Is gradable response
     *
     * @param array $response
     * @return bool
     */
    public function is_gradable_response(array $response) {
        // Determine if the given response has online text and attachments.
        if (array_key_exists('answer', $response) && ($response['answer'] !== '')) {
            return true;
        } else if (array_key_exists('attachments', $response)
                && $response['attachments'] instanceof question_response_files) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Is same response
     *
     * @param array $prevresponse
     * @param array $newresponse
     * @return bool
     */
    public function is_same_response(array $prevresponse, array $newresponse) {
        if (array_key_exists('answer', $prevresponse) && $prevresponse['answer'] !== $this->responsetemplate) {
            $value1 = (string) $prevresponse['answer'];
        } else {
            $value1 = '';
        }
        if (array_key_exists('answer', $newresponse) && $newresponse['answer'] !== $this->responsetemplate) {
            $value2 = (string) $newresponse['answer'];
        } else {
            $value2 = '';
        }
        return $value1 === $value2 && ($this->attachments == 0 ||
                question_utils::arrays_same_at_key_missing_is_blank(
                $prevresponse, $newresponse, 'attachments'));
    }

    /**
     * Check file access
     *
     * @param question_attempt $qa
     * @param question_display_options $options
     * @param string $component
     * @param string $filearea
     * @param array $args
     * @param bool $forcedownload
     * @return bool
     */
    public function check_file_access($qa, $options, $component, $filearea, $args, $forcedownload) {
        if ($component == 'question' && $filearea == 'response_attachments') {
            // Response attachments visible if the question has them.
            return $this->attachments != 0;

        } else if ($component == 'question' && $filearea == 'response_answer') {
            // Response attachments visible if the question has them.
            return $this->responseformat === 'editorfilepicker';

        } else if ($component == 'qtype_jack' && $filearea == 'graderinfo') {
            return $options->manualcomment && $args[0] == $this->id;

        } else {
            return parent::check_file_access($qa, $options, $component,
                    $filearea, $args, $forcedownload);
        }
    }

    /**
     * Get filetypeslist
     *
     * @return array
     */
    public function get_filetypeslist() {
        switch ($this->filetypeslist) {
            case 0:
            return array('.java', '.jar');
            default:
            return array();
        }
    }
}
