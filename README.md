# ci-simple-auth
PHP - CodeIgniter | Simple session control system. (Limited Step, Limited Device, Changing Device, Active / Passive)


It is a simple session control system made with CodeIgniter.

CI ver: 3.1.11
Overwrite the codeigniter files you downloaded. You can then test it by installing the sample database.

User information for testing;
Username: test@test.com
Password: 123


Some simple features in the system;

- If the wrong password is entered, the trial limit-
@ When the wrong password is entered, it gives warning by the number of trial limits that you set in the setting file, and when it reaches this limit, it prevents the input from the setting file within the time you set.

- Registration of devices and requesting device registration when logged in from different devices
@ When the user requests login via different ip, the code is sent to the email address for the new session and by entering this code, he registers his new device. It is then allowed to log in from that IP address or device.

- Is the account active? -
@ You can trigger it on the system by adding active code as desired to the required field in the database when creating the user. It does not allow an inactive user to login.

If you want to review, you should look at the files;
- models / Auth_model.php // Access control file
- config / auth.php // File with settings for required systems
core / MY_Controller.php // File opened for several operations
- core / MY_Model.php // File opened for a few operations
- language / english / auth_lang.php // File using the system's declarations
