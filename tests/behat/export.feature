@qtype @qtype_jack
Feature: Test exporting jack questions
  As a teacher
  In order to be able to reuse my jack questions
  I need to export them

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
    And the following "question categories" exist:
      | contextlevel | reference | name           |
      | Course       | C1        | Test questions |
    And the following "questions" exist:
      | questioncategory | qtype | name     | template         | testdriver | ruleset |
      | Test questions   | jack  | jack-001 | editor           | x          | y       |
      | Test questions   | jack  | jack-002 | editorfilepicker | x          | y       |
      | Test questions   | jack  | jack-003 | plain            | x          | y       |

  @javascript
  Scenario: Export 3 jack questions
    When I am on the "Course 1" "core_question > course question export" page logged in as teacher1
    ## And I set the field "jump" to "Export"
    And I set the field "id_format_xml" to "1"
    And I press "Export questions to file"
    Then following "click here" should download between "2600" and "3500" bytes
    # If the download step is the last in the scenario then we can sometimes run
    # into the situation where the download page causes a http redirect but behat
    # has already conducted its reset (generating an error). By putting a logout
    # step we avoid behat doing the reset until we are off that page.
    And I log out
