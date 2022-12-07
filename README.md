# Moodle question type jack #
## Introduction

The Jack question type allows the input of program code by the participant. The response can be submitted as a file upload and/or online text. The data entered is then retrieved in the background by Jack and an assessment is transferred back to Moodle. This assessment is therefore time-delayed.

## Installation
1. move the folder jack to moodle/question/type/jack
2. log in as administrator and open the page: http://your-moodle/admin (Website administration -> System messages)
3. Follow the installation instructions.

## Web service interface
In order for Jack to retrieve the questions and return the scores a webservice must be set up on the Moodle side.

You will need to execute the following steps:

1. Create a new role and a new user which should only be used to access the webservice.
The role should have the following contexts:
- System
- Course completion
- activity

The role should have the following permissions:
- qtype/jack:access
- webservice/rest:use

2. Add this user to the list of authorized users for the "qtype Jack external" webervice.
Jack automatically creates a webservice with the necessary functions and the shortname "qtype_jack_external".

Only authorized users can access this webservice. You need to got to
http://your-moodle/admin/settings.php?section=externalservices
and add the user with the permission to the list of authorized users.

3. Create a Token for the user. This Token will have to be communicated to University Duisburg-Essen.

Go to Home / Website Administration / Plugins / Webservices / Manage tokens. Create the token for the jack webservice and the authorized user.

Note: The token is bound to the account that created it. If this account is deleted, the token will also disappear. Best create it with a main admin from which you are sure that it will be will be preserved.