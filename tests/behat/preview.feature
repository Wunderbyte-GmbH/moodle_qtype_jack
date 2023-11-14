@qtype @qtype_jack
Feature: Preview jack questions
  As a teacher
  In order to check my jack questions will work for students
  I need to preview them

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email               |
      | teacher1 | T1        | Teacher1 | teacher1@moodle.com |
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
    And the following "user private files" exist:
      | user     | filepath                                       | filename     |
      | teacher1 | question/type/jack/tests/fixtures/test.jar     | test.jar     |
      | teacher1 | question/type/jack/tests/fixtures/template.jar | template.jar |
    Given I log in as "teacher1"
    And I am on "Course 1" course homepage

  @javascript
  Scenario: Preview an jack-001 question and submit a partially correct response.
    When I am on the "jack-001" "core_question > preview" page logged in as teacher1
    And I expand all fieldsets
    And I set the field "How questions behave" to "Immediate feedback"
    And I press "Start again with these options"
    And I should see "Please write a story about a frog."
    And I set the field with xpath "//div[@class='answer']//textarea[contains(@name, '1_answer')]" to "A story about frogs."
    And I press "Submit and finish"
    And I should see "I hope your story had a beginning, a middle and an end."

  @javascript @_file_upload
  Scenario: Preview an jack-002 question and submit a correct response.
    When I am on the "jack-002" "core_question > preview" page logged in as teacher1
    And I expand all fieldsets
    And I set the field "How questions behave" to "Immediate feedback"
    And I press "Start again with these options"
    And I should see "Please write a story about a frog."
    And I should see "You can drag and drop files here to add them."
    And I click on "Add..." "button"
    And I click on "Private files" "link" in the ".fp-repo-area" "css_element"
    And I click on "test.jar" "link"
    And I click on "Select this file" "button"
    # Wait for the page to "settle".
    And I wait until the page is ready
    And I press "Submit and finish"
    And I should see "I hope your story had a beginning, a middle and an end."

  @javascript
  Scenario: Preview an jack-003 question and submit a partially correct response.
    When I am on the "jack-003" "core_question > preview" page logged in as teacher1
    And I expand all fieldsets
    And I set the field "How questions behave" to "Immediate feedback"
    And I press "Start again with these options"
    And I should see "Please write a story about a frog."
    And I set the field with xpath "//div[@class='answer']//textarea[contains(@name, '1_answer')]" to "This is a story about the frog."
    And I press "Submit and finish"
    And I should see "I hope your story had a beginning, a middle and an end."
