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
define('AUTH_KEY', '9D{^)*<Tdp4Og)` N;W)Fr2yAPIwFXb F(vRGZR6?a`*RAo)XB^hHCA}=r9@v*O3');
define('SECURE_AUTH_KEY', ';P#&Cy+0YJ#5h,W=$DN{_cza32[{6}am;>_yq Od1:~~KQS|@ LVV+ZE!F2DyXVr');
define('LOGGED_IN_KEY', 'C}Zvyz=+aU-PPUX:D+Ts^9 ]3:7-eC`7snq%%96Q~,>k*Ps.*sf-)GHZy#5DZ;<6');
define('NONCE_KEY', 'yr,LMn8-]Qp,/~+=n{j.M%9lQm[j@C^ux|rdR{+L:>Q[YxwdZ>U%KAY!{%|^rM#k');
define('AUTH_SALT', 'g{BE4Qp!d,qdW@TAoYTaZOrW A:!6|F^;rLiQ@/kv#k{>TJ M-bVtxQL|Dn.}5&*');
define('SECURE_AUTH_SALT', 'B)@mL=$7@PctGyHIHR`l<96X/#]>|?ND:WT`ZkEh0fAIw?r$&vC25]yE>0;f<9:`');
define('LOGGED_IN_SALT', 'B}-&Bi8?q8a%@!xKynuAj-U|jm)aAC5-0krzDzbcn/A6U+[fNzKCU(49H$+qh8KA');
define('NONCE_SALT', 'Kp9gEK.|sH+iUD9|^VZ]E.EJ5Y+rtF/kW:!--j6 FSc+&~!O3t4j9*+$CP}UxP;E');


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