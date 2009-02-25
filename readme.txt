=== iLast.Fm ===
Contributors: leandrow
Tags: lastfm, last.fm, music, tracks, cover, cd, cd cover, album, widget, sidebar, images
Requires at least: 2.0
Tested up to: 2.6
Stable tag: 0.2

iLast.Fm provides a complete integration between your blog and your Last.fm account, show what musics you are listening to and much more!

== Description ==

The plugin iLast.Fm provides a complete integration between your blog and your Last.fm account.

You can show on your blog what musics you are listening to, your top albums, top artists, loved tracks, and much more. We show the cd covers or informations. Or both, as you want.

See what we can do for you:

*   Display the cd cover and/or text informations
*   Show your recent tracks, top albums, top artists, top tracks, weekly album chart, weekly artist chart, weekly track chart or (whew!) your loved tracks.
*   Cache all images and data, making everything faster!
*   The iLast.Fm widget: easily you can put your musics on sidebar.
*   Complete integration with the Administration Panel.
*   Easy to use and configure.
*   Fully customizable!

== Installation ==

1. Upload he folder `/ilastfm` (and all of your content) to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to iLast.Fm Options (Plugins -> iLast.Fm) and set your username and preferences
1. Important: The folder `/cache` need to be writable, otherwise the cache will not work
1. Place `<ul id="ilastfm"> <?php ilastfm(); ?> </ul>` in your templates (or use the widget)
1. Listen to some music and relax (:

== Frequently Asked Questions ==

= The cache option does not appear, why? =

This probably happens because the folder /cache (inside of /ilastfm) doesn't can be writable. To solve this problem, use an FTP, go to folder cache and try put CHMOD 775.

= How do I clear the cache? =

Go to iLast.Fm Options and simply click in "Save Settings". Done.

= Where i can get more help, tips and informations? =

[Here!](http://leandrow.net/lastfm/ "iLast.Fm - Official Page")