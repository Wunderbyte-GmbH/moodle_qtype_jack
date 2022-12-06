The jack question type allows a response as a file upload and/or online text. This will be graded automaticly in the background.

Installation:
To install the plugin just copy the folder "jack" into moodle/question/type/.

Afterwards you have to go to http://your-moodle/admin (Site administration -> Notifications) to trigger the installation process.

Usage:
The process runs in two steps. On the first page you can chose inactive user or webinar user.

On the second step you can see the users found, before you delete them. This step page shows only 100 lines at a time.
You can start the deleting process for the users you see with the button "delete user".
If the list is greater than 100 the following users are displayed after deleting the current displayed.

copyright  2022 eLeDia GmbH http://eledia.de
license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
You can receive a copy of the GNU General Public License at <http:www.gnu.org/licenses/>.

#Introduction
The Jack question type is based on the Moodle free text question type. In this documentation we will only explain the differences/extensions to the free text question type.

The Jack question type allows the input of program code by the participant. The data entered is then retrieved in the background by Jack and an assessment is transferred back to Moodle. This assessment is therefore time-delayed.

# Installation
1. move the folder jack to moodle/question/type/jack
2. log in as administrator and open the page: http://your-moodle/admin (Website administration -> System messages)
3. Follow the installation instructions.

# Web service interface
In order for Jack to retrieve the questions and return the scores a webservice must be set up on the Moodle side. For this purpose user and a role for this user in the context system.

The role should have the following contexts:
- System
- Course completion
- activity

The role should have the following permissions:
- qtype/jack:access
- webservice/rest:use

==Jack automatically creates a webservice with the necessary functions and the shortname "qtype_jack_external".==

Finally, under Home / Website Administration / Plugins / Webservices / Manage tokens create a token for the user for this webservice.

Note: The token is bound to the account that created it. If this account is deleted, the token will also disappear. Best create it with a main admin from which you are sure that it will be will be preserved.

# Jack fields in the question instance
The plugin has two additional fields in the instance settings to store information necessary for information necessary for the jack rating.
