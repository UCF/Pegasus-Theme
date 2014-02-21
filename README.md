Pegasus-Theme
=============

Wordpress theme for Pegasus Magazine.  Built off of UCF's Generic Theme.


## Required Plugins
- Map Cap (see notes)
- Gravity Forms


## Installation Requirements
- Set user capabilities for custom post types via Map Cap.  See notes below.


## Notes
This theme uses the Map Cap plugin to set user capabilities for custom post types.  See recommended schema below.
Not setting a schema forces custom post types to inherit a default set of capabilities from the generic 'post' post type.

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