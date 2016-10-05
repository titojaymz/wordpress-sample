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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'masterpogi');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'oZ@em@d0sphDo%8X{pM~Y7%,Ve+`C$^zp#z<!v5hJDjC.&CpPXEyctQ=G9X%DY;u');
define('SECURE_AUTH_KEY',  'YX<0u(jO}PKB0i/95*D!j,99uC$3d0301!Ka[olZ]gb 9[>]bCmk^UZ-}*[KK~wm');
define('LOGGED_IN_KEY',    'W#k1&|Hp5t<<jW.lOc;,J54+<D~m{wgnt%W_MFQ@`>YS25ZRZ-a5_)1d#1JU& p;');
define('NONCE_KEY',        'II~)ZOq`){t%N9c9jgy/*7(a(X~Wn3(W*Y]cK@8/TfQrWcRy+6.XDEJW(L;DtwI$');
define('AUTH_SALT',        '+K7%&MP{FZL`ieukcun9IW(t({OU:<!(l/)cd$Sc#v=HcawE=]+[PmiCvQrbEdaF');
define('SECURE_AUTH_SALT', 'Yw-m,8}xwc+L`A[3tIAocW&cPp=O[DnLXV z1qcqp|=|)/pevHDk(],n4}-[i@8u');
define('LOGGED_IN_SALT',   '#z#ocW03/$73QZu7-ndTKkM/m!#ZS]V(|U;1|Vlw82UDIX{*8m9-(p!z#JmU%r0b');
define('NONCE_SALT',       'cp ?@W@)sf +WF:38^P!$,<VDW}:H `Ja$iw7Tpdg8oysmax#ax3d29M<C^yJS|g');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
