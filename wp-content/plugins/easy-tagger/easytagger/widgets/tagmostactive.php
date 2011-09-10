<?php


class MostActiveTaggers extends WP_Widget
{
    function __construct()
    {
        parent::__construct('mostactivetaggers','ET Most Active Taggers');
    }
    function form($instance)
    {
        $defaults = array(
            'top_count' => 10,
            'caption' => 'Most active users',
             'hours_ago' => 1,
            'minutes_ago' => 0
        );
        $instance = wp_parse_args((array)$instance,$defaults);
        $field1_id = $this->get_field_id('top_count');
        $field1_name = $this->get_field_name('top_count');
        $field2_id = $this->get_field_id('caption');
        $field2_name = $this->get_field_name('caption');
        $field3_id = $this->get_field_id('hours_ago');
        $field3_name = $this->get_field_name('hours_ago');
        $field4_id = $this->get_field_id('minutes_ago');
        $field4_name = $this->get_field_name('minutes_ago');
        ?>
        <p>
            <label for="<?php echo $field1_id; ?>">
                <?php _e('How many top users to display:','easytagger'); ?> <br/>
                <input type="text" name="<?php echo $field1_name; ?> " id="<?php echo $field1_id; ?>"
                       value="<?php echo $instance['top_count']; ?>" />
            </label>
        </p>
         <p>
            <label for="<?php echo $field2_id ?>">
                <?php _e('Caption for the list:','easytagger'); ?> <br/>
                <input type="text" name="<?php echo $field2_name; ?>" id="<?php echo $field2_id; ?>"
                       value="<?php echo $instance['caption']; ?>" />
            </label>
        </p>
            <p>
            <label for="<?php echo $field3_id ?>">
                <?php _e('Since hours ago:','easytagger'); ?> <br/>
                <input type="text" name="<?php echo $field3_name; ?>" id="<?php echo $field3_id; ?>"
                       value="<?php echo $instance['hours_ago']; ?>" />
            </label>
        </p>
          <p>
            <label for="<?php echo $field4_id ?>">
                <?php _e('+ minutes ago:','easytagger'); ?> <br/>
                <input type="text" name="<?php echo $field4_name; ?>" id="<?php echo $field4_id; ?>"
                       value="<?php echo $instance['minutes_ago']; ?>" />
            </label>
        </p>
        <?php
        
    }
    function update($new_instance,$old_instance)
    {
        $instance = $old_instance;
        $instance['caption'] = strip_tags($new_instance['caption']);
        $instance['top_count'] = $new_instance['top_count'];
         $instance['hours_ago'] = $new_instance['hours_ago'];
        $instance['minutes_ago'] = $new_instance['minutes_ago'];
        
        return $instance;
    }
    function widget($args,$instance)
    {    
        global $etdb;
        
        extract($args,EXTR_SKIP);
        $top = intval( $instance['top_count'] );
        $caption = $instance['caption'];
        $hr = intval( $instance['hours_ago']);
        $min = intval( $instance['minutes_ago']);
          
        echo $before_widget;
        ?> <h3><?php echo $caption ?></h3>
        <?php
        $entries = $etdb->get_most_active_taggers(
           array(
               'user_count' => $instance['top_count'],
                'hours_ago' =>$hr,
               'minutes_ago' => $min
           )     
         );
        if(!$entries){
            echo '<p>' . __('No recent active taggers','easytagger').'</p>';
        }
        else{
           foreach($entries as $e){
                ?>
                <p>
                    <strong><?php echo get_userdata($e['user_id'])->user_nicename; ?></strong>
                     <?php _e('added','easytagger'); ?> 
                    <strong><?php echo $e['tag_count'];?></strong> 
                    <?php
                        if($e['tag_count'] > 1)
                            _e('tags','easytagger');
                        else    
                            echo 'tag';
                    ?>
                </p>
                <?php
            }    
        }
        echo $after_widget;
                
   }
}



add_action(
        'widgets_init',
        create_function('','register_widget("MostActiveTaggers");')
);


?>
