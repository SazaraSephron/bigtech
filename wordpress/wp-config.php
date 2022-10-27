<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'bigtechs_wordpress' );

/** Database username */
define( 'DB_USER', 'bigtechs_wp' );

/** Database password */
define( 'DB_PASSWORD', 'u1ozP`H+g^A"Ndw' );

/** Database hostname */
define( 'DB_HOST', 's04he.syd5.hostingplatform.net.au' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

define('WP_MEMORY_LIMIT', '512M');

define( 'WP_MAX_MEMORY_LIMIT', '512M' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'h;+:*ggQ$&5g2yParmrP4g&|TA[iu$;+kbO[*-QR%3K+u%1&kW8kcbVZJj)$svZi');
define('SECURE_AUTH_KEY',  '74X#-03|``Tl-VOg}fb2|4+}!Gu eOMp=:4:ELxlBjm9jjBU|Z^cR7}Gslpw{PhB');
define('LOGGED_IN_KEY',    'X5M+$^4u_Rf05&<7%yvAI}Vn^++z(l+d2ZZdV/O+op+V768[mKXLGr>:|Ag(9K:P');
define('NONCE_KEY',        'IWHf9<T6R*h&.brZ  rQJW{6SfZ&A_o~+-(D|NzD,SgW/Dlj}-n6>$?{{,lGJk)G');
define('AUTH_SALT',        'V-85^pJw,XqS6(F^-.2O9,Kk>r[>S&JDu{Q4Z^boT|?BKc<PmB-7UxO6b`h_e16-');
define('SECURE_AUTH_SALT', '@#.6Ji_lw-5M9ihKgCM_4A-UKBb`]Y-Z_*O:WD@G({+$q%iL}4bvxg&kfqeE+(B|');
define('LOGGED_IN_SALT',   'e!1GugIQ]@=>S`yriaS.zNgts|)7D+i1UH@{&kM&bosvhB|G5+}d#+/GE_h3_$xx');
define('NONCE_SALT',       '.r1wg*@+(Q*e%d<J>>M++Nc|k]@-G9.cX)2|kgaU{]6Y( gTMb~f@3QNI5GC $RP');

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
