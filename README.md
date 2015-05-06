# Thonk
*********************************************************************************************
*Project: Thonk
*Description: This document will explain the functionalities of every file in the application.
*Last updated: 05/04/2015
*********************************************************************************************

I. Configuration files
config.php - Connect to the database, library of functions used in the application
config_map.php - Functions which are used in start_node.php
myscript.js - Javascripts functions are used to draw the nodes
*********************************************************************************************
II. PHP & HTML pages

index.php - Homepage
start_node.php - View Nodes page

addNode.php - Add new node
addNodeProcess.php - Landing page for addNode.php
editNode.php - Edit an existing node
editNodeProcess.php - Landing page for editNode.php
viewNode.php - Display information of a node, allows logged-in user to vote up/down

login.php - login form for user
userSettings.php - show account information for logged-in user
registration.php - sign up form for non-user
eVerification.php - Send an email for user after a successful registration
registrationProcess.php - showing greeting message for new user
usernameCheck.php - Check for existing username in registration.php
forgotPassword.php - Allow user to retrieve new password by email
rules.php - Display policy/rules
contact.php - Allows users and non-users to send email to admin
FAQ.php - Display Frequently Asked Questions, allows admin to add a new FAQ straight to the page
logout.php - Destroy user's session
404.html - 404 not found

managenode.php (Admin) - Node manager
manageuser.php (Admin) - User manager
manageuserprocess.php (Admin) - Display details user information, allows admin to modify
deleteNode.php (Admin) - allow Admin to delete an existing node in Node Manager
changestatus.php (Admin) - instant switch status of user in User Manager ( from active to inactive and vice versa)

/actions/index.php - perform actions such as activating new user or sending new verification email (using GET method)
*********************************************************************************************
III. Addons/Plugins & Media files
1. Cascading Style Sheets
style.css - Stylesheet for start_node.php
/media/css/ - Cascade Style Sheet for dataTables
/css/ - Style Sheets for responsive template
/sass/ - .scss files for responsive template


2. Javascript files
myscript.js - javascript functions to draw nodes
/media/js/ - .js files for dataTables & JQuery
/js/ - .js for responsive template

3. Images
/media/images/ - Logos and icons
giphy.gif - Loading animation in start_node.php

4. Plugins/Addons
/mailer/ - SendGrid mail server
/tagsinput/ - tagsinput plugin for Keyword/Tag field

5. Others
FAQ.xml - Store FAQ arrays


*********************************************************************************************
THE END

