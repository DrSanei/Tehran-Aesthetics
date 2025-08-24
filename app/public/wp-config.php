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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          '1;nMK.lT}D`8Eb1H_Y9(aY8MDi.2oXsp2bn~fu[Enb*RG V^w(,8PA9Dv+^*7qW~' );
define( 'SECURE_AUTH_KEY',   '!7_lj&9o9D3](]?r2G|8r6`5VA^|GZA8~kJcN0<HQ-NFpJG-~`i3<xL7V.j^b(:I' );
define( 'LOGGED_IN_KEY',     'W/I7#SzB0fpB-TzRw=[&h 7)OVaP|3Z#9lzF5+0HDUcvh8bdT .e:&?n(R6v2,jz' );
define( 'NONCE_KEY',         ' ,s:Bi{;%PKmuozghEqmcUwuA_YA>^?u(JDNoX9Yk8]3MRv9C$t&Bx~vsgV-3+!9' );
define( 'AUTH_SALT',         '$pv8cMN hWU1{DCldvUR>]ch6MeT/[tiN7><dFu)SAYDYwy5dJPH.yjNG%6<Yp60' );
define( 'SECURE_AUTH_SALT',  ':w(O`|QqJ~=T(_a*=N&rNgo$/PF>iP}0xo1+nqE[C ^J+jECdQRi3fIeFVLIR^~f' );
define( 'LOGGED_IN_SALT',    's8Q6mCdxhPFE_h ?lySxBs2v$Te]H99woMfWNQfiHWwa=<;g*K( WXz9J4}:y0zw' );
define( 'NONCE_SALT',        'du!Hb55rg=K_3FPch#cY,n4zP`wa-J~t${ (?-q<u$l(5.nE$):ZR}QAM%-J{xWo' );
define( 'WP_CACHE_KEY_SALT', '9#{U6UkegjDAZRS~@`0OQ[LiPJk85jV(&TtF`kwyft.z)T!]KnSR`m,GHc<oDxeq' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
