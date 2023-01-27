# Moodle question type JACK #
## Introduction

The JACK question type allows the input of program code by the participant. The response can be submitted as a file upload or online text. The data entered is then retrieved in the background by JACK and an assessment is transferred back to Moodle. This assessment is therefore time-delayed. JACK is an automatic exercise and examination system developed by the University of Duisburg-Essen.

## Installation
1. Move the folder jack to moodle/question/type/jack
2. Log in as administrator and open the page: http://your-moodle/admin (Site administration / Notifications)
3. Follow the installation instructions.

## Web service interface
In order for JACK to retrieve the questions and return the scores a webservice must be set up on the Moodle site.

You will need to execute the following steps:

1. Create a new role and a new user which should only be used to access the web service.
The role should have the following contexts:
- System
- Course 
- Activity module

The role should have the following permissions:
- qtype/jack:access
- webservice/rest:use

2. Add this user to the list of authorized users for the "qtype Jack external" webervice.
During the plugin installation, a web service with the necessary functions and the shortname "qtype Jack external" is automatically created.

Only authorised users can access this webservice. You need to got to
http://your-moodle/admin/settings.php?section=externalservices (Site administration / Server / Web services / External services) and add the user with the permission to the list of authorized users.

In case all users of your Moodle site have to accept one or more user agreement, the administrator will need to make sure the newly created user has accepted the necessary agreements. Otherwise, the web service might not run smoothly. Log in as administrator and go to https://your-moodle/admin/tool/policy/acceptances.php (Site administration / Users / Privacy and policies / User agreements) to check the user’s acceptance.

3. Create a token for the user. You will need to communicate this token to the University  of Duisburg-Essen at jack(at)paluno.uni-due.de.

Go to https://your-moodle/admin/webservice/tokens.php?action=create (Home / Site Administration / Server / Web services / Manage tokens) and create the token for the service “qtype Jack external” and the authorised user.

Note: The token is bound to the account that created it. If this account is deleted, the token will also disappear. Best create it with a main administrator from which you are sure that it will be will be preserved.

## Further information
You can find further information (in German) about the question type JACK and sample questions in the [Moodle course “Fragetyp JACK erklärt” developed by the Hochschule Niederrhein](https://moodle.hsnr.de/course/view.php?id=9059), University of Applied Sciences. For background information (in English and German) about the automatic exercise and examination system JACK visit the [website of paluno - The Ruhr Institute for Software Technology at the University of Duisburg-Essen](https://s3.paluno.uni-due.de/en/forschung/spalte1/e-learning-und-e-assessment).
