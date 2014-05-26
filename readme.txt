=== Simple Page Specific Sidebars ===
Contributors: dgilfoy, ivycat, sewmyheadon, pjackson1972
Donate link: http://www.ivycat.com/contribute/
Tags: page, widgets, sidebar, role based
Requires at least: 3.0
Tested up to: 3.9-RC1
Stable tag: 2.15
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add a sidebar to any specific page by creating a widget area on demand.

==Description==

Page Specific Sidebars adds a checkbox to every page allowing you to choose which pages should have a unique sidebar.  

Once you enable a page-specific sidebar in the page editor, a widget area will automatically be created for that page and you can customize it as you like.  

You can specify whether the plugin replaces your primary sidebar with the page-specific sidebar, or adds your page-specific sidebar to the existing sidebar.

For child pages, you can choose to inherit the parent's settings.  When a child page inherits its parent's settings, that child page will not have its own custom sidebar.  Default inheritance behavior can be set via the plugin settings page.

== Notes ==

Plugin has no built-in styling, and does not contain native styles; it is uses the styles from your existing theme.

This is a minimal plugin, placing function over form.  If you would like to extend it, or would like us to extend it in later versions, feel free to [contact us](http://www.ivycat.com/contact/), or post feedback in this plugin's [support forum]().

== Installation ==

You can install from within WordPress using the Plugin/Add New feature, or if you wish to manually install:

1. Download the plugin.
1. Upload the entire page-sidebars directory to your WordPress plugins folder.
1. Click Activate Plugin in your WordPress plugin page.
1. Visit the Settings / Page Sidebar Settings page to customize.

== Usage ==

Simply activate and go.  There are a couple filters for customization:

`$location = apply_filters( 'page_sidebar_location', 'side' );

$priority = apply_filters( 'page_sidebar_priority', 'high' );`

These allow you to change the location and priority of the metabox on the "Edit Page" view.

Contact us if you want some more filters or actions added.  http://www.ivycat.com/contact

== Screenshots ==

1. Selecting a page to have a unique sidebar is as easy as clicking a button - Also, check if you just want to merge the new sidebar with the old.
2. Your new sidebars show up under widgets.  No need to clutter up the Page editor.

== Frequently Asked Questions ==

= What is the point of this plugin? =

Some of our clients need the ability to easily create, and edit sidebars on a per-page basis using widgets.  This is our solution, and we hope it helps others too. :)

== Changelog ==


= 2.15 =
* New Feature: Inherit Parent Settings - When a page has a parent, it may inherit the Page Specific Sidebar behavior defined for its parent.

= 2.14.2 =

* Update: compatible with WP 3.9.x
* Update: revised instructions & screenshots
* Bug fixes: Fixed checkbox JS behavior
* Bug fixes: Fixed issues with selection storage
* Bug fixes: Fixed failure to work on Home page
* Bug fixes: Fixed failure to work on pages with additional post loops

= 2.14.1 =
* Bug fixes updated JS & CSS.

= 2.14.0 =
* Dropdown selection for sidebar on option page.  Now you can re-use existing sidebars on other pages.
* Ability to prepend custom sidebar on default sidebar (previously only append).
* Ability to allow the page to display an existing sidebar rather than create it's own.
* Updated license to GPL v2, included correct license file.
* File & folder maintenance

= 2.13 =
* Updated authors, links.

= 2.1.2 =
* Added author, updated short description.

= 2.1.1 =
* Added sidebar to settings page.  
* Documentation editing, housekeeping.

= 2.1 =
* Added help to settings page.

== Upgrade Notice ==

= 2.14.1 =
Bug fixes: please update right away.

= 2.14.0 =

Cool feature updates; please upgrade.

= 2.1.2 =

No critical updates; just housekeeping.

== Road Map ==

1. Add ordering feature? (long down the road).
2. Suggest a feature...


