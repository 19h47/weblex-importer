=== Weblex Importer ===
Contributors: 19h47
Tags: rss, importer
Requires at least: 5.8
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Import posts from a Weblex RSS feed.

== Description ==

The plugin imports the news of one or more Weblex RSS feeds, once a day and each time the date of a feed is changed in **Settings > Weblex Importer**. Each item becomes a post with its title, its introduction as excerpt, its text, its image as featured image, its categories, its activities and its keywords, and the title of its feed as tag.

The posts are of the plugin's own type, under `/les-infos-du-jour/`, or WordPress posts when the **Post** option is checked.

An item Weblex edits updates its post: same date, same URL. The plugin recognises an item by its link on weblex.fr, which is built from its title: an item whose title changes is imported as a new post.

== Installation ==

1. Download the archive of the latest [release](https://github.com/19h47/weblex-importer/releases) and upload it in **Plugins > Add New > Upload Plugin**
2. Activate the plugin
3. Enter the URLs of the feeds in **Settings > Weblex Importer**

== Updates ==

The plugin updates from the latest release of [19h47/weblex-importer](https://github.com/19h47/weblex-importer/releases), like any other plugin in **Dashboard > Updates**: the `Update URI` header routes the update check to GitHub. To publish a version, bump `Version` in `weblex-importer.php` and `Stable tag` in this file, with an entry in the changelog, then create a release whose tag is that version. No update is offered when the plugin folder is a Git working copy, since updating replaces the whole folder.

== Frequently Asked Questions ==

= What happens to the posts when the plugin is deactivated? =

The posts of the plugin's own type are deleted permanently; their featured images stay in the media library. WordPress posts, imported with the **Post** option, are kept. An update does not deactivate the plugin.

= Why does an edited news item appear twice? =

Weblex changed its title, and so its link: the plugin cannot tell it from a new item. Delete the older post by hand.

== Changelog ==

= 0.0.0 =
* Initial Release

= 0.0.1 =
* Test Release

= 0.0.2 =
* Test Release

= 0.0.3 =
* Disable WP_MEMORY_LIMIT

= 0.0.4 =
* Add Category taxonomy
* i18n

= 0.0.5 =
* Fix languages files
* Fix typo

= 0.0.6 =
* Add get_the_term_list to template

= 0.0.7 =
* Bugfix
* Change archive slug for les-infos-du-jour

= 0.0.8 =
* Bugfix 

= 0.0.9 =
* Bugfix 

= 0.0.10 =
* Change tag and category slug 

= 0.0.11 =
* Add archive category template

= 0.0.12 =
* Fix pre_get_posts on taxonomy archive

= 0.0.13 =
* Set post thumbnail

= 0.0.14 =
* Set date column

= 0.0.15 =
* Add post thumbnail to archive

= 0.0.16 =
* Remove archive 
* Fix load_plugin_textdomain
* Quizz JavaScript

= 0.0.17 =
* Fox categories import

= 0.0.18 =
* Excerpt field

= 0.0.19 =
* Fix

= 0.0.20 =
* Fix

= 0.0.21 =
* Read more link

= 0.0.22 =
* Fix wp_insert_term

= 0.0.23 =
* Fix when feed item doesn't have content

= 0.0.24 =
* Check for content to display readmore link

= 0.0.25 =
* Release Date - 21 September 2021

= 0.0.26 =
* Change tag name

= 0.0.27 =
* Add some tag slug to check

= 0.0.28 =
* Fix get_tag

= 0.0.29 =
* Add new slugs
* Delete attachment when post is delete

= 0.1.0 =
* Naming

= 0.1.1 =
* Fix

= 0.1.2 =
* Fix

= 0.1.3 =
* Fix weblex_importer_options in CRON job

= 0.1.4 =
* Fix CRON job

= 0.1.5 =
* Fix function name

= 0.1.6 =
* Change JS Quizz behavior

= 0.1.7 =
* Add LES INDICATEURS tag
* Add L'AGENDA tag

= 0.1.8 =
* Clean code

= 0.2.0 =
* Adding Activities taxonomy
* Get Activities from feed

= 0.2.1 =
* Fix and test Activities taxonomy

= 0.2.2 =
* Add activity taxonomy archive template

= 0.2.3 =
* Remove var_dump

= 0.2.4 =
* Remove tag from teaser

= 0.2.5 =
* Fixes JS
* Add keyword taxonomy

= 0.3.0 =
* Change ID logique

= 0.3.1 =
* Performance

= 0.3.2 =
* Remove date on category archive

= 0.3.3 =
* Hierachical tag

= 0.4.0 =
* WebLex to Weblex

= 0.5.0 =
* Recent posts widget

= 0.6.0 =
* Update translations

= 0.6.1 =
* Missing translation

= 0.7.0 =
* Fix get_tag_by_name (get term by slug instead of name)

= 0.8.0 =
* Show thumbnail in Recent posts widget

= 0.8.1 =
* Fix

= 0.8.2 =
* Fix

= 0.8.3 =
* Fix

= 0.8.4 =
* Fix

= 0.8.5 =
* Fix

= 0.8.6 =
* Typo
* i18n

= 0.8.7 =
* i18n

= 0.8.8 =
* i18n

= 1.0.0 =
* Option to import post as WordPress post

= 1.0.1 =
* i18n

= 1.0.2 =
* Fix

= 1.0.3 =
* Fix

= 1.0.4 =
* Fix

= 1.0.5 =
* Fix

= 1.0.6 =
* Fix

= 1.0.7 =
* Fix

= 1.0.8 =
* Fix

= 1.0.9 =
* Fix

= 1.0.10 =
* Fix

= 1.0.11 =
* Fix

= 1.0.12 =
* Fix

= 1.0.13 =
* Fix

= 1.0.14 =
* Fix fetch method when fetch_feed return a WP_Error

= 1.0.15 =
* Fix get_activities method

= 1.0.16 =
* Add NR-CO tag names

= 1.0.17 =
* Add tag name 

= 1.0.18 =
* Deenqueue empty CSS file

= 1.0.19 =
* Check if option post exist

= 1.0.20 =
* Check if option post type exist

= 1.0.21 =
* Remove unnecessary type
* Upgrade dependencies

= 1.1.0 =
* Identify a feed item by its link instead of a hash of its content: an item edited by Weblex updates its post instead of creating a new one (-2) (#4)
* An updated post keeps its date and URL
* Replace the featured image of an updated post instead of adding a new one to the media library
* Posts imported before 1.1.0 are left as they are and attached to their link on the next import
* The link is built from the title: an item whose title is changed by Weblex still creates a new post
* Find an imported post with a direct database query, which other plugins (WPML…) cannot filter (#3)
* Update from GitHub releases with the Update URI header of WordPress, instead of the plugin-update-checker library on the wordpress-plugin branch
* Remove the empty admin stylesheet and script, and the empty public stylesheet
* Code only: WordPress coding standards, checked by phpcs

== Upgrade Notice ==