<?php
/**
 * La configuration de base de votre installation WordPress.
 *
 * Ce fichier contient les réglages de configuration suivants : réglages MySQL,
 * préfixe de table, clefs secrètes, langue utilisée, et ABSPATH.
 * Vous pouvez en savoir plus à leur sujet en allant sur
 * {@link http://codex.wordpress.org/fr:Modifier_wp-config.php Modifier
 * wp-config.php}. C'est votre hébergeur qui doit vous donner vos
 * codes MySQL.
 *
 * Ce fichier est utilisé par le script de création de wp-config.php pendant
 * le processus d'installation. Vous n'avez pas à utiliser le site web, vous
 * pouvez simplement renommer ce fichier en "wp-config.php" et remplir les
 * valeurs.
 *
 * @package WordPress
 */

// ** Réglages MySQL - Votre hébergeur doit vous fournir ces informations. ** //
/** Nom de la base de données de WordPress. */
define('DB_NAME', 'test');

/** Utilisateur de la base de données MySQL. */
define('DB_USER', 'root');

/** Mot de passe de la base de données MySQL. */
define('DB_PASSWORD', '');

/** Adresse de l'hébergement MySQL. */
define('DB_HOST', 'localhost');

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define('DB_CHARSET', 'utf8mb4');

/** Type de collation de la base de données.
  * N'y touchez que si vous savez ce que vous faites.
  */
define('DB_COLLATE', '');

/**#@+
 * Clefs uniques d'authentification et salage.
 *
 * Remplacez les valeurs par défaut par des phrases uniques !
 * Vous pouvez générer des phrases aléatoires en utilisant
 * {@link https://api.wordpress.org/secret-key/1.1/salt/ le service de clefs secrètes de WordPress.org}.
 * Vous pouvez modifier ces phrases à n'importe quel moment, afin d'invalider tous les cookies existants.
 * Cela forcera également tous les utilisateurs à se reconnecter.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '~=qmBf(7mx6L~UBbJ*<S2JL?Hjou(~`U1}0SJsM9-ophv_ITiqRfxv^tn_Rq!3wi');
define('SECURE_AUTH_KEY',  '%[)$j,vkxZ8Je+3`O{23*9!=|W6%oL%7_0=v)*4t4gQzX:}_Vb*mf~yU(4^*8N6*');
define('LOGGED_IN_KEY',    '4NxPG@fCi%J;w+UXn#(:)d0P{^g)u*K@&;P%UTQ4Xu&Y)$GTpp:n4UUQCKj05XXq');
define('NONCE_KEY',        'sq5ksqBM7d-C6}due4zx%GN3`g`n|g?TD#!*J7zSp72Q;rO]ta}G@*c`7/7bYd!q');
define('AUTH_SALT',        'N3G@eYS]0`7Vy(Pd{Ed]a2/UF 0z#3s=}m%[5R$ygaqjj9%!H{UtU>T<y@Tno)@+');
define('SECURE_AUTH_SALT', '0as*]X1GKWG6l 0;{Y|LGI0Rfx5`kO(Yi*|4VeqsFEKHwaN{s#[pt(pTlhfPvFy3');
define('LOGGED_IN_SALT',   '6W0}X@5VAVn:Lj8!|>.c_g:}f:l$.<>e<AN@#XJ*+ts:[AE*;IuICXBb&REXF!xl');
define('NONCE_SALT',       'Mq^s4@XYHNrs;$iI?-wj_M9~l:2%5$Ld%az|Q7?O/_IznZ9[7`w7kypUHLQ3c2[b');
/**#@-*/

/**
 * Préfixe de base de données pour les tables de WordPress.
 *
 * Vous pouvez installer plusieurs WordPress sur une seule base de données
 * si vous leur donnez chacune un préfixe unique.
 * N'utilisez que des chiffres, des lettres non-accentuées, et des caractères soulignés!
 */
$table_prefix  = 'wp_';

/**
 * Pour les développeurs : le mode déboguage de WordPress.
 *
 * En passant la valeur suivante à "true", vous activez l'affichage des
 * notifications d'erreurs pendant vos essais.
 * Il est fortemment recommandé que les développeurs d'extensions et
 * de thèmes se servent de WP_DEBUG dans leur environnement de
 * développement.
 *
 * Pour plus d'information sur les autres constantes qui peuvent être utilisées
 * pour le déboguage, rendez-vous sur le Codex.
 * 
 * @link https://codex.wordpress.org/Debugging_in_WordPress 
 */
define('WP_DEBUG', false);

/* C'est tout, ne touchez pas à ce qui suit ! Bon blogging ! */

/** Chemin absolu vers le dossier de WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Réglage des variables de WordPress et de ses fichiers inclus. */
require_once(ABSPATH . 'wp-settings.php');