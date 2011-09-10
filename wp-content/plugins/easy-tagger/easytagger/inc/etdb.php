<?php



class ET_Database
{
    
     function __construct(){
         global $wpdb, $logger;
         
         $exists = $wpdb->query("
            SELECT table_name FROM INFORMATION_SCHEMA.TABLES
            WHERE table_schema = '".$wpdb->dbname."'
            AND (table_name LIKE 'wp_users_terms' OR table_name LIKE 'wp_users_blacklist'); 
        ");
         
         if($exists === false){
             if( defined('DEBUG') ){
                $logger->log('Database error: ' . mysql_error($wpdb->dbh) );
            }
         }
         else if($exists === 0){
             
            if( defined('DEBUG') ){
                $logger->log('table wp_users_terms doesnt exist, creating new one');
            }
            
            $created1 = $wpdb->query("
                CREATE TABLE wp_users_terms_posts(
                    user_id INT UNSIGNED NOT NULL,
                    term_id INT UNSIGNED NOT NULL,
                    post_id INT UNSIGNED NOT NULL,
                    term_date DATETIME NOT NULL,
                    PRIMARY KEY(user_id, term_id, post_id)
                );
            ");
            
            $created2 = $wpdb->query("
               CREATE TABLE wp_users_blacklist(
                   user_id INT NOT NULL,
                   reason VARCHAR(50) NOT NULL,
                   ban_date DATETIME NOT NULL,
                   ban_expire DATETIME NOT NULL,
                   PRIMARY KEY(user_id)
               );
            ");
            
            if( defined('DEBUG') ){
                if($created1 === false)
                    $logger->log('couldn`t create table wp_users_terms: ' . mysql_error($wpdb->dbh) );
                else
                    $logger->log('successfully created wp_users_terms');
                 if($created2 === false)
                    $logger->log('couldnt create table wp_users_blacklist: ' . mysql_error($wpdb->dbh) );
                else
                    $logger->log('successfully created wp_users_blacklist');
            }
            
         }  
     }
     function get_users(){
         global $wpdb,$logger;
         
         $res = $wpdb->get_results("
               SELECT ID,user_nicename,user_email FROM $wpdb->users ;   
         ",ARRAY_A);
     
         if(!$res){
             if( defined('DEBUG') )
                 $logger->log('Database error: ' . mysql_error($wpdb->dbh) );
             return null;
         }
         else{
             foreach($res as &$r){
                 if( $this->is_banned($r['ID']) )
                         $r['banned'] = true;
                 else
                     $r['banned'] = false;
             }
              if( defined('DEBUG') )
                     $logger->log("List all users: " .var_export($res,true) );
             return $res;
         }
     }
     
     function get_banned_users(){
         global $wpdb,$logger;
         
         $res = $wpdb->get_results("
               SELECT * FROM wp_users_blacklist ;   
         ",ARRAY_A);
         
          if(!$res){
             if( defined('DEBUG') )
                 $logger->log('Database error: ' . mysql_error($wpdb->dbh) );
             return null;
         }
          else{
             foreach($res as &$r){
                $r['user_nicename'] = $wpdb->get_var("SELECT user_nicename FROM $wpdb->users WHERE ID=".$r['user_id'].";");
             }
             if( defined('DEBUG') )
                     $logger->log("List banned users: " .var_export($res,true) );
             return $res;
         }
     }
     
     function give_user_ban($userid, $opt = array()){
         global $wpdb, $logger;
         
         $default = array(
             'reason' => '',
             'expire' => time()+24*3600
         );
         $opt = wp_parse_args($opt, $default);
         
         $res = $wpdb->get_row("SELECT * FROM wp_users_blacklist WHERE user_id = $userid ;",ARRAY_A);
         
         if(!$res){
             if( defined('DEBUG') )
                $logger->log(sprintf("User %s doesn't exist in ban list",$userid));
             $res = $wpdb->insert(
                     'wp_users_blacklist',
                     array(
                        'user_id' => $userid,
                        'reason' => $opt['reason'],
                         'ban_date' => date( 'Y-m-d H:i:s', time() ),
                         'ban_expire' => date( 'Y-m-d H:i:s', $opt['expire'] )
                     ));
             if( $res===false ){
                 if ( defined('DEBUG') )
                     $logger->log('couldnt insert user to blacklist: ' . mysql_error($wpdb->dbh) );
                 return false;
             }
             else{
                 if ( defined('DEBUG') )
                     $logger->log('inserted user to blacklist ');
                 return true;
             }
         }
         else{
            if( defined('DEBUG') )
                $logger->log(sprintf("User %s exists in ban list",$userid));
            
            $res = $wpdb->update(
                     'wp_users_blacklist',
                     array( //what
                        'reason' => $opt['reason'],
                         'ban_date' => date( 'Y-m-d H:i:s', time() ),
                         'ban_expire' => date( 'Y-m-d H:i:s', $opt['expire'] )
                     ),
                    array( //where
                        'user_id' => $userid
                    ));
            if( $res===false ){
                if ( defined('DEBUG') )
                    $logger->log('couldnt update user on blacklist: ' . mysql_error($wpdb->dbh) );
                return false;
            }
            else{
                return true;
            }
         }
     }
     
     function remove_user_ban($userid){
         global $wpdb, $logger;
         
         $res = $wpdb->get_row("SELECT * FROM wp_users_blacklist WHERE user_id = $userid ;",ARRAY_A);
         
         if(!$res){
             if( defined('DEBUG') )
                $logger->log(sprintf("User %s doesnt exist in ban list",$userid));
             return false;
         }
         else{
             if( defined('DEBUG') )
                $logger->log(sprintf("User %s exists in ban list",$userid));
             
             $res = $wpdb->query("
                 DELETE FROM wp_users_blacklist
                 WHERE user_id = $userid
              ");
             
             if($res === false){
                 if ( defined('DEBUG') )
                     $logger->log('couldnt delete user from blacklist: ' . mysql_error($wpdb->dbh) );
                 return false;
             }
             else{
                  if ( defined('DEBUG') )
                     $logger->log("User $userid deleted from blacklist" );
                  
                  return true;
             }
         }
     }
     function is_banned($userid){
         global $wpdb, $logger;
         
         $res = $wpdb->get_var('SELECT user_id FROM wp_users_blacklist;');
         
         if(!$res){
             if( defined('DEBUG') )
                $logger->log("Checking, user $userid is not banned");
             return false;
         }
         else{
             if( defined('DEBUG') )
                $logger->log("Checking, user $userid is on ban list");
             
             $expiration =  $wpdb->get_var("SELECT ban_expire FROM wp_users_blacklist WHERE user_id = $userid ;");
             $expiration = strtotime($expiration);
             
             
             if( $expiration <= time() ){
                  if( defined('DEBUG') )
                    $logger->log("user $userid ban expired");
                  
                   $res = $wpdb->query("DELETE FROM wp_users_blacklist WHERE user_id = $userid ;");
                   if($res===false && defined('DEBUG') )
                        $logger->log('couldnt delete user from blacklist: ' . mysql_error($wpdb->dbh) );
                  return false;
             }
             else{
                 if( defined('DEBUG') )
                    $logger->log("user $userid is still banned");

                  return true;
             }
             
         }
         
     }
     
     function add_post_tag($postid,$userid,$term){
         global $logger,$wpdb;
         
         $result = array();
         
         /*check if user can add tags */
         if( $this->is_banned($userid) ){
             if( defined('DEBUG') )
                 $logger->log("Cant add tag to post: user $userid is banned");
             $result['success'] = false;
             $result['reason'] = 'banned';
             return $result;
         }
         
       
         /*add user-post-term entry if it doesnt exist yet */
         if( !($res = term_exists($term,'post_tag')) ){
             $res = wp_insert_term($term, 'post_tag');
             if( defined('DEBUG') )
                 $logger->log("Term $term doesnt exist. Inserting. Result: " .var_export($res,true) );
         }
 
         $alreadyadded = $wpdb->query("
           SELECT * FROM wp_users_terms_posts
           WHERE post_id = $postid
           AND user_id = $userid
           AND term_id =". $res['term_id'] .";
        ");
         
         if($alreadyadded){
              if( defined('DEBUG') )
                  $logger->log("User $userid already added $term to post $postid");
              
              $result['success'] = false;
              $result['reason'] = 'exists';
              return $result;
         }
         else{
              if( defined('DEBUG') )
                  $logger->log("User $userid havent yet added $term to post $postid");
              
              $wpdb->insert(
                      'wp_users_terms_posts',
                      array('user_id' => $userid, 'post_id' => $postid, 'term_id' => $res['term_id'], 'term_date' => date( 'Y-m-d H:i:s', time() ))
              );  
         }
         
         /* attach tag to post if not yet attached */
         if( !in_array( $postid, get_objects_in_term($res['term_id'], 'post_tag') ) ){
               if( defined('DEBUG') )
                   $logger->log("Term $term not yet attached to post $postid");
               wp_set_object_terms($postid, intval($res['term_id']), 'post_tag', $append=true);
              
         }
         else{
             if( defined('DEBUG') )
                   $logger->log("Term $term already attached to post $postid"); 
             $result['success'] = false;
             $result['reason'] = 'attached';
             return $result;
         }
         
         $result['success'] = true;
         return $result;
     }
     
     function get_all_tags(){
         global $wpdb,$logger;
         
         $res = $wpdb->get_results("
           SELECT slug, name FROM $wpdb->terms
           INNER JOIN $wpdb->term_taxonomy
           ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id
           WHERE taxonomy LIKE 'post_tag';
        ",ARRAY_A);
         if(!$res){
             if( defined('DEBUG') )
                 $logger->log('error retrieving tags: ' . mysql_error($wpdb->dbh)); 
             
             return null;
         }
         else{
              if( defined('DEBUG') ){
                 $logger->log('tags retrieved');  
                 $logger->log(var_export($res, true));
              }
             return $res;
         }
     }
     
     function get_most_active_taggers($opts){
         global $wpdb,$logger;
         
         $defaults = array(
            'user_count' => 10,
            'hours_ago' => 3,
            'minutes_ago' => 0,
            'seconds_ago' => 0
         );
         
         $opts = wp_parse_args($opts,$defaults);
         
         $topusers = $opts['user_count'];
         $mysqltime = date(
                 'Y-m-d H:i:s',
                 time() - 3600 * $opts['hours_ago'] - 60 * $opts['minutes_ago'] - $opts['seconds_ago']
         );
         
         $res = $wpdb->get_results("
            SELECT user_id, COUNT(term_id) AS 'tag_count' FROM wp_users_terms_posts
            WHERE term_date > '$mysqltime'
            GROUP BY user_id
            ORDER BY COUNT(term_id) DESC
            LIMIT 0,$topusers ;
          ",ARRAY_A);
         
          if(!$res){
             if( defined('DEBUG') )
                 $logger->log('error retrieving taggers: ' . mysql_error($wpdb->dbh)); 
             
             return null;
         }
         else{
              if( defined('DEBUG') ){
                 $logger->log('taggers retrieved');  
                 $logger->log(var_export($res, true));
              }
             return $res;
         }
     }
     
     function get_most_popular_tags($opts){
         global $wpdb,$logger;
         
         $defaults = array(
            'tag_count' => 10,
            'hours_ago' => 3,
            'minutes_ago' => 0,
            'seconds_ago' => 0
         );
         $opts = wp_parse_args($opts,$defaults);
         
         $mysqltime = date(
                 'Y-m-d H:i:s',
                 time() - 3600 * $opts['hours_ago'] - 60 * $opts['minutes_ago'] - $opts['seconds_ago']
         );
         $toptags = $opts['tag_count'];
        
         
          $res = $wpdb->get_results("
            SELECT term_id, COUNT( user_id ) AS 'user_count'
            FROM wp_users_terms_posts
            WHERE term_date > '$mysqltime'
            GROUP BY term_id
            ORDER BY COUNT( user_id ) DESC
            LIMIT 0 , $toptags ;
          ",ARRAY_A);
          
           if(!$res){
             if( defined('DEBUG') )
                 $logger->log('error retrieving popular tags: ' . mysql_error($wpdb->dbh)); 
             
             return null;
         }
         else{
              if( defined('DEBUG') ){
                 $logger->log('popular tags retrieved');  
                 $logger->log(var_export($res, true));
              }
             return $res;
         }
     }
     function get_recent_tags($opts){
           global $wpdb,$logger;
         
         $defaults = array(
            'tag_count' => 10,
            'hours_ago' => 3,
            'minutes_ago' => 0,
            'seconds_ago' => 0
         );
         $opts = wp_parse_args($opts,$defaults);
         
         $mysqltime = date(
                 'Y-m-d H:i:s',
                 time() - 3600 * $opts['hours_ago'] - 60 * $opts['minutes_ago'] - $opts['seconds_ago']
         );
         $toptags = $opts['tag_count'];
         
           $res = $wpdb->get_results("
            SELECT term_id, user_id, post_id, term_date
            FROM wp_users_terms_posts
            WHERE term_date > '$mysqltime'
            ORDER BY term_date DESC
            LIMIT 0 , $toptags ;
          ",ARRAY_A);
          
           if(!$res){
             if( defined('DEBUG') )
                 $logger->log('error retrieving recent tags: ' . mysql_error($wpdb->dbh)); 
             
             return null;
         }
         else{
              if( defined('DEBUG') ){
                 $logger->log('recent tags retrieved');  
                 $logger->log(var_export($res, true));
              }
             return $res;
         }
         
     }
     
     
}

$GLOBALS['etdb'] = new ET_Database();
global $etdb;

?>
