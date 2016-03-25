4PSA VoipNow SecureMyForm
--------------------------------------

Disclaimer
----------
This demonstrative code SHOULD NOT be used for production. 
It was designed to show how third-party applications interact 
with the 4PSA VoipNow using the UnifiedAPI web service.
Therefore, illustrating the most common mistakes requires no
validations and the error-checking process is relaxed. 

Requirements
------------
1. A server with a version of VoipNow installed. 
The version must be higher than 3.0.0.

2. PHP 5.3 or higher is required.

Installation
------------

After downloading the plug-in from the 4PSA Wiki, you need to set it up.

1. You need to run the SQL located at sql/securemyform.sql. This will create several tables used by the application.

2. Open the interface/plib/config.php file and change the following configuration parameters: 

- VN_SERVER_IP - the IP or hostname of the VoipNow Professional server; 
- VN_VERSION - the VoipNow Professional software version;
- VN_IVR_EXTENSION - the IVR extension from the VoipNow server that will play the messages;
- VN_CHARGE_EXTENSION - the extension that will be charged for the call; this extension must belong to the VoipNow server;
- RETURN_URL - the URL the user is redirected to as soon as validation is complete; the information submitted is processed here;
- FORM_METHOD - the method used by forms to submit the information;
- OAUTH_ACCESS_TOKEN - OAuth access token
- MYSQL_HOST - MySQL host the SQL from step 1 has been run on;
- MYSQL_USER - MySQL username; 
- MYSQL_PASS - MySQL password used for the username defined by the MYSQL_USER parameter; 
- MYSQL_DBNAME - the name of the MySQL database; 

3. Then you need to setup the IVR extension.

First of all, you need to add a context for the IVR. 

In order to do this, you need to go to the server interface, select the Extensions option from the upper left menu,
and access the IVR extension you want to use. Then click on the IVR setup icon in the Tools section. 
In the page that will open, you may add a new context or edit an existing one. Please make sure the context you select is the one played when the user calls the IVR.

Then you need to edit the Start option of the previously selected context and as follows:

a. Add a "Play sound" action. This will contain the message that the user hears when connected to or calling the IVR.

b. Add a new "Record digits to variable" action. With the help of this option, the number the user is required to enter is recorded.
This will ensure validation. The variable that will be recorded must be called "activationCode."

c. Add a new "CallAPI Interactive" action that needs to be set up as follows:
- Request method = GET
- Request ID = An alphanumeric string
- Make request to = 
	http(s)://<PATH_TO_PLUGIN>/ivr_validate.php?activationCode=$activationCode
	
When the IVR runs this action, it will access the validate_request.php setting the activateCode value to the $activationCode variable registered in the previous action.

Copyrights
----------

4PSA VoipNow SecureMyForm

Copyright (c) 2012, Rack-Soft (www.4psa.com). All rights reserved.
VoipNow is a Trademark of Rack-Soft, Inc
4PSA is a Registered Trademark of Rack-Soft, Inc.
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:
    * Redistributions of source code must retain the above copyright
      notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright
      notice, this list of conditions and the following disclaimer in the
      documentation and/or other materials provided with the distribution.
    * Neither the name of 4PSA nor the names of its contributors may be used 
      to endorse or promote products derived from this software without specific 
      prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL 4PSA BE LIABLE FOR ANY DIRECT, INDIRECT, 
INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR
 PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF 
LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE
 OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF 
 ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
