<?php

function nice_time($time){
    if( !$time)
        return '';
    
    if( !is_numeric($time) )
        $time = strtotime ($time);
    
    $time_diff = time() - $time;
    
    if( ($hr = intval($time_diff / 3600 )) > 1)
        return sprintf(__("%d hours ago",'easytagger'),$hr);
    else if( ($hr = intval($time_diff / 3600 )) > 0)
        return __("hour ago",'easytagger');
    else if(($min = intval($time_diff / 60)) > 1)
        return sprintf(__("%d minutes ago",'easytagger'),$min);
    else if(($min = intval($time_diff / 60)) > 0)
        return __("minute ago",'easytagger');
    else 
        return sprintf(__("%d seconds ago",'easytagger'),$time_diff);
}

function setup_admin_settings_page(){
    add_menu_page('Easy Tagger Options','Easy Tagger','manage_options','easytagger_menu','print_options_page');
}
function print_options_page(){
    global $etdb;
    
    ?>
    <div class="wrap" style="font-size:13px;">
        <h2><?php _e('Easy Tagger configuration','easytagger'); ?></h2>
        <input id="adminadminurl" type="hidden" value="<?php echo admin_url('admin-ajax.php'); ?>" ></input>
        
        <div style="width:850px;height:1200px;">
            <h3><?php _e('Users administration','easytagger'); ?></h3>
            <p><?php _e('In this section you can prevent malicious users from tagging. Simply pick  one users from first '.
            'list on the left, and enter expiration date and reason (both are required). Notice bans are automatically '.
            'removed by this plugin after expiration. Reload page to see how lists changed.','easytagger'); ?></p>

            <div class="postbox" id="user_list" style="width:350px;height:500px;overflow:auto;padding:0;margin:10px 0px 0px 10px;float:left;">

                <h3 class="hndle" style="padding-top:5px;padding-bottom: 5px;padding-left: 10px;" ><?php _e('Blog users','easytagger'); ?></h3>

                <div id="et-container" style="padding-left:1em;">
                    <table id="et-users" class="wp-list-table widefat" style="width:200px;cursor: default;">
                        <col width="20px">
                        <col width="50px">
                        <col width="50px">
                        <col width="100px">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th><?php _e('User','easytagger'); ?></th>
                                <th><?php _e('Status','easytagger'); ?></th>
                                <th><?php _e('Email','easytagger'); ?></th>
                            </tr>
                        </thead>
                        <tbody style="text-align: center;">
                             <?php
                                  if( !isset($etdb) )
                                      echo '<tr> Error: etdb not set </tr>';
                                  else{
                                      $users = $etdb->get_users();
                                      foreach($users as $u){
                                          $status = $u['banned'] ? 'banned' : 'clear';

                                          echo "".
                                          "<tr>".
                                          "<td id=\"ID\">".$u['ID']."</td>".
                                          "<td id=\"nicename\">".$u['user_nicename']."</td>".
                                          "<td id=\"status\">$status</td>".
                                          "<td id=\"email\">".$u['user_email']."</td>".
                                          "</tr>";
                                      }
                                  }
                              ?>
                        </tbody>
                    </table>
                    <div class="submit">
                        <label for="ban_expire"><?php _e('Expires (Y-m-d H:i:s):','easytagger'); ?> </label>
                        <input type="text" id="ban_expire" name="ban_expire" value=""/><br/>
                        <label for="ban_reason"><?php _e('Reason:','easytagger'); ?> </label>
                        <input type="text" id="ban_reason" name="ban_reason" value=""/>
                        <input style="" type="submit" class="button-primary" id="ban_submit" value="<?php _e('Give Ban','easytagger'); ?>" /> <br/>
                        <p style="background:#fecac2;width:300px;text-align: center;border: 1px solid #E6DCFF; display:none; " id="ban_warning"  ></p>
                    </div>
                </div>
            </div>    

             <div class="postbox" id="user_list" style="width:450px;height:500px;overflow:auto;padding:0;margin:10px 0px 0px 10px;float:left;">

                <h3 class="hndle" style="padding-top:5px;padding-bottom: 5px;padding-left: 10px;" ><?php _e('Users banned from tagging','easytagger'); ?></h3>

                <div id="et-container2" style="padding-left:1em;">
                    <table id="et-villains" class="wp-list-table widefat" style="width:420px;cursor: default;">
                        <col width="20px">
                        <col width="50px">
                        <col width="150px">
                        <col width="150px">
                        <col width="100px">
                        <thead>
                            <tr>
                                <th><?php _e('ID','easytagger'); ?></th>
                                <th><?php _e('User','easytagger'); ?></th>
                                <th><?php _e('Reason','easytagger'); ?></th>
                                <th><?php _e('Ban Date(GMT+O)','easytagger'); ?></th>
                                <th><?php _e('Expires','easytagger'); ?></th>
                            </tr>
                        </thead>
                        <tbody style="text-align: center;">
                             <?php
                                  if( !isset($etdb) )
                                      echo '<tr> Error: etdb not set </tr>';
                                  else{
                                      $users = $etdb->get_banned_users();
                                      if($users){                             
                                          foreach($users as $u){

                                              echo "".
                                              "<tr>".
                                              "<td id=\"ID2\">".$u['user_id']."</td>".
                                              "<td id=\"nicename2\">".$u['user_nicename']."</td>".
                                              "<td id=\"reason\">".trim($u['reason'])."</td>".
                                              "<td id=\"bdate\">".$u['ban_date']."</td>".
                                              "<td id=\"bexpire\">".$u['ban_expire']."</td>".
                                              "</tr>";
                                          }
                                      }
                                  }
                              ?>
                        </tbody>
                    </table>
                    <div style="position:relative;" class="submit">
                       <p style="background:#fecac2;width:250px;text-align: center;border: 1px solid #E6DCFF;display:none  " id="unban_warning"  >text</p>
                       <input style="position:absolute;left:300px;top:30px;" type="submit" class="button-primary" id="unban_submit" value="<?php _e('Remove Ban','easytagger'); ?>" />
                    </div>
                </div>
            </div>  
            <div class="submit">
                <form method="post" action="options.php">
                      <h3 style="clear:both;padding-top:20px;"><?php _e('Tag Search Redirection','easytagger'); ?></h3>
                <p ><?php _e('One of Easy Tagger widgets \'Tag Search\' allows to search posts '.
                'based on assigned tags. Redirection defaults to wordpress search loop, you can also redirect to custom page containing '.
                'Easy Tagger`s another widget \'Featured Images Grid\'. If so, copy permalink to text field below.','easytagger'); ?></p>
                   <?php settings_fields('et_options'); ?>
                    <select id="et_redirection" name="et_redirection" >
                         <option value="1"  <?php if(get_option('et_redirection')=='1') echo 'selected="selected"'; ?> ><?php _e('Wordpress Search Loop','easytagger'); ?></option>
                         <option value="2" <?php if(get_option('et_redirection')=='2') echo 'selected="selected"'; ?> ><?php _e('Custom Page Show All Posts','easytagger'); ?></option>
                         <option value="3" <?php if(get_option('et_redirection')=='3') echo 'selected="selected"'; ?> ><?php _e('Custom Page Show Posts with given tag','easytagger'); ?></option>
                    </select>
                    <input type="submit" class="button-primary" name="Submit" value="<?php _e('Save Changes','easytagger'); ?>" /><br/>
                    <label for="et_redirect_url"><?php _e('Redirect to:','easytagger'); ?></label>
                    <input size="50" type="text" id="et_redirect_url" name="et_redirect_url" disabled value="<?php echo get_option('et_redirect_url'); ?>"></input>

                    <h3><?php _e('Featured Images Grid Options','easytagger'); ?></h3>
                    <p><?php _e('This widget displays in grid featured images of posts, which are links, also. You can display this widget on 3 different ways: '.
                    '1)drag widget on the sidebar 2)insert shortcode [etgrid] when publishing post/page 3)add template tag <?php et_images_grid()?> '.
                    'to your custom template. Here you can set options how to render this grid.','easytagger'); ?></p>

                    <label for="et_grid_image_width"><?php  _e('ET Featured Images Grid Image Width:','easytagger'); ?> </label>
                    <input id="et_grid_image_width" name="et_grid_image_width" value="<?php echo get_option('et_grid_image_width'); ?>" size="5"></input>
                    <br/> <label for="et_grid_image_height"><?php _e('ET Featured Images Grid Image Height:','easytagger'); ?> </label>
                    <input id="et_grid_image_height" name="et_grid_image_height" value="<?php echo get_option('et_grid_image_height'); ?>" size="5"></input>
                     <br/><label for="et_grid_rows"><?php _e('ET Featured Images Grid Rows:','easytagger'); ?></label>
                    <input id="et_grid_rows" name="et_grid_rows" value="<?php echo get_option('et_grid_rows'); ?>" size="5"></input>
                    <br/> <label for="et_grid_columns"><?php _e('ET Featured Images Grid Columns:','easytagger'); ?></label>
                    <input id="et_grid_columns" name="et_grid_columns" value="<?php echo get_option('et_grid_columns'); ?>" size="5"></input>
                    <br/> <input type="submit" class="button-primary" name="Submit2" value="<?php _e('Save Changes','easytagger'); ?>" />
                </form>
            </div>
        </div>
    </div>
    <?php 
}

function admin_scripts(){
    wp_enqueue_script('adminbancontrol',ET_URL . 'inc/js/adminBanControl.js',array('jquery') );
    wp_localize_script('adminbancontrol', 'l10nobject', 
            array(
                'success_msg' => __("Success",'easytagger'),
                'noexp_msg' => __('No ban expiration date!','easytagger'),
                'noreason_msg' => __('No ban reason given!','easytagger'),
                'wrongformat_msg' => __('Wrong expire date format','easytagger')
             )
    );
}
function et_settings(){
    register_setting('et_options','et_redirection');
    register_setting('et_options','et_redirect_url');
    register_setting('et_options','et_grid_image_width');
    register_setting('et_options','et_grid_image_height');
    register_setting('et_options','et_grid_rows');
    register_setting('et_options','et_grid_columns');     
}

/* localization */
function text_domain(){
    load_plugin_textdomain('easytagger', false, basename(ET_URL).'/languages');
}



add_action('admin_menu','setup_admin_settings_page');
add_action('admin_enqueue_scripts','admin_scripts');
add_action('wp_ajax_add_ban','add_ban');
add_action('wp_ajax_remove_ban','remove_ban');
add_action('admin_init','et_settings');
add_action('plugins_loaded','text_domain');

?>
