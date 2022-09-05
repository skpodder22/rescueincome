<?php
ini_set('display_errors',1);
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
//define( 'DB_NAME', 'rescueincome' );
define( 'DB_NAME', 'rescueincomelive' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',         'BWw&*8@%c)<q1#%`S>]/J!W#ak=:Lr#L1y@&nPqH<#$$#q[?X`<(L*zMU}+cAk.]' );
define( 'SECURE_AUTH_KEY',  '(Zi&@]CR)n2HG^~|`<xmS69ZCS|W9v`=m&B8yhe5$^O*%#F(359]x2:3?YoMyCao' );
define( 'LOGGED_IN_KEY',    '=mUzE50sCQ6eS&FEUNyaW0+im(X;]Zho~ >DY~ssZb9K0)MjKEE4g#&,kEnF]_2q' );
define( 'NONCE_KEY',        '9+>E_$qWD}cp?O-:D(w{92Ao[*M-aq+;Q,r_Q<f3Txmzc8mA0^y}Y_Ti-rGLTx _' );
define( 'AUTH_SALT',        'k07dfg@mK#OzIwI0.-~CivA3J,9n0@40E59;Wy.:k%UAy)X,0m|JeAcltW>=W-(#' );
define( 'SECURE_AUTH_SALT', '|c.=+xz[@HiTj`;>600C:Y/3]?vJ12IJa;;ZmNGkz(L]QU{uQYl>lE;!%tMQnK K' );
define( 'LOGGED_IN_SALT',   'x/)+6J0ba1E,4LjJ/sampd~HCB`EhKaxsK?:J)z#kVAJ9z%4pBK;6KzLd22Ifo<9' );
define( 'NONCE_SALT',       'E!XFO0zK}*Ky/D?f<eC Putn9m ++ =-nv@:]q^xd2AP{@l|wi?.rfDs2{)0+?L6' );

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
//define( 'WP_DEBUG_LOG', true );
/* Add any custom values between this line and the "stop editing" line. */

define('WP_IMPORTING',true);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
