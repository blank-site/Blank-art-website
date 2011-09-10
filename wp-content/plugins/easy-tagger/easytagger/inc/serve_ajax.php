<?php

function fetch_featured_thumbnails()
{
    //defaults
    $imagesize = 'thumbnail';
    
   
   //request arguments
   if( isset($_POST['isize']) ) $imagesize = $_POST['isize'];
   
   $query_args = array('post_status'=>'publish','posts_per_page'=>-1);
   if( isset($_POST['tag_slug']) )
       $query_args['tag'] = $_POST['tag_slug'];
    
   $query = new WP_Query( $query_args );
   
   $attachments = array();
   
   $xml = new SimpleXMLElement('<root/>');
   
   foreach($query->get_posts() as $p)
   {
       $srcobj =  wp_get_attachment_image_src( get_post_thumbnail_id($p->ID), $imagesize );
       
       if($srcobj){
           $obj = array();

           $obj['id'] = $p->ID;
           $obj['post_name'] = $p->post_name;
           $obj['post_title'] = $p->post_title;

           $obj['thumb_url'] = $srcobj[0];
           $obj['thumb_width'] = $srcobj[1];
           $obj['thumb_height'] = $srcobj[2];

           $obj = array_flip($obj);
           $entity = $xml->addChild('entity');

           array_walk_recursive($obj, array ($entity, 'addChild'));
       }
   }
   echo $xml->asXML();
   die();
}

function add_tags(){
    global $etdb;
    
    function _try_add($tag){
        global $etdb;
        
        $x = $etdb->add_post_tag($_POST['pid'], $_POST['uid'],$tag);
        return $x['success']; 
    }
   
    $xml = new SimpleXMLElement('<root/>');
    
    if( !isset($_POST['new_tags']) ){
        $xml->addChild('result','error');
        $xml->addChild('reason','no tags to add');
        echo $xml->asXML();
        die();
    }
    else if( !isset($_POST['pid']) || !isset($_POST['uid']) ){
        $xml->addChild('result','error');
        $xml->addChild('reason','user id or post id not set');
        echo $xml->asXML();
        die();
    }
    else if( !isset($etdb) ){
        $xml->addChild('result','error');
        $xml->addChild('reason','etdb not set');
        echo $xml->asXML();
        die();
    }
    else if( $etdb->is_banned($_POST['uid']) ){
        $xml->addChild('result','error');
        $xml->addChild('reason','user is banned');
        echo $xml->asXML();
        die();
    }
    else{
        $accepted = array_filter($_POST['new_tags'],'_try_add');
        if(! $accepted){
            $xml->addChild('result','error');
            $xml->addChild('reason','all tags already exist');
            echo $xml->asXML();
            die();  
        }
        $xml->addChild('result','success');
        foreach($accepted as $acc){
            $xml->addChild('accepted',$acc);
        }
        echo $xml->asXML();
        die();
               
    }  
}

function fetch_tags(){
    global $etdb;
    
    $xml = new SimpleXMLElement('<root/>');
    
    if( !isset($etdb) ){
        $xml->addChild('result','error');
        $xml->addChild('reason','etdb not set');
        echo $xml->asXML();
        die();
    }
    else{
       $ret = $etdb->get_all_tags();
       if(!$ret){
            $xml->addChild('result','error');
            $xml->addChild('reason','database error');
       }
       else{
           $xml->addChild('result','success');
           foreach($ret as $r){
               $element = $xml->addChild('tag');
               $element->addChild('slug', $r['slug']);
               $element->addChild('name',$r['name']);
           }
       }
       echo $xml->asXML();
       die();
    }
}

function add_ban(){
    global $etdb;
    
     $xml = new SimpleXMLElement('<root/>');
     
     if( !isset($etdb) ){
        $xml->addChild('result','error');
        $xml->addChild('reason','etdb not set');
        echo $xml->asXML();
        die();
    }
    else{
        $uid = $_POST['id'];
        $reason = $_POST['reason'];
        $expire = $_POST['expire'];
        
        $res = $etdb->give_user_ban($uid,array('reason' => $reason, 'expire' => strtotime($expire) ) );
        
        if(!$res){
            $xml->addChild('result','error');
            $xml->addChild('reason','database error');
            echo $xml->asXML();
            die();
        }
        else{
             $xml->addChild('result','success');
             echo $xml->asXML();
             die();
        }
    }
}

function remove_ban(){
     global $etdb;
    
     $xml = new SimpleXMLElement('<root/>');
     
     if( !isset($etdb) ){
        $xml->addChild('result','error');
        $xml->addChild('reason','etdb not set');
        echo $xml->asXML();
        die();
    }
    else{
        $uid = $_POST['id'];
        $res = $etdb->remove_user_ban($uid);
        
        if(!$res){
            $xml->addChild('result','error');
            $xml->addChild('reason','database error');
            echo $xml->asXML();
            die();
        }
        else{
             $xml->addChild('result','success');
             echo $xml->asXML();
             die();
        }
        
    }
}


?>
