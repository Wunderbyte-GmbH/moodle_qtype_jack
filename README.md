# Moodle question type JACK #

## What does it do?
JACK is a Moodle question type that allows trainers to create programming tasks in Moodle that offer automatic textual feedback and evaluation through the JACK system. JACK is an automatic exercise and examination system developed by paluno, the Ruhr Institute for Software Technology at the University of Duisburg-Essen. 
At the moment, the question type only supports JAVA programming tasks. However, an expansion of the field of application is possible due to the structure of the plugin and the evolving capabilities of the JACK examination system.
The JACK question type allows the input of program code by the participant. Teachers and students only use the Moodle user interface to set or answer questions and tests. Students can submit their response to the JAVA programming task as a file upload or online text. The data entered is then retrieved in the background by JACK and an automatic textual feedback and grade is transferred back to Moodle. The JACK examination system can check the programming style, the use of certain programming concepts (e.g. recursion) and also the object hierarchy.
The feedback and grade provided by the JACK system is time-delayed. Usually, it takes a maximum of one minute to receive the feedback and grade. The connection to the JACK system is possible via a web service. This setup avoids system-related breaks and enables a secure, data-saving connection.

## Installation
1. Move the folder jack to moodle/question/type/jack
2. Log in as administrator and open the page: http://your-moodle/admin (Site administration / Notifications)
3. Follow the installation instructions.

## Web service interface
In order for JACK to retrieve the questions and return the textual feedback and grades a web service must be set up on the Moodle site.

You will need to execute the following steps:

1. Create a new role and a new user which should only be used to access the web service.
The role should have the following contexts:
- System
- Course 
- Activity module

The role should have the following permissions:
- qtype/jack:access
- moodle/course:view
- webservice/rest:use
- mod/quiz:view
- mod/quiz:viewreports

2. Add this user to the list of authorized users for the "qtype Jack external" web service.
During the plugin installation, a web service with the necessary functions and the shortname "qtype Jack external" is automatically created.

Only authorised users can access this web service. You need to got to
http://your-moodle/admin/settings.php?section=externalservices (Site administration / Server / Web services / External services) and add the user with the permission to the list of authorized users.

In case all users of your Moodle site have to accept one or more user agreement, the administrator will need to make sure the newly created user has accepted the necessary agreements. Otherwise, the web service might not run smoothly. Log in as administrator and go to https://your-moodle/admin/tool/policy/acceptances.php (Site administration / Users / Privacy and policies / User agreements) to check the user’s acceptance.

3. Create a token. Go to https://your-moodle/admin/webservice/tokens.php?action=create (Home / Site Administration / Server / Web services / Manage tokens). As "User" select the user you created in step 1. As "Service" select "qtype Jack external".

You will need to communicate this token along with your site domain (such as moodle.hsnr.de) to the University of Duisburg-Essen at jack(at)paluno.uni-due.de.

Note: The token is bound to the account that created it. If this account is deleted, the token will also disappear. Best create it with a main administrator from which you are sure that it will be will be preserved.

## Further information
You can find further information (in German) about the question type JACK and sample questions in the [Moodle course “Fragetyp JACK erklärt” developed by the Hochschule Niederrhein](https://moodle.hsnr.de/course/view.php?id=9059), University of Applied Sciences. For background information (in English and German) about the automatic exercise and examination system JACK visit the [website of paluno - The Ruhr Institute for Software Technology at the University of Duisburg-Essen](https://s3.paluno.uni-due.de/en/forschung/spalte1/e-learning-und-e-assessment).
