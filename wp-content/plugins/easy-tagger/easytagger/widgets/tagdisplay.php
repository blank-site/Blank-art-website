<?php


class TagsDisplay extends WP_Widget
{
    function __construct()
    {
        parent::__construct('tagdisplay','ET Tags Display For Posts');
    }
    function form($instance)
    {
        $defaults = array(
            'show_all' => true,
            'caption' => 'Assigned tags'
        );
        $instance = wp_parse_args((array)$instance,$defaults);
        $field1_id = $this->get_field_id('show_all');
        $field1_name = $this->get_field_name('show_all');
        $field2_id = $this->get_field_id('caption');
        $field2_name = $this->get_field_name('caption');
        ?>
        <p>
            <label for="<?php echo $field1_id; ?>">
                <?php _e('Show all: ','easytagger'); ?>
                <input type="checkbox" name="<?php echo $field1_name; ?> " id="<?php echo $field1_id; ?>"
                       value="1" <?php checked($instance['show_all']); ?>/>
            </label>
        </p>
         <p>
            <label for="<?php echo $field2_id ?>">
                <?php _e('Caption under which to display tags:','easytagger'); ?>
                <input type="text" name="<?php echo $field2_name; ?>" id="<?php echo $field2_id; ?>"
                       value="<?php echo $instance['caption']; ?>" />
            </label>
        </p>
        <?php
        
    }
    function update($new_instance,$old_instance)
    {
        $instance = $old_instance;
        $instance['caption'] = strip_tags($new_instance['caption']);
        $instance['show_all'] = $new_instance['show_all'];
        
        return $instance;
    }
    function widget($args,$instance)
    {
        global $post;
        
        extract($args,EXTR_SKIP);
        if(!is_single())
        {
            echo '<p>'. __('This widget works only with posts','easytagger') . '</p>';
        }
        else
        {
            $show_all = $instance['show_all'];
            $caption = $instance['caption'];
            
            ?> <h3><?php echo $caption ?></h3>
            <?php
            
            $tags = get_tags();
          
            $tagscount = count($tags);   
            if($show_all || $tagscount <= 10)
                $limit = $tagscount;
            else
                $limit = 10;
            
         
            $text='';
            for($i=0;$i<$limit;$i++)
            {
                $text .= $tags[$i]->name . ' '; 
            }
            ?><p> <?php echo $text ?></p><?
                
         }
    }
}



add_action(
        'widgets_init',
        create_function('','register_widget("TagsDisplay");')
);

?>
