# Easy Changelog

Super simple WordPress plugin to display changelogs, or release notes, on your site.

## Description

Each changelog post is assigned to a Project taxonomy and displayed on a page with the same name/slug. You can change which post type the changelogs are associated with on the plugin's Settings page.

## Requirements
* WordPress 3.8, tested up to 4.4

## Installation

### Upload

1. Download the latest tagged archive (choose the "zip" option).
2. Go to the __Plugins -> Add New__ screen and click the __Upload__ tab.
3. Upload the zipped archive directly.
4. Go to the Plugins screen and click __Activate__.

### Manual

1. Download the latest tagged archive (choose the "zip" option).
2. Unzip the archive.
3. Copy the folder to your `/wp-content/plugins/` directory.
4. Go to the Plugins screen and click __Activate__.

Check out the Codex for more information about [installing plugins manually](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

### Git

Using git, browse to your `/wp-content/plugins/` directory and clone this repository:

`git clone git@github.com:robincornett/easy-changelog.git`

Then go to your Plugins screen and click __Activate__.

## Frequently Asked Questions

### How do I change the "Changelog" heading?

Navigate to the Changelogs > Settings page and enter a new value for the heading.

### What if I want the Changelog posts to be attached to a different post type, not pages?

On the Changelogs > Settings page, you can assign the changelog posts to any registered post type.

### Um. My changelog posts are not showing up on the page/post/whatever I've selected.

Please make sure that the slug (not the name) of the Project matches the slug of the page to which you want it associated.
## Credits

* Built by [Robin Cornett](http://robincornett.com/)

## Changelog

### 1.0.0
* Initial release on Github
