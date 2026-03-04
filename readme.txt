=== Site Doctor ===
Contributors: sayed shagul
Tags: site health, performance, cleanup, dashboard, database
Requires at least: 6.0
Tested up to: 7.0
Stable tag: 1.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: site-doctor

== Description ==

 Site Doctor helps you monitor and improve your WordPress site’s health, performance, and database cleanup.

= Features =

• Health Score with dynamic status colors  
• WordPress version monitoring  
• Plugin count overview  
• Active theme information  
• Database cleanup tools  
• AJAX powered scan and fix tools 

It also provides buttons to:  
* Run health scan  
* Fix common issues automatically  
* Clean cache / temporary data  

Perfect for site admins who want a quick overview of their WordPress site’s health.

== Installation ==
1. Upload the `wp-site-doctor` folder to `/wp-content/plugins/`.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to **Site Doctor** in the admin menu to see your dashboard and perform scans.

== Frequently Asked Questions ==

= Can I customize the Health Score? =
Yes, you can edit the `wpsd_health_score()` function in the plugin to adjust scoring rules.

= Can I change colors for Health Score? =
Yes, colors are defined in `dashboard.php`. You can replace the hex codes for each range (Excellent, Good, Fair, Poor).

= Can I display the card on my website pages? =
Currently, the plugin is designed for the admin dashboard. Shortcode support may be added in future updates.

= Is it safe to run the cleanup? =
Yes, the plugin only deletes **revisions, spam comments, transients, and trash posts**. No published content is affected.

== Screenshots ==
1. Dashboard view showing Plugin count, WordPress version, Theme, and Health Score.
2. Health Score card with dynamic colors (Green=Excellent, Amber=Good, Orange=Fair, Red=Poor).
3. Buttons for Scan, Fix Issues, and Clean Cache.
4. Example of scan results after running health scan.

== Changelog ==
= 1.0 =
* Initial release: Admin dashboard with plugin count, WP version, theme, posts, health score.
* Added dynamic Health Score with color coding and wording.
* Added AJAX buttons for scan, fix, and cleanup.
* Modern, clean, responsive dashboard design.

== Upgrade Notice ==
= 1.0 =
Initial release. No upgrades yet.

== Arbitrary section ==
WP Site Doctor is a lightweight, easy-to-use plugin that gives WordPress admins a quick overview of site health and tools to maintain it efficiently.