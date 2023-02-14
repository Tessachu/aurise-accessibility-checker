=== AuRise Accessibility Checker ===
Contributors: tessawatkinsllc
Donate link: https://just1voice.com/donate/
Tags: tota11y, totally, accessibility, a11y, accessible, neurodiversity, neurodivergent, accommodation, disability, legible reading, wcag, ada, Section 508, image alt text, quality assurance, qa, aria, landmarks, screen reader
Requires at least: 4.6
Tested up to: 6.1
Stable tag: 1.0.0
Requires PHP: 5.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Visualize how your site works with assistive technologies to improve accessibility using tota11y.

== Description ==

The internet is a wonderful place but not all websites are accessible to everyone. This plugin aims to help WordPress website owners test, check, and QA their websites for improvements on making them accessible.

= What does it do? =

This plugin adds [tota11y®](https://khan.github.io/tota11y/), an accessibility visualization toolkit, to the frontend of your website for site admins so you can easily see it marked up with annotations on where your site fails and succeeds in addressing accessibility.

= What is Tested? =

**Headings**

Highlights headings (`<h1>`, `<h2>`, etc.) and order violations

**Contrast**

Labels elements with insufficient Contrast

**Link Text**

Identifies links that may be confusing when read by a screen reader

**Labels**

Identifies inputs with missing labels

**Image alt-text**

Annotates images without alt text

**Landmarks**

Labels all ARIA landmarks

**Screen Reader Wand (Experimental)**

Hover over elements to view them as a screen reader would

== Installation ==

There are three (3) ways to install my plugin: automatically, upload, or manually.

= Install Method 1: Automatic Installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don’t need to leave your web browser.

1. Log in to your WordPress dashboard.
1. Navigate to **Plugins > Add New**.
1. Where it says “Keyword” in a dropdown, change it to “Author”
1. In the search form, type “TessaWatkinsLLC” (results may begin populating as you type but my plugins will only show when the full name is there)
1. Once you’ve found my plugin in the search results that appear, click the **Install Now** button and wait for the installation process to complete.
1. Once the installation process is completed, click the **Activate** button to activate it.
1. After this plugin is activated, see below for additional instructions for setup.

= Install Method 2: Upload via WordPress Admin =

This method involves is a little more involved. You don’t need to leave your web browser, but you’ll need to download and then upload the files yourself.

1. [Download my plugin](https://wordpress.org/plugins/aurise-accessibility-checker/) from WordPress.org; it will be in the form of a zip file.
1. Log in to your WordPress dashboard.
1. Navigate to **Plugins > Add New**.
1. Click the **Upload Plugin** button at the top of the screen.
1. Select the zip file from your local file system that was downloaded in step 1.
1. Click the **Install Now** button and wait for the installation process to complete.
1. Once the installation process is completed, click the **Activate** button to activate it.

= Install Method 3: Manual Installation =

This method is the most involved as it requires you to be familiar with the process of transferring files using an SFTP client.

1. [Download my plugin](https://wordpress.org/plugins/aurise-accessibility-checker/) from WordPress.org; it will be in the form of a zip file.
1. Unzip the contents; you should have a single folder named `aurise-accessibility-checker`.
1. Connect to your WordPress server with your favorite SFTP client.
1. Copy folder from step 2 to the `/wp-content/plugins/` folder in your WordPress directory. Once the folder and all of its files are there, installation is complete.
1. Now log in to your WordPress dashboard.
1. Navigate to **Plugins > Installed Plugins**. You should now see my plugin in your list.
1. Click the **Activate** button under my plugin to activate it.

== Screenshots ==

1. The frontend of the post page with the accessible reading button toggled on
2. The frontend of the post page with the accessible reading button toggled off
3. Plugin settings screen

== Frequently Asked Questions ==

= How do I use it? =

Simply install/activate the plugin and view the frontend of your WordPress website!
The widget will appear as a small, black block with a pair of white sunglasses fixed to the bottom-left of your browser.
Click on the widget to open its menu for the different types of tools you can use.

= Who can see the testing widget? =

By default, the widget will only appear for WordPress administrators, so your site's viewers will not see the widget.
However, you can change these settings in the backend on the Settings page by navigating to "Settings > Accessibility Checker"

You can control which user roles can see the widget and even tie it to the `WP_DEBUG` constant variable so it only appears if that variable is set to true.
You can also simply enable/disable the feature as necessary.

= How does it work? =

This plugin uses an accessibility visualization toolkit called [tota11y](https://khan.github.io/tota11y/).

= Is this the official tota11y WordPress plugin? =

No, this WordPress plugin is not a product of tota11y or its creators, the Khan Academy. It was developed by an independent and disabled web developer that wants to bring more accessibility to the digital space.

== Upgrade Notice ==

= 1.0.0 =

First submission!

== Changelog ==

= 1.0.1 =
**Submission Date: February 7, 2023**

* Major: First release to the public!