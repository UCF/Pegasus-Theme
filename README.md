Pegasus-Theme
=============

Wordpress theme for Pegasus Magazine.  Built off of UCF's Generic Theme.


## Required Plugins
- Map Cap (see notes)
- Gravity Forms


## Installation Requirements
- Set user capabilities for custom post types via Map Cap.  See notes below.
- Configure Cloud.Typography projects for Development and Production environments.


## Notes
This theme uses the Map Cap plugin to set user capabilities for custom post types.  See recommended schema below.
**For custom post types in this theme to be usable for users that are not super admins, this step MUST be performed
upon theme activation.**

Schema assumes that site administrators will be approving stories before they go live in a production environment.


### Stories, Issues, Photo Essays Capabilities

#### Publish
- Administrator
- (Photo Essay ONLY:) Editor

#### Edit Own
- Administrator
- Editor
- Author
- Contributor

#### Edit Others'
- Administrator
- Editor
- Author

#### View Private
- Administrator
- Editor
- Author


### Using Cloud.Typography
This theme is configured to work with the Cloud.Typography web font service.  To deliver the web fonts specified in
this theme, a project must be set up in Cloud.Typography that references the domain on which this repository will live.

Development environments should be set up in a separate, Development Mode project in Cloud.Typography to prevent pageviews
from development environments counting toward the Cloud.Typography monthly pageview limit.  Paste the CSS Key URL provided 
by Cloud.Typography in the CSS Key URL field in the Theme Options admin area.

This site's production environment should have its own Cloud.Typography project, configured identically to the Development
Mode equivalent project.  **The webfont archive name (usually six-digit number) provided by Cloud.Typography MUST match the
name of the directory for Cloud.Typography webfonts in this repository!**

Install the https://github.com/UCF/WP-Allowed-Hosts wordpress plugin if media import fails. After installing the plugin update the Allowed Hosts under the admin Settings menu. Use the following regex \.ucf\.edu$ and check the regex checkbox.
