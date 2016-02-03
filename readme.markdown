Pegasus-Theme
=============

Wordpress theme for Pegasus Magazine.  Built off of UCF's Generic Theme.


## Requirements
* WordPress 4.1+

## Required Plugins
- Map Cap (see notes)
- Gravity Forms

## Installation Requirements
- Set user capabilities for custom post types via Map Cap.  See notes below.
- Configure Cloud.Typography projects for Development and Production environments.
- Set an active issue in Theme Options.  See Theme Help for more info.

## Notes

### Developer Mode
This theme has a built-in Developer Mode that should be activated while stories and issue covers are in development.  Update the Developer Mode setting in Theme Options to activate or deactivate Developer Mode.

Developer Mode allows for a streamlined development process; instead of uploading updated css and javascript files into WordPress's editor with every update to view changes, you can maintain a consistent naming schema for all story/issue-related files in the dev directory and reference those files by story/issue directory.  With Developer Mode turned on, these files will be referenced automatically if the naming schema matches the post slug (e.g., a story with slug 'my-story' has the css file 'my-story.css') and a particular directory in the dev directory is specified in the Developer Mode meta field(s).  More information is provided in the meta field description for those fields.  The fields will only appear if Developer Mode is turned on.

HTML markup is also updateable with this method; if the WYSIWYG editor is blank and a dev directory is specified, you can work off of a file of schema 'post-slug.html' in that directory instead of copy+pasting the markup with each update.

Note that Developer Mode should be turned off for non-development environments.

### Theme Versions
As of v3.0.0 of this repo, this theme uses a "versions" concept to load functions, templates and assets conditionally, depending on the story/issue being requested.  Versions allow us to maintain backward-compatibility with stories that were written using custom markup and/or code that is heavily dependent on outdated assets.  Versions are assigned to issue posts as a custom meta field value; available values are defined with the `LATEST_VERSION` and `EARLIEST_VERSION` constants in `functions/config.php`.  These versions correspond to contents in a `versions/` subdirectory.  Ideally, versions values should line up with with major git tags on the theme (e.g. git tags of 3.x.x+ should have a `LATEST_VERSION` value of 3), though this is not technically required.  See the readme in each version directory for more details on those versions.

#### Create a New Version
To create a new version, copy the latest version directory in `versions/` and rename it, then update the `LATEST_VERSION` value in `functions/config.php`.  Make any changes to the newly-copied code as necessary.  Deploy repo with new version code, then assign the new version to newly-created issue posts in WordPress.

### Stories, Issues, Photo Essays Capabilities
This theme uses the Map Cap plugin to set user capabilities for custom post types.  See recommended schema below.

**For custom post types in this theme to be usable for users that are not super admins, this step MUST be performed
upon theme activation.**

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

### Importing Content
Install the https://github.com/UCF/WP-Allowed-Hosts wordpress plugin if media import fails. After installing the plugin update the Allowed Hosts under the admin Settings menu. Use the following regex `\.ucf\.edu$` and check the regex checkbox.

## Custom Post Types

* Issue - a post reference to an overall group of stories.  Acts as the home page for Pegasus when a particular issue is active (via Theme Options.)
* Story - a single story, categorized by the issue taxonomy.

## Custom Taxonomies

* Issue - grouping mechanism for stories.  Note that the slugs for these must match their equivalent Issue post slug exactly.

## Shortcodes

* image - returns the URL of an image uploaded as an attachment for the post.
* static-image - link to an image in the theme's static directory.  (Requires extension)
* media - if found, returns the URL of some arbitrary media in the media library that is an attachment of the current post.  Should only be used for media types that can't be returned via [image] or [static-image].
* google-remarketing - outputs Google Remarketing javascript code.
