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
 * Unit tests for the jack question type class.
 *
 * @package    qtype_jack
 * @subpackage jack
 * @copyright  2010 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace qtype_jack;

use advanced_testcase;
use qtype_jack;
use stdClass;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once($CFG->dirroot . '/question/type/jack/questiontype.php');


/**
 * Unit tests for the jack question type class.
 *
 * @copyright  2010 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @covers     \qtype_jack_qyestiontype
 */
class questiontype_test extends advanced_testcase {
    /**
     * Question type
     *
     * @var [object]
     */
    protected $qtype;

    /**
     * Setup
     *
     * @return void
     */
    protected function setUp(): void {
        $this->qtype = new qtype_jack();
    }

    /**
     * Tear down
     *
     * @return void
     */
    protected function tearDown(): void {
        $this->qtype = null;
    }

    /**
     * Get test question data
     *
     * @return stdClass
     */
    protected function get_test_question_data() {
        $q = new stdClass();
        $q->id = 1;

        return $q;
    }

    /**
     * Test of qtype->name
     *
     * @return void
     */
    public function test_name() {
        $this->assertEquals($this->qtype->name(), 'jack');
    }

    /**
     * Test of qtype->can_analyse_responses
     *
     * @return void
     */
    public function test_can_analyse_responses() {
        $this->assertFalse($this->qtype->can_analyse_responses());
    }

    /**
     * Test of qtype->get_random_guess_score
     *
     * @return void
     */
    public function test_get_random_guess_score() {
        $q = $this->get_test_question_data();
        $this->assertEquals(0, $this->qtype->get_random_guess_score($q));
    }

    /**
     * Test of qtype->get_possible_responses
     *
     * @return void
     */
    public function test_get_possible_responses() {
        $q = $this->get_test_question_data();
        $this->assertEquals(array(), $this->qtype->get_possible_responses($q));
    }
}
