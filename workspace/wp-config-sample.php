<?php

/** Nom de la base de données de WordPress. */
define('DB_NAME', '');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', '');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', '');

/** Adresse de l’hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères */
define('DB_CHARSET', 'utf8mb4');

/** Type de collation de la base de données.
  * N’y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');


// Sécurité
define('AUTH_KEY', 'put your unique phrase here');
define('SECURE_AUTH_KEY', 'put your unique phrase here');
define('LOGGED_IN_KEY', 'put your unique phrase here');
define('NONCE_KEY', 'put your unique phrase here');
define('AUTH_SALT', 'put your unique phrase here');
define('SECURE_AUTH_SALT', 'put your unique phrase here');
define('LOGGED_IN_SALT', 'put your unique phrase here');
define('NONCE_SALT', 'put your unique phrase here');


// Database prefix
$table_prefix  = 'gate_wp_';


// Basic URL
$base_url  = 'http://projects-x/gate-v4.1/final';

// Afficher les erreurs
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);


/* 
* C’est tout, ne touchez pas à ce qui suit!
*
* Chemin absolu vers le dossier de WordPress. 
*/
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

// On change le dossier de destination des images
define('UPLOADS', 'files');

// On change le nom du wp-content
define ('WP_CONTENT_FOLDERNAME', 'frame');
// On dicte le chemin du wp-content
define( 'WP_CONTENT_DIR', dirname(__FILE__) . '/frame' );
define( 'WP_CONTENT_URL', $base_url . '/frame' );


// On dicte le chemin des plugins
define( 'WP_PLUGIN_DIR', dirname(__FILE__) . '/frame/modules' );
define( 'WP_PLUGIN_URL', $base_url . '/frame/modules' );


/* Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');