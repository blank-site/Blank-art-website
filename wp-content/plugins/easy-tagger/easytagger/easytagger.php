<?php

/*
Plugin Name: Easy Tagger
Plugin URI: http://wordpress.org/extend/plugins/easy-tagger/
Description: A simple plugin enabling blog visitors to tag posts, show rankings, etc
Version: 1.0
Author: Dominik Gawlik

    Copyright 2011  Dominik Gawlik (dominik.gawlik1@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/* uncomment for debug version */
//define('DEBUG',true);


if( ( $phpbad = version_compare(phpversion(), '5.0.0','<') ) ||
    version_compare(get_bloginfo('version'), '2.8.0','<')  )
{
    if(function_exists('deactivate_plugins'))
    {
        deactivate_plugins( plugin_basename(__FILE__), true );
    }
    if($phpbad){
        wp_die( sprintf( "Easy Tagger requires at least %s PHP version. You have %s.",'5.0.0',  phpversion() ) );
    }
    else
    {
       wp_die( sprintf( "Easy Tagger requires at least %s WP version. You have %s.",'2.8', bloginfo('version')) );
    }
}


/* plugin directory */
define('ET_DIR',dirname(__FILE__));
/* plugin url*/
define('ET_URL', plugin_dir_url(__FILE__) );
/* includes dir */
define('ET_INC',ET_DIR . '/inc');
/* widgets dir*/
define ('ET_W',ET_DIR . '/widgets');


/*log if in debug mode */
if( defined('DEBUG')){
    
require_once 'Log.php';
require_once 'Log/file.php';

$GLOBALS['logger'] = new Log_file(ET_DIR . '/etlog');
global $logger;
$logger->open();

}

/* helpers */
require_once(ET_INC . '/etdb.php');

require_once(ET_INC . '/serve_ajax.php');

require_once(ET_INC . '/misc.php');

/* widgets */
require_once(ET_W . '/tagadder.php');
require_once(ET_W . '/tagdisplay.php');
require_once(ET_W . '/griddisplay.php');
require_once(ET_W . '/tagsearch.php');
require_once(ET_W . '/tagmostactive.php');
require_once(ET_W . '/tagrecent.php');
require_once(ET_W . '/tagpopular.php');



?>






