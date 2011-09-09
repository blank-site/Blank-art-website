<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'blank');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'sonata');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'U&[$bH2 `b^8C}U;`d~d44d7^s|7t=SfKjOOQZN]H&J-a<(/$@99t]oK?Qb|3ayk');
define('SECURE_AUTH_KEY',  '-kz6lLo|F_dW&P]wUU:A$e}:a954,EW7ob]X#:JHXY.xd,(?[HgS5ZPT$J9=b3D{');
define('LOGGED_IN_KEY',    '*GjL[|JjuU?w67WjWwj!0[%A+{V`!K`p3c10.F`?)i}A!#VexN;g+DjK,hkMG}H>');
define('NONCE_KEY',        '.zQ%u!Sed3CASNwpK,z%j_-k L p{-+(nGnbUL~$h?s#;>/8^h0:-D{Uq/P?*oG_');
define('AUTH_SALT',        'N1?oF3`5}M }rN(l0*Fxnln&c!#oD-OE:~tHq!Y`@Zn%F5u_+0O5WC>Sy@ZA6+k[');
define('SECURE_AUTH_SALT', 'rK[!]a3~`Iz&*TqA4X;t1V,gve!8(kE<O}f(${Ry9/`&QD~4gbs*.c78%$+.3X^]');
define('LOGGED_IN_SALT',   '#Ke}S_r_7+`3_sptDje@6PH5_e.,x<4q-p[X8Mk>I}BHBu|P|7LQ`olB!B{gvJC5');
define('NONCE_SALT',       '/7ML/zs<UUHE^Up4:ju2j{5U`Pj!{}Wy2TBFE`8d/y?^C;D^L<DkTG:4IJ^?9Uih');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
