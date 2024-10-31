=== Netsmax Gateway For Woocommerce ===
Tags: netsmax,payment, woocommerce payment,payment gateway
Requires at least: 5.9
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.0.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Extends WooCommerce with an Netsmax gateway payment.

== Description ==
Extends WooCommerce with an Netsmax gateway payment. Manage your transactions on WordPress more conveniently.
See the [installation guide](https://api.netsmax.com/saas/woocommerce/apps/install-plugin) for how to install and configure
this plugin.

== Plugin Features ==

Netsmax is a leading provider of cross-border payment and risk management services.
With extensive experience in the payment industry and an excellent technological platform,
we are dedicated to providing stable and redivable overseas payment processing capabidivties to well-known payment institutions, acquirers, and merchants.
Our goal is to dedivver efficient, secure, and dependable acquiring gateway services and transaction risk control services for global cdivents.

== Installation ==
1. Download `netsmax-gateway-for-woocommerce` the latest version of the plugin zip file.
2. Login to your WordPress admin dashboard, navigate to the `Plugins` menu, and choose `Add New Plugin`.
3. Click on `Upload Plugin`, select the downloaded plugin zip file, and upload it.
4. Activate the plugin.

== Usage ==
Once the plugin is activated, you can access the new features in the WordPress dashboard under the plugins settings page.
1. Go to the WordPress admin dashboard, navigate to `WooCommerce` -> `Settings` -> `Payments`.
2. Enable `Netsmax` the plugin under the payment options and configure the necessary settings.
3. Your customers will now see the new payment options on the checkout page to complete their order payments.


== Frequently Asked Questions ==
Q: Which payment methods are supported?
T: The plugin supports popular payment methods such as Credit Cards, local wallets, etc.

Q: In which countries can this plugin be used?
T: This plugin can be used globally and is suitable for all WooCommerce websites.

Technical Support and Contact:
For any questions or assistance, please email us at `support@netsmax.com`.


== Changelog ==
Stable tag: 1.0.4

See [changelog.txt](changelog.txt) for more info. 

= 1.0.4 =
* Release Date - 23 September 2024

* Updated to add initialization validation and eliminate errors in session management classes using woocommerce;
* Some other security code adjustments;

= 1.0.3 =
* Release Date - 23 July 2024

* Update the logic of cleaning up the configuration data stored by this plugin when uninstalling the plugin;
* Some other security code adjustments;

= 1.0.2 =
* Release Date - 22 July 2024

* Display JS source format, turn off JS min conversion;
* Update HTML, URL, Text security escape;
* Update security filtering receiving parameters and JSON;
* Some other non-security code adjustments;

= 1.0.1 =
* Release Date - 8 July 2024

* Refactored JS, Remove some unnecessary JS
* Update version stability support statement
* Update the Text Domain name to make it consistent
* Update PHP file import method and modify class name
* Update HTML security escape esc_html()
* Update receiving parameters for security filtering

= 1.0.0 =
* Release Date - 14 May 2024
* Initial version
