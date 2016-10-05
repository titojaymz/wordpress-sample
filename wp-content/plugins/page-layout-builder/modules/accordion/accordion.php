<?php
/*
Plugin Name: Accordion 
Plugin URI: #
Description: Accordion For MiniMax
Author: Shaon
Version: 1.6
Author URI: #
*/

class MiniMax_accordion extends WP_Widget {

    function __construct() {
        parent::__construct( 'MiniMax_accordion', 'Accordion', array( 'description' => 'Accordion Widget' ) );
        
        if( is_admin() ){ wp_enqueue_style('accor-css',base_theme_url.'/modules/accordion/css/admin.css'); }
    }

    function widget( $args, $instance ) {
        extract( $args );
        extract( $instance );

        if( !isset($accordion_style) || $accordion_style =='' || $accordion_style =='whead-style-2') $accordion_style = 'default';
        
        echo $before_widget;
            include("bootstrap_accordion.php");
        echo $after_widget;
    }

    function preview( $instance ){
        extract( $instance );
        include("accordion_preview.php");
    }

    function update( $new_instance, $old_instance ) {
        $instance = $new_instance;       
        return $instance;
    }

    function form( $instance ){
        extract($instance);
        if(!isset($accordion_style)) $accordion_style = 'default';
        
        $accordion_posts = get_posts("post_type=minimax_accordion&posts_per_page=-1");    
        ?>
        <div id="tabpane">
        <!--left box-->
        <div style="padding-top: 0;" id="poststuff" class="left_box postbox">
        <h3 class="hndle"><span>Inactive Accordion</span></h3>
        <ul class="accordion_ul" id="inactive_accordion">
        <?php
            foreach($accordion_posts as $key => $accordion_post){
                $flag = 0;
               if($pid){
                   for($i = 0; $i < count($pid); $i++ ){
                       if($accordion_post->ID == $pid[$i]){$flag=1;break;}
                   }
               }
                if($flag == 0)
                echo "<li class='ui-state-default' rel='".$accordion_post->ID."' id='p_".$accordion_post->ID."'><span style='padding:0px;margin:0px;'>".$accordion_post->post_title."<small class='accordion-small'></small></span></li>";
            }
        ?>
        </ul>
        </div>
        <!--right box-->
        <div  style="padding-top: 0;" id="poststuff" class="right_box postbox " >
        <h3 class="hndle"><span>Active Accordion</span></h3>
        <ul class="accordion_ul" id="active_accordion">
         <?php
            if(!empty($pid) > 0){
            for($i = 0; $i < count($pid); $i++ ){
                $pimg = get_post($pid[$i]);
                ?>
                <li class='ui-state-default' rel='<?php echo $pid[$i];?>'>
                    <span style='padding:0px;margin:0px;'><?php echo $pimg->post_title; ?><small class='accordion-small'></small></span>
                    <input id="i_<?php echo $pid[$i];?>" name="<?php echo $this->get_field_name('pid'); ?>[]" type="hidden" value="<?php echo $pid[$i]; ?>" />
                </li>

                <?php
            }
            }
        ?>
        </ul>
        </div>
        <p>
            <label for="<?php echo $this->get_field_id('accordion_style'); ?>"><?php _e('Accordion Style:'); ?></label>      
            <select class="widefat" name="<?php echo $this->get_field_name('accordion_style');?>">
                <option value="default" <?php if($accordion_style=='default') echo 'selected=selected'; ?> >Gray</option>
                <option value="primary" <?php if($accordion_style=='primary') echo 'selected=selected'; ?> >Blue</option>        
                <option value="warning" <?php if($accordion_style=='warning') echo 'selected=selected'; ?> >Orange</option>
                <option value="success" <?php if($accordion_style=='success') echo 'selected=selected'; ?> >Green</option> 
                <option value="danger" <?php if($accordion_style=='danger') echo 'selected=selected'; ?> >Red</option>
                <option value="info" <?php if($accordion_style=='info') echo 'selected=selected'; ?> >Turquoise Blue</option>
            </select>
        </p>
        <p>
            <label>
                    <input type="checkbox" id="<?php echo $this->get_field_id('closed'); ?>" name="<?php echo $this->get_field_name('closed'); ?>" value="closed" <?php if($closed == "closed") echo "checked=checked";?> > Collapse all Accordion tabs on page load
            </label>
        </p>
        <div style="clear: both;"></div>

        <p class="settings-info">Accordions are custom posts. Add new Accordion from <b>Dashboard >> Accordions >> Add New</b> menu. Drag Accordions from left to right box to insert them into page.</p>
        
        </div>
        
        <script type="text/javascript">                        
        jQuery(document).ready(function(){
            var c = <?php echo count($title) > 0 ? count($title):0; ?>;
            jQuery( "#inactive_accordion, #active_accordion" ).sortable({
                connectWith: ".accordion_ul"
               
            }).disableSelection();
            
            jQuery( "#active_accordion" ).sortable({
                receive: handlereceiveEvent,
                remove: handleremoveEvent
            });
        
            function handlereceiveEvent( event, ui ) {
              var item = ui.item;
              jQuery('#active_accordion').append('<input id="i_' + item.attr('rel') + '" name="<?php echo $this->get_field_name('pid');?>[]" type="hidden" value="' + item.attr('rel') + '" />');             
              //alert( 'The square with class "' + item.attr('id') + '" was dropped onto me!' );
            }
            
            function handleremoveEvent(event, ui){
                 var item = ui.item;
                 //alert( 'The square with class "' + item.attr('rel') + '" was dropped onto me!' );
                jQuery('#i_'+item.attr('rel')).remove();
            }
            window.onload = select_slide('<?php echo $accordion_name;?>');
            
            function select_slide(id){
                jQuery('.control').fadeOut();
                jQuery('#'+id+"_control").fadeIn();
            }
            
            jQuery('#<?php echo $this->get_field_id('accordion_name'); ?>').change(function(){
                jQuery('.control').fadeOut();
                select_slide(jQuery(this).val());
            });
        });
        </script>
        <?php 
    }
}

add_action( 'widgets_init', create_function( '', 'register_widget("MiniMax_accordion");' ) );