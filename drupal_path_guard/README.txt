CONTENTS OF THIS FILE
---------------------

 * Introduction
The Drupal Path Guard is a simple module that performs a simple function 
(one that have been asked for in many places without success).

The intended use of the module is to redirect GUEST / ANONYMOUS users when
they access content in Drupal using URL /node/{id}. Many sites make use of URL ALIAS
and don't want users necessarily to scan content out of context using /node/{id}.

The module simply "hooks" into / subscribes to kernel.request and matches the 
request uri with a regular expression that is configured (default is '~/node/d*~i'). 
If there is a match, it will route the user to a route that has been configured 
(default set to '/home').

 * Requirements
 No specific code requirements exists, but the module works best with PathAuto 
 also installed, and with a URL ALIAS given to ALL content that GUEST users will 
 access. This is so that the guest user has a valid address to use for the content.

 The URL ALIAS must be provided in communication to such users.

 * Recommended modules
 PathAuto

 * Installation
The module can be installed using standard Drupal Module install practices.

 * Configuration
Configuration can be adjusted at /admin/drupal_path_guard.

 * Troubleshooting
 The module writes log entries for successful blocks as well as exceptions.

 * FAQ

 * Maintainers
 Louis Horn