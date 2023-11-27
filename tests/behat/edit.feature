@qtype @qtype_jack
Feature: Test editing an jack question
  As a teacher
  In order to be able to update my jack question
  I need to edit them

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
      | questioncategory | qtype | name    | template         | testdriver | ruleset |
      | Test questions   | jack | jack-001 | editor           | x          | y       |
      | Test questions   | jack | jack-002 | editorfilepicker | x          | y       |
      | Test questions   | jack | jack-003 | plain            | x          | y       |

  Scenario: Edit an jack question
    When I log in as "teacher1"
    And I am on "Course 1" course homepage
    And I navigate to "Question bank" in current page administration
    And I choose "Edit question" action for "jack-001" in the question bank
    And I set the following fields to these values:
      | Question name | |
    And I press "id_submitbutton"
    Then I should see "You must supply a value here."
    When I set the following fields to these values:
      | Question name   | Edited jack-001 name |
    And I press "id_submitbutton"
    Then I should see "Edited jack-001 name"
