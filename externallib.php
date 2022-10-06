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
 * The externallib of the jack qtype.
 *
 * Here you'll find the methods you can directly access through
 * the webservice.
 *
 * @package    qtype
 * @subpackage jack
 * @author     Benjamin Wolf <support@eledia.de>
 * @copyright  2021 eLeDia GmbH
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;
require_once($CFG->libdir.'/externallib.php');
require_once($CFG->libdir.'/questionlib.php');
require_once($CFG->dirroot.'/question/engine/datalib.php');

class jack extends external_api {

    /**
     * Parameterdefinition for method "get_next_jack_question"
     *
     * @return external_function_parameters {object} external_function_parameters
     */
    public static function get_next_jack_question_parameters() {
        return new external_function_parameters(
            array(
            )
        );
    }

    /**
     * Method to get next open jack question.
     *
     * @param {array} params
     * @return array {array} array of question info data.
     * @throws dml_exception
     * @throws invalid_parameter_exception
     * @throws required_capability_exception
     */
    public static function get_next_jack_question() {

        $context = context_system::instance();
        require_capability('qtype/jack:access', $context);

        $result = array();
        $next = self::get_next_question();

        if (!$next) {
            return $result;
        }
        $result = (array) $next;

        return $result;
    }

    /**
     * Returndefinition for method "get_next_jack_question"
     *
     * @return external_single_structure {object} external_value external_value
     */
    public static function get_next_jack_question_returns() {
        return new external_single_structure(
            array(
                'attemptid'  => new external_value(PARAM_INT, 'attemptid', VALUE_OPTIONAL),
                'attachments' => new external_files('attachments', VALUE_OPTIONAL),
                'submission' => new external_value(PARAM_RAW, 'submission', VALUE_OPTIONAL),
                'testdriver' => new external_value(PARAM_RAW, 'testdriver', VALUE_OPTIONAL),
                'ruleset'    => new external_value(PARAM_RAW, 'ruleset', VALUE_OPTIONAL),
            )
        );
    }

    /**
     * Helper function for get_next_jack_question function.
     *
     * @return bool|stdClass {object} object of question info data.
     * @throws dml_exception
     */
    public static function get_next_question() {
        global $DB;

        $jack_questions = $DB->get_records('question', array('qtype' => 'jack'));

        foreach ($jack_questions as $jack_question) {
            $jack_attempts = $DB->get_records('question_attempts', array('questionid' => $jack_question->id));
            foreach ($jack_attempts as $jack_attempt) {

                // Check for preview attempt.
                $question_usage = $DB->get_record('question_usages', array('id' => $jack_attempt->questionusageid));
                if ($question_usage->component == 'core_question_preview') {
                    continue; // We dont want to grade previews here.
                }

                // Check for steps.
                $last_step_sql = "SELECT MAX(sequencenumber) AS sequence
                FROM {question_attempt_steps}
                WHERE questionattemptid = $jack_attempt->id";
                $last_sequence = $DB->get_records_sql($last_step_sql);
                $last_step = $DB->get_record('question_attempt_steps',
                    array('questionattemptid' => $jack_attempt->id,
                    'sequencenumber' => current($last_sequence)->sequence));

                if ($last_step->state == 'needsgrading') {
                    // Found one, collect data and return it as result.
                    $data = new stdClass();
                    $data->attemptid = $jack_attempt->id;
                    // Get submitted data.
                    $completion_step = $DB->get_record('question_attempt_steps',
                        array('questionattemptid' => $jack_attempt->id, 'state' => 'complete'));
                    $options = $DB->get_record('qtype_jack_options',
                        array('questionid' => $jack_question->id));
                    if ($options->attachments) {
                        $data->attachments = array();
                        $dm = new question_engine_data_mapper();
                        $qa = $dm->load_question_attempt($jack_attempt->id);
                        $files = $qa->get_last_qt_files('attachments', $question_usage->contextid);
                        foreach ($files as $file) {
                            $data->attachments[] = array(
                                'filename' => $file->get_filename(),
                                'filepath' => $file->get_filepath(),
                                'filesize' => $file->get_filesize(),
                                'fileurl' => preg_replace('/pluginfile.php/',
                                    'webservice/pluginfile.php', $qa->get_response_file_url($file)),
                                'timemodified' => $file->get_timemodified(),
                                'mimetype' => $file->get_mimetype()
                            );
                        }
                    } else {
                        $submission = $DB->get_record('question_attempt_step_data',
                            array('attemptstepid' => $completion_step->id, 'name' => 'answer'));
                        $data->submission = $submission->value;
                    }
                    // Get jack data for this question.
                    $question_jack_settings = $DB->get_record('question_jack',
                        array('questionid' => $jack_question->id));
                    $data->testdriver = $question_jack_settings->testdriver;
                    $data->ruleset = $question_jack_settings->ruleset;
                    return $data;
                }
            }
        }
        // No open attempts found.
        return false;
    }

