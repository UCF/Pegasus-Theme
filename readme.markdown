# Pegasus Theme

Theme for the online version of Pegasus Magazine.

## Notes

This theme has a built-in Developer Mode that should be activated while stories and issue covers are in development.  Update the Developer Mode setting in Theme Options to activate or deactivate Developer Mode.

Developer Mode allows for a streamlined development process; instead of uploading updated css and javascript files into WordPress's editor with every update to view changes, you can maintain a consistent naming schema for all story/issue-related files in the dev directory and reference those files by story/issue directory.  With Developer Mode turned on, these files will be referenced automatically if the naming schema matches the post slug (e.g., a story with slug 'my-story' has the css file 'my-story.css') and a particular directory in the dev directory is specified in the Developer Mode meta field(s).  More information is provided in the meta field description for those fields.  The fields will only appear if Developer Mode is turned on.

HTML markup is also updateable with this method; if the WYSIWYG editor is blank and a dev directory is specified, you can work off of a file of schema 'post-slug.html' in that directory instead of copy+pasting the markup with each update.

Note that Developer Mode should be turned off for non-development environments.

## Custom Post Types

* Issue - a post reference to an overall group of stories.  Acts as the home page for Pegasus when a particular issue is active (via Theme Options.)
* Story - a single story, categorized by the issue taxonomy.
* Alumni Note - (not used)

## Custom Taxonomies

* Issue - grouping mechanism for stories.  Note that the slugs for these are reverse of the Issue post type slugs and need to be entered manually upon creation.

## Short Codes

* image - returns the URL of an image uploaded as an attachment for the post.
* static-image - link to an image in the theme's static directory.  (Requires extension)
* media - if found, returns the URL of some arbitrary media in the media library that is an attachment of the current post.  Should only be used for media types that can't be returned via [image] or [static-image].