<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'bonnle' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '3-R!Z?PMn^j!pr<j$U%[c6kmm?:0!A`^/FY{$d|TbsmarrL/A?qVv9JDG)@vJyH$' );
define( 'SECURE_AUTH_KEY',  '#>z[FZu.-h_)ZSyu))~KEU!(4.gq{|~obzlAluXQaRL81`0Z?KyUi2{}.(@}}L@X' );
define( 'LOGGED_IN_KEY',    '#D_NJ {mQdDy>H|3nIl{_qAtJ5 _<|k]xmi1Ay6Y5Gi>K,e!_GU%W#JY2Z-e18r4' );
define( 'NONCE_KEY',        ':}JE,Lu_n0r.0K=PeJ6KcPX5$T9ON^g>Z~cmQ/cc.Fu+1:?ZjOZVt6}o2dsZg@*s' );
define( 'AUTH_SALT',        'OMpCCh#$ vb2>)eZ/&8jI3u0tgpiHZ;sB^s@Ja4SZMusotHqt(;oDB/u @r|-|ua' );
define( 'SECURE_AUTH_SALT', 'v=#t<l,?mJPhQ ZyAk]zxNu*ybHKia3l<X>c;s1*Wfgr5^K$>4AOHHIx~*kZQiAb' );
define( 'LOGGED_IN_SALT',   'Eo>7Ag{AcI;q=`xNEFvEk4j)^,v99a`X~ehI[y:` #]-P](*2sD3PW=J(0|k|q(0' );
define( 'NONCE_SALT',       'FJH;h mU:Iv9+& YzB99}K&8Igz?HN(vDQ^gxp2Agk:HM=1(~@j>Fo7*>59;7Zbu' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'bne_';

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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
