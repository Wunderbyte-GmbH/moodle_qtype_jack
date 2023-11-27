@qtype @qtype_jack
Feature: Test importing jack questions
  As a teacher
  In order to reuse jack questions
  I need to import them

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email                |
      | teacher1 | T1        | Teacher1 | teacher1@example.com |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1        | 0        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |

  @javascript @_file_upload
  Scenario: An import of JACK questions from xml file
    Given I am on the "Course 1" "core_question > course question import" page logged in as teacher1
    ## When I navigate to "Question bank" in current page administration
    ## And I set the field "jump" to "Import"
    And I set the field "id_format_xml" to "1"
    When I upload "question/type/jack/tests/fixtures/testquestion.moodle.xml" file to "Import" filemanager
    And I press "id_submitbutton"
    Then I should see "Parsing questions from import file."
    And I should see "Importing 1 questions from file"
    And I should see "Write an jack with 500 words."
    And I press "Continue"
    And I should see "jack-001"
