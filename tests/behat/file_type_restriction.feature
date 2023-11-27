@qtype @qtype_jack
Feature: In a jack question, limit submittable file types
In order to constrain student submissions for marking
As a teacher
I need to limit the submittable file types

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email                |
      | teacher1 | Teacher   | 1        | teacher1@example.com |
      | student1 | Student   | 1        | student0@example.com |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1        | 0        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student        |
    And the following "question categories" exist:
      | contextlevel | reference | name           |
      | Course       | C1        | Test questions |
    And the following "questions" exist:
      | questioncategory | qtype       | name  | questiontext    | defaultmark | attachments | filetypeslist |
      | Test questions   | jack        | TF1   | First question  | 20          | 1           | 0             |
    And the following "activities" exist:
      | activity   | name   | intro              | course | idnumber | grade |
      | quiz       | Quiz 1 | Quiz 1 description | C1     | quiz1    | 20    |
    And quiz "Quiz 1" contains the following questions:
      | question | page |
      | TF1      | 1    |

  @javascript @_file_upload
  Scenario: Preview the jack question and submit a response with a incorrect filetype txt
    Given the following "user private files" exist:
      | user     | filepath                     | filename  |
      | student1 | lib/tests/fixtures/empty.txt | empty.txt |
    When I am on the "Quiz 1" "mod_quiz > View" page logged in as "student1"
    And I press "Attempt quiz"
    And I should see "First question"
    And I should see "You can drag and drop files here to add them."
    And I click on "Add..." "button"
    And I click on "Private files" "link" in the ".fp-repo-area" "css_element"
    Then I should not see "empty.txt"
    And I should see "No files available"

  @javascript @_file_upload
  Scenario: Preview the jack question and try to submit a response with an incorrect filetype csv
    Given the following "user private files" exist:
      | user     | filepath                            | filename         |
      | student1 | lib/tests/fixtures/upload_users.csv | upload_users.csv |
    When I am on the "Quiz 1" "mod_quiz > View" page logged in as "student1"
    And I press "Attempt quiz"
    And I should see "First question"
    And I should see "You can drag and drop files here to add them."
    And I click on "Add..." "button"
    And I click on "Private files" "link" in the ".fp-repo-area" "css_element"
    Then I should see "No files available"

  @javascript @_file_upload
  Scenario: Preview the jack question and submit a response with a correct filetype
    Given the following "user private files" exist:
      | user     | filepath                                   | filename |
      | student1 | question/type/jack/tests/fixtures/test.jar | test.jar |
    When I am on the "Quiz 1" "mod_quiz > View" page logged in as "student1"
    And I press "Attempt quiz"
    And I should see "First question"
    And I should see "You can drag and drop files here to add them."
    And I click on "Add..." "button"
    And I click on "Private files" "link" in the ".fp-repo-area" "css_element"
    And I click on "test.jar" "link"
    And I click on "Select this file" "button"
    And I wait until the page is ready
    And I click on "Finish attempt ..." "button"
    Then I should see "Summary of attempt"