    /**
     * Parameterdefinition for method "set_jack_question_result"
     *
     * @return external_function_parameters {object} external_function_parameters
     */
    public static function set_jack_question_result_parameters() {
        return new external_function_parameters(
            array(
                'attemptid' => new external_value(PARAM_INT, 'attempt id'),
                'grade' => new external_value(PARAM_TEXT, 'grade for this attempt'),
                'feedback' => new external_value(PARAM_RAW, 'feedback for the attempt'),
            )
        );
    }

    /**
     * Method to set the result for an jack question.
     *
     * @param {array} params
     * @return array {array} array of question info data.
     * @throws dml_exception
     * @throws invalid_parameter_exception
     * @throws required_capability_exception
     */
    public static function set_jack_question_result($attemptid, $grade, $feedback) {
        global $DB, $CFG;

        $context = context_system::instance();
        require_capability('qtype/jack:access', $context);
        require_once($CFG->dirroot.'/question/engine/lib.php');

        // Parameter validation.
        self::validate_parameters(
            self::set_jack_question_result_parameters(),
            array(
                'attemptid' => $attemptid,
                'grade' => $grade,
                'feedback' => $feedback
            )
        );

        try {
            $question_attempt = $DB->get_record('question_attempts', array('id' => $attemptid));
            $question_attempt_steps = $DB->get_record('question_attempt_steps',
                array('questionattemptid' => $attemptid, 'state' => 'needsgrading'));
            $data =  array('answer' => '');
            $step = new question_attempt_step_read_only($data, time(), $question_attempt_steps->userid);
            $question_usage = $DB->get_record('question_usages', array('id' => $question_attempt->questionusageid));
            $context_rec = $DB->get_record('context', array('id' => $question_usage->contextid));
            $cm = $DB->get_record('course_modules', array('id' => $context_rec->instanceid));
            $context = context_module::instance($cm->id);
            $questtion_file = new question_file_loader($step, 'bf_comment', $feedback, $context->id);
            $submit['-comment'] = $questtion_file->get_question_file_saver();
            $submit['-commentformat'] = 1;
            $submit['-mark'] = ($grade/100)*$question_attempt->maxmark;
            $submit['-maxmark'] = $question_attempt->maxmark;

            $quba = question_engine::load_questions_usage_by_activity($question_attempt->questionusageid);
            $quba->process_action($question_attempt->slot, $submit);
            $transaction = $DB->start_delegated_transaction();
            question_engine::save_questions_usage_by_activity($quba);
            $transaction->allow_commit();
        } catch (Exception $ex) {
            // Define failed message.
            $output['result'] = $ex->getMessage();
            $output['success'] = false;
            return $output;
        }

        try {
            require_once($CFG->dirroot.'/mod/quiz/locallib.php');
            $quiz = $DB->get_record('quiz', array('id' => $cm->instance));
            quiz_update_all_attempt_sumgrades($quiz);
            quiz_update_all_final_grades($quiz);
            quiz_update_grades($quiz);
        } catch (Exception $ex) {
            // Define failed message.
            $output['result'] = $ex->getMessage();
            $output['success'] = false;
            return $output;
        }

        // Define success message.
        return array('success' => true, 'result' => 'Question result set.');
    }

    /**
     * Returndefinition for method "set_jack_question_result"
     *
     * @return external_single_structure {object} external_value external_value
     */
    public static function set_jack_question_result_returns() {
        return new external_single_structure(
            array(
                'success'   => new external_value(PARAM_BOOL, 'Return success of operation true or false'),
                'result'    => new external_value(PARAM_RAW, 'Return message'),
            )
        );
    }
}
