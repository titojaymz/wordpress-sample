<?php

global $mxwidgets, $minimax_layout_data, $minimax_modules, $wp_widget_factory, $minimax_modules_settings, $minimax_options, $minimax_layout_settings;
define("base_theme_url", plugins_url('page-layout-builder'));

include("metabox.php");

error_reporting(0);
ini_set('display_errors', 0);

/* 
 * Load minimax active theme option data 
 */
function minimax_load_theme_option_data(){
    global $minimax_layout_data, $minimax_modules, $minimax_modules_settings, $minimax_options;
    $minimax_options = get_option("wpeden_admin");
    $minimax_layout_data = get_option('minimax_layout',array());    
    $minimax_layout_settings = get_option('minimax_layout_settings',array());        
    $minimax_modules = get_option('minimax_modules',array());        
    $minimax_modules_settings = get_option('minimax_modules_settings',array());        
    if(isset($_GET['post'])&&$_GET['post']!=''){           
    $minimax_layout_settings = get_post_meta($_GET['post'],'minimax_layout_settings',true);                     
    $minimax_layout_data = get_post_meta($_GET['post'],'minimax_layout',true);     
    $minimax_modules = get_post_meta($_GET['post'],'minimax_modules',true);        
    $minimax_modules_settings = get_post_meta($_GET['post'],'minimax_modules_settings',true);        
    }
}

/**
 * Load active WP Widgets
 */
function minimax_wp_widgets(){
    global $mxwidgets, $wp_widget_factory;    
    $mxwidgets = $wp_widget_factory->widgets;
}

/**
 * Layout holder (site)
 */
function minimax_layout_holder($name){
    global $minimax_layout_holder, $minimax_layout_data, $minimax_modules;     
    if(is_array($minimax_layout_data[$name])){
        foreach($minimax_layout_data[$name] as $id=>$layout):
            minimax_render_layout($layout, $id, $name);
        endforeach;
    }
}

/**
 * Page layout renderer (site)
 */
function minimax_page_layout($content){
     $pid = get_the_ID();

    if( file_exists(MX_CACHE_DIR.$pid) && get_option('minimax_cahce_status',0) == 1 && get_option('minimax_frontend_editing',0) == 0 )
            return $content.file_get_contents(MX_CACHE_DIR.$pid);

    ob_start();
    $minimax_layout_data = get_post_meta($pid,'minimax_layout',true);
    //$minimax_modules = get_post_meta($pid,'minimax_modules',true);
    //$minimax_modules_settings = get_post_meta($pid,'minimax_modules_settings',true);

    if(is_array($minimax_layout_data[get_post_type()])):

        echo "<div class='w3eden' id='layout_".get_post_type()."'>";
        if(current_user_can('edit_posts') && get_option('minimax_frontend_editing',0) == 1) echo "<ul class='layout-data mxrows'>";

        foreach($minimax_layout_data[get_post_type()] as $id => $layout):
            minimax_render_layout( $layout, $id, get_post_type() );
        endforeach;

        if(current_user_can('edit_posts') && get_option('minimax_frontend_editing',0) == 1) echo "</ul>";
        echo "</div>";

    endif;

    $data = ob_get_clean();

    if(get_option('minimax_cahce_status',0) == 1 && get_option('minimax_frontend_editing',0) == 0)
        file_put_contents(MX_CACHE_DIR.$pid, $data);

    $dialog = "";

    if(current_user_can('edit_posts') && get_option('minimax_frontend_editing',0)==1 && (is_page()||is_single())):
        $dialog =   '<div id="dialog" title="Basic dialog">Dialog</div>
                    <input type="hidden" name="frontend_minimax" value="1" class="mx-input"/>
                    <input type="hidden" name="post_id" value="'.$pid.'" class="mx-input"/>
                    <div id="mx-toolbar" class="w3eden">
                    <div class="panel panel-default">
                        <div class="panel-heading">Insert Row <a class="pull-right btn btn-xs btn-danger" title="Disable Front-end Editing"  href="'.home_url('/?mxfrontend=0').'"><i class="fa fa-times"></i></a></div>
                        <div class="panel-body">
                        <a href="#" rel="col-1" holder="#layout_'.get_post_type().'" class="insert-layout btn btn-sm btn-default btn-block"><i class="fa fa-plus-circle"></i>1 Col Row</a>
                        <a href="#" rel="col-2" holder="#layout_'.get_post_type().'" class="insert-layout btn btn-sm btn-default btn-block"><i class="fa fa-plus-circle"></i>2 Cols Row</a>
                        <a href="#" rel="col-3" holder="#layout_'.get_post_type().'" class="insert-layout btn btn-sm btn-default btn-block"><i class="fa fa-plus-circle"></i>3 Cols Row</a>
                        <a href="#" rel="col-4" holder="#layout_'.get_post_type().'" class="insert-layout btn btn-sm btn-default btn-block"><i class="fa fa-plus-circle"></i>4 Cols Row</a>
                        <a href="#" rel="col-5" holder="#layout_'.get_post_type().'" class="insert-layout btn btn-sm btn-default btn-block"><i class="fa fa-plus-circle"></i>5 Cols Row</a>
                        <a href="#" rel="col-6" holder="#layout_'.get_post_type().'" class="insert-layout btn btn-sm btn-default btn-block"><i class="fa fa-plus-circle"></i>6 Cols Row</a>
                        </div>
                        <div class="panel-footer">
                            <button class="btn btn-success btn-block" id="scng"><i class="fa fa-save"></i> Save Changes</button>
                        </div>
                    </div>
                </div>';


        $dialog =   '<div id="dialog" title="Basic dialog">Dialog</div>
                    <input type="hidden" name="frontend_minimax" value="1" class="mx-input"/>
                    <input type="hidden" name="post_id" value="'.$pid.'" class="mx-input"/>
                    <div id="mx-toolbar" class="w3eden">

                    <div id="mx-controls">
                        <div class="mx-toolbar-close">
                            <a class="" title="Disable Front-end Editing"  href="'.home_url('/?mxfrontend=0').'"><i class="fa fa-times"></i></a>
                        </div>
                        <div class="mx-col-layout">
                            <a href="#" rel="col-1" holder="#layout_'.get_post_type().'" class="insert-layout">1 Col Row</a>
                        </div>
                        <div class="mx-col-layout">
                            <a href="#" rel="col-2" holder="#layout_'.get_post_type().'" class="insert-layout">2 Cols Row</a>
                        </div>
                        <div class="mx-col-layout">
                            <a href="#" rel="col-3" holder="#layout_'.get_post_type().'" class="insert-layout">3 Cols Row</a>
                        </div>
                        <div class="mx-col-layout">
                            <a href="#" rel="col-4" holder="#layout_'.get_post_type().'" class="insert-layout">4 Cols Row</a>
                        </div>
                        <div class="mx-col-layout">
                            <a href="#" rel="col-5" holder="#layout_'.get_post_type().'" class="insert-layout">5 Cols Row</a>
                        </div>
                        <div class="mx-col-layout">
                            <a href="#" rel="col-6" holder="#layout_'.get_post_type().'" class="insert-layout">6 Cols Row</a>
                        </div>
                        <div class="mx-save-changes">
                            <a class="" id="scng"><i class="fa fa-save"></i> Save Changes</a>
                        </div>
                    </div>

                </div>';

        endif;

    if( current_user_can( 'edit_posts' ) && get_option('minimax_frontend_editing',0) == 0 && ( is_page() || is_single() ) ):
        $dialog = '<div id="minimax-front-builder" style="position:fixed;left:40px;bottom:50px;z-index:99999;" class="w3eden">
                        <a title="Enable MiniMax Frontend Editing" class="ttip minimax-fe" href="' . home_url('/?mxfrontend=1') . '">
                            <i class="fa fa-magic"></i> Enable Frontend Builder
                        </a>
                    </div>';
    endif;

    return $content.$data.$dialog;
}

function minimax_enable_frontend(){
    if(current_user_can('edit_posts') && isset($_GET['mxfrontend'])){
        update_option('minimax_frontend_editing',$_GET['mxfrontend']);
        wp_redirect($_SERVER['HTTP_REFERER']);
        die();
    }
}

//Insert Layout (admin)
function minimax_insert_layout(){
    $id = uniqid();              
    $holder = $_REQUEST['holder'];

    echo '<li id="row_li_'.$id.'">
            <input id="row_settings_'.$id.'" type="hidden" name="layout_settings['.$holder.']['.$_GET['layout'].']['.$id.']" value="" />
            <div class="row-handler">
                <div class="sort dtooltip" title="Drag this row"><span class="minimax-icons minimax-move"></span></div>
                <div rel="row_li_'.$id.'" class="rdel delete dtooltip" title="Delete this Row ?"><span class="minimax-icons minimax-cross"></span></div>
                <div class="rsettings dtooltip" title="Row CSS Settings" rel="row_settings_'.$id.'"><span class="minimax-icons minimax-cog"></span></div>
                <div class="rclone dtooltip" title="Clone this Row" rel="row_li_'.$id.'" rthis="'.$id.'"><span class="minimax-icons minimax-clone"></span></div>
            </div>
            <div class="row-container">
                <div class="container_12 clearfix wrapper row" id="row_'.$id.'">
                    <input type="hidden" name="layouts['.$holder.']['.$id.']" value="'.(isset($_REQUEST['layout'])?$_REQUEST['layout']:'').'" />';

    $cols = (int)str_replace("col-","", $_GET['layout']);
    include(MX_THEME_DIR."/frames/dynamic.frame.php");
    echo "</div></div><div class='clear'></div></li>";
    die(); 
}

//Insert Layout (front)
function minimax_insert_layout_front(){
    $id = uniqid();
    $holder = $_REQUEST['holder'];
    $layout = $_REQUEST['layout'];
    $rid = isset($ls['css_id'])?$ls['css_id']:"row_{$id}";
    $init = 1;
    if(current_user_can('edit_posts') && get_option('minimax_frontend_editing',0)==1){
        echo '<li id="row_li_'.$id.'"><input class="mx-input" id="row_settings_'.$id.'" type="hidden" name="layout_settings['.$holder.']['.$layout.']['.$id.']" value="" /><div class="row-handler"><div class="sort dtooltip" title="Drag this row"><i class="fa fa-arrows"></i></div><div rel="row_li_'.$id.'" class="rdel delete dtooltip" title="Delete this Row ?"><i class="fa fa-trash-o"></i></div><div  rel="row_settings_'.$id.'" class="rsettings dtooltip" title="Row CSS Settings"><i class="fa fa-cog"></i></div></div><div class="row-container"><input  class="mx-input" type="hidden" name="layouts['.$holder.']['.$id.']" value="'.$layout.'" />';
    echo '<div class="minimax_content_area borderfocus row '.$ls['css_class'].'" id="'.$rid.'" style="'.$ls['css_txt'].'" >';

        $cols = (int)str_replace("col-","", $_GET['layout']);
        include(MX_THEME_DIR."/layouts/bootstrap/dynamic.layout.php");

    echo "<div style='clear: both;'></div></div>";

        echo "</div><div class='clear'></div></li>";
    }
    die();
}


//Layout Settings (admin)
function minimax_layout_settings(){
    $ls  = unserialize(base64_decode($_REQUEST['layout_settings_data']));
    include("layout-settings.php"); 
    die();
}

//Format Layout Settings Data (admin)
function minimax_layout_settings_data(){
    echo base64_encode(serialize($_POST['ls']));
    die();
}

//Render saved layout frames (admin)
function minimax_render_layout_frames($holder){
    global $minimax_layout_data, $minimax_layout_settings;
    $gs = get_post_meta( isset($_GET['post']) ? $_GET['post'] : '','minimax_grid_settings', true );
    $gs = $gs[$holder."_rows"];  
    if( is_array($minimax_layout_data) && isset($minimax_layout_data[$holder]) && is_array($minimax_layout_data[$holder]) ):
        foreach( $minimax_layout_data[$holder] as $id => $layout ):
            echo '<li id="row_li_'.$id.'">
                    <input id="row_settings_'.$id.'" type="hidden" name="layout_settings['.$holder.']['.$layout.']['.$id.']" value="'.$minimax_layout_settings[$holder][$layout][$id].'" />
                    <div class="row-handler">
                        <div class="sort dtooltip" title="Drag this row"><span class="minimax-icons minimax-move"></span></div>
                        <div rel="row_li_'.$id.'" class="rdel delete dtooltip" title="Delete this Row ?"><span class="minimax-icons minimax-cross"></span></div>
                        <div  rel="row_settings_'.$id.'" class="rsettings dtooltip" title="Row CSS Settings"><span class="minimax-icons minimax-cog"></span></div>
                        <div class="rclone dtooltip" title="Clone this Row" rel="row_li_'.$id.'" rthis="'.$id.'"><span class="minimax-icons minimax-clone"></span></div>
                    </div>
                    <div class="row-container">
                        <div class="container_12 clearfix wrapper row" id="row_'.$id.'">
                            <input type="hidden" name="layouts['.$holder.']['.$id.']" value="'.$layout.'" />';

            if(!isset($gs[$id]))
                include(MX_THEME_DIR."/frames/{$layout}.frame.php");
            else  {
                $cols = count($gs[$id]);
                include(MX_THEME_DIR."/frames/dynamic.frame.php");
            }
            echo "</div></div><div class='clear'></div></li>";
        endforeach;
    endif;
}

//Render saved layout frames (site)
function minimax_render_layout($layout, $id, $holder = '') {
    $minimax_options = get_option("wpeden_admin");
    $gs = get_post_meta(get_the_ID(), 'minimax_grid_settings', true);
    $gs = $gs[$holder . "_rows"];
    $container_css = "row";

    global $minimax_layout_data, $minimax_layout_settings;
    if (!$minimax_layout_settings) $minimax_layout_settings = get_option('minimax_layout_settings', array());
    if (in_array($holder, get_post_types())) {
        $minimax_page_layout_settings = get_post_meta(get_the_ID(), 'minimax_layout_settings', true);
        $ls = unserialize(base64_decode($minimax_page_layout_settings[$holder][$layout][$id]));

        // Row Settings data
        $mls = $minimax_page_layout_settings[$holder][$layout][$id];
    } else {
        $ls = unserialize(base64_decode($minimax_layout_settings[$holder][$layout][$id]));

        // Row Settings data
        $mls = '';
    }
    $row_style = '';

    // Row Borders
    if (isset($ls['border_color']) && $ls['border_color'] != ''):
        $row_style .= (isset($ls['border_top']) && $ls['border_top'] != '') ? "border-top:" . $ls['border_top'] . "px solid " . $ls['border_color'] . ";" : "";
        $row_style .= (isset($ls['border_right']) && $ls['border_right'] != '') ? "border-right:" . $ls['border_right'] . "px solid " . $ls['border_color'] . ";" : "";
        $row_style .= (isset($ls['border_bottom']) && $ls['border_bottom'] != '') ? "border-bottom:" . $ls['border_bottom'] . "px solid " . $ls['border_color'] . ";" : "";
        $row_style .= (isset($ls['border_left']) && $ls['border_left'] != '') ? "border-left:" . $ls['border_left'] . "px solid " . $ls['border_color'] . ";" : "";
    endif;

    // Row Margins
    $row_style .= (isset($ls['margin_top']) && $ls['margin_top'] != '') ? "margin-top:" . $ls['margin_top'] . "px; " : '';
    $row_style .= (isset($ls['margin_right']) && $ls['margin_right'] != '') ? "margin-right:" . $ls['margin_right'] . "px; " : '';
    $row_style .= (isset($ls['margin_bottom']) && $ls['margin_bottom'] != '') ? "margin-bottom:" . $ls['margin_bottom'] . "px; " : '';
    $row_style .= (isset($ls['margin_left']) && $ls['margin_left'] != '') ? "margin-left:" . $ls['margin_left'] . "px; " : '';

    // Row Paddings
    $row_style .= (isset($ls['padding_top']) && $ls['padding_top'] != '') ? "padding-top:" . $ls['padding_top'] . "px; " : '';
    $row_style .= (isset($ls['padding_right']) && $ls['padding_right'] != '') ? "padding-right:" . $ls['padding_right'] . "px; " : '';
    $row_style .= (isset($ls['padding_bottom']) && $ls['padding_bottom'] != '') ? "padding-bottom:" . $ls['padding_bottom'] . "px; " : '';
    $row_style .= (isset($ls['padding_left']) && $ls['padding_left'] != '') ? "padding-left:" . $ls['padding_left'] . "px; " : '';

    $row_style .= 'position: relative;';

    $row_style .= ( isset($ls['bg_color']) && $ls['bg_color'] != '') ? "background:" . $ls['bg_color'] . ";" : "";
    $row_style .= ( isset($ls['tx_color']) && $ls['tx_color'] != '') ? "color:" . $ls['tx_color'] . ";" : "";

    // BG Image Styles
    if (isset($ls['image']) && $ls['image'] != '') {
        $row_style .= ( isset($ls['bg_color']) && $ls['bg_color'] != '') ?  "background: {$ls['bg_color']} url(" . $ls['image'] . ");" : "background: url(" . $ls['image'] . ");";
        $row_style .= ( $ls['repeat'] == 'stretched' ) ? "background-size:100% auto;" : "";
        $row_style .= ( $ls['repeat'] && $ls['repeat'] != 'stretched' ) ? "background-repeat:" . $ls['repeat'] . ";" : "";
        $row_style .= $ls['attachment'] ? "background-attachment:" . $ls['attachment'] . ";" : "";
        $row_style .= $ls['position_h'] ? "background-position:" . $ls['position_h'] . " " . $ls['position_v'] . ";" : "";
    }

    // Row Settings data
    //if (count($minimax_layout_settings) == 0 || !is_array($minimax_layout_settings)) $mls = "";
    //else $mls = $minimax_layout_settings[$holder][$layout][$id];

    $preh = $posh = "";
    $rid = (isset($ls['css_id']) && $ls['css_id'] != '') ? $ls['css_id'] : "row_{$id}";

    if( isset($ls['row_style']) && $ls['row_style'] != 'default' ) {
        $full_width_classes = $ls['row_style'] == 'fwr' ? ' mx-fullwidth-row ' : ' mx-fullwidth-row mx-fullwidth-content ';
    }else{
        $full_width_classes = '';
    }

    if (current_user_can('edit_posts') && get_option('minimax_frontend_editing', 0) == 1 && (is_page() || is_single())) {

        echo '<li id="row_li_' . $id . '">
                <input class="mx-input" id="row_settings_' . $id . '" type="hidden" name="layout_settings[' . $holder . '][' . $layout . '][' . $id . ']" value="' . $mls . '" />
                <div class="row-handler">
                    <div class="sort dtooltip" title="Drag this row"><i class="fa fa-arrows"></i></div>
                    <div rel="row_li_' . $id . '" class="rdel delete dtooltip" title="Delete this Row ?"><i class="fa fa-trash-o"></i></div>
                    <div rel="row_settings_' . $id . '" class="rsettings dtooltip" title="Row CSS Settings"><i class="fa fa-cog"></i></div>
                    <div class="rclone dtooltip" title="Clone this Row" rel="row_li_' . $id . '" rthis="' . $id . '"><i class="fa fa-copy"></i></div>
                </div>
                <div class="row-container"><input  class="mx-input" type="hidden" name="layouts[' . $holder . '][' . $id . ']" value="' . $layout . '" />';
    }

    echo '<div class="minimax_content_area row ' . $ls['css_class'] . $full_width_classes . '" id="' . $rid . '" style="' . $row_style . '" >' . $preh;

    $cols = count($gs[$id]);
    include(MX_THEME_DIR . "/layouts/bootstrap/dynamic.layout.php");
    echo "<div style='clear: both;'></div>{$posh}</div>";

    if (current_user_can('edit_posts') && get_option('minimax_frontend_editing', 0) == 1 && (is_page() || is_single())) {
        echo "</div><div class='clear'></div></li>";
    }
}

function wpeden_hex2rgb($hex) {
    $hex = str_replace("#", "", $hex);
    $color = array();
     
    if(strlen($hex) == 3) {
    $color['r'] = hexdec(substr($hex, 0, 1) . $r);
    $color['g'] = hexdec(substr($hex, 1, 1) . $g);
    $color['b'] = hexdec(substr($hex, 2, 1) . $b);
    }
    else if(strlen($hex) == 6) {
    $color['r'] = hexdec(substr($hex, 0, 2));
    $color['g'] = hexdec(substr($hex, 2, 2));
    $color['b'] = hexdec(substr($hex, 4, 2));
    }
     
    return $color['r'].",".$color['g'].",".$color['b'];
}


//Render Module Frames (admin)
function minimax_render_module_frames( $id ){
    global $mxwidgets, $minimax_modules, $minimax_modules_settings;
    $base_theme_url = base_theme_url;
    $minimax_modules_settings = get_option('minimax_modules_settings',array());    
    if(!is_array($minimax_modules_settings)) $minimax_modules_settings = array();
    $post_modules_settings = get_post_meta(isset($_GET['post'])?$_GET['post']:'','minimax_modules_settings',true);
    if(!is_array($post_modules_settings)) $post_modules_settings = array();
    $minimax_modules_settings += $post_modules_settings;
   
    $module_frames = '';
    if(isset($minimax_modules[$id])&&is_array($minimax_modules[$id])):
        $z = 0;
        foreach($minimax_modules[$id] as $mid => $module):
            $mod = $mxwidgets[$module];
            $z++;
            $ms = $z-1;

            // Generate module preview
            $instance = @unserialize(base64_decode($minimax_modules_settings[$id][$mid]));

            $instance = @array_shift(array_shift($instance));
            if($instance):
                foreach($instance as $k => $v):
                    $ins[$k] = is_array($v) ? $v : stripslashes($v);
                endforeach;
            endif;

            $prevw  = "";

            if(get_option('minimax_module_preview',0)==1){
                ob_start();

                if(method_exists($pmod = new $module(),'preview'))
                    $pmod->preview($ins);
                else
                    $pmod->widget(array(), $ins);
        
                $prevw = ob_get_contents();
                ob_clean();

                // Get preview html
                $prevw = "<div class='before-preview'></div><div class='module-preview w3eden'> {$prevw} </div>";
            }
    
            $cls = (!is_admin())?' class="mx-input"':'';
    
            $module_frames .=<<<MOD
            <li id='module_{$id}_{$z}' class='mod-single' rel='{$id}'>
                <input $cls type="hidden" id="modid_module_{$id}_{$z}" name="modules[{$id}][]" value="{$module}" />
                <input $cls id="modset_module_{$id}_{$z}" type="hidden" name="modules_settings[{$id}][]" value="{$minimax_modules_settings[$id][$mid]}" />

                <span class="module-title">{$mod->name}</span>
                <div class="ctl">
                    <div class="handle dtooltip" title="Drag this module"><span class="minimax-icons minimax-move"></span></div>
                    <div class="delete_module dtooltip" title="Delete this module ?" rel="#module_{$id}_{$z}"><span class="minimax-icons minimax-cross"></span></div>
                    <div class="insert dtooltip" title="Module Settings" wname="{$mod->name}" id="modset_module_{$id}_{$z}_icon" rel="$module" data="{$id}|{$mid}" datafield="modset_module_{$id}_{$z}">
                        <span class="minimax-icons minimax-cog"></span>
                    </div>
                    <div class="module-clone dtooltip" title="Clone this module" rel="module_{$id}_{$z}" col_id = {$id} mod_name="{$mod->name}" mod_id={$module} mod_set= {$minimax_modules_settings[$id][$mid]}>
                        <span class="minimax-icons minimax-clone"></span>
                    </div>
                </div>
                <div class="clear"></div>
                {$prevw}
                <div class="clear"></div>
            </li>
MOD;

    endforeach;
    endif;
    echo $module_frames;
}

function minimax_get_module_preview(){
    //global $mxwidgets, $minimax_modules, $minimax_modules_settings;

    if( get_option('minimax_module_preview',0) == 0 && !isset($_REQUEST['front'])) die("");

    $instance = @unserialize(base64_decode($_POST['modinfo']));
    $instance = @array_shift(array_shift($instance));
    if($instance):
        foreach($instance as $k=>$v):
            $ins[$k] = is_array($v)?$v:stripslashes($v);
        endforeach; endif;
    $module = $_POST['mod'];

    if(method_exists($pmod = new $module(),'preview'))
        $pmod->preview($ins);
    else
        the_widget($module, $ins);

    die();
}

//Render Modules (site)
function minimax_render_modules($id){
    global $minimax_modules, $mxwidgets, $wp_widget_factory, $minimax_modules_settings, $minimax_layout_data;
          
    if(get_post_meta(get_the_ID(),'minimax_modules',true)){           
        $minimax_layout_data_page = get_post_meta(get_the_ID(),'minimax_layout',true);
        $postmod = get_post_meta(get_the_ID(),'minimax_modules',true);
        $postmodset = get_post_meta(get_the_ID(),'minimax_modules_settings',true);
        if(!is_array($minimax_modules)) $minimax_modules = array();
        if(!is_array($minimax_modules_settings)) $minimax_modules_settings = array();
        if(!is_array($postmod)) $postmod = array();
        if(!is_array($postmodset)) $postmodset = array();
        $minimax_modules += $postmod;
        $minimax_modules_settings += $postmodset;
    }

    $base_theme_url = base_theme_url;

    if($minimax_modules[$id]):

    $mcount = 0;
    $z = 0;
    foreach($minimax_modules[$id] as $index => $module):
        $z++;
        $mod = $mxwidgets[$module];
        $instance = @unserialize(base64_decode($minimax_modules_settings[$id][$index]));

        //Get extra styling data
        $ms = $instance['ms'];

        $mod_style = '';
        $mod_style .= ( isset($ms['bg_color']) && $ms['bg_color'] != '' ) ? "background:".$ms['bg_color'].";" : "";
        $mod_style .= ( isset($ms['tx_color']) && $ms['tx_color'] != '' ) ? "color:".$ms['tx_color'].";" : "";

        // Module Borders
        if (isset($ms['border_color']) && $ms['border_color'] != ''):
            $mod_style .= (isset($ms['border_top']) && $ms['border_top'] != '') ? "border-top:" . $ms['border_top'] . "px solid " . $ms['border_color'] . ";" : "";
            $mod_style .= (isset($ms['border_right']) && $ms['border_right'] != '') ? "border-right:" . $ms['border_right'] . "px solid " . $ms['border_color'] . ";" : "";
            $mod_style .= (isset($ms['border_bottom']) && $ms['border_bottom'] != '') ? "border-bottom:" . $ms['border_bottom'] . "px solid " . $ms['border_color'] . ";" : "";
            $mod_style .= (isset($ms['border_left']) && $ms['border_left'] != '') ? "border-left:" . $ms['border_left'] . "px solid " . $ms['border_color'] . ";" : "";
        endif;

        // Module Margins
        $mod_style .= (isset($ms['margin_top']) && $ms['margin_top'] != '') ? "margin-top:" . $ms['margin_top'] . "px; " : '';
        $mod_style .= (isset($ms['margin_right']) && $ms['margin_right'] != '') ? "margin-right:" . $ms['margin_right'] . "px; " : '';
        $mod_style .= (isset($ms['margin_bottom']) && $ms['margin_bottom'] != '') ? "margin-bottom:" . $ms['margin_bottom'] . "px; " : '';
        $mod_style .= (isset($ms['margin_left']) && $ms['margin_left'] != '') ? "margin-left:" . $ms['margin_left'] . "px; " : '';

        // Module Paddings
        $mod_style .= (isset($ms['padding_top']) && $ms['padding_top'] != '') ? "padding-top:" . $ms['padding_top'] . "px; " : '';
        $mod_style .= (isset($ms['padding_right']) && $ms['padding_right'] != '') ? "padding-right:" . $ms['padding_right'] . "px; " : '';
        $mod_style .= (isset($ms['padding_bottom']) && $ms['padding_bottom'] != '') ? "padding-bottom:" . $ms['padding_bottom'] . "px; " : '';
        $mod_style .= (isset($ms['padding_left']) && $ms['padding_left'] != '') ? "padding-left:" . $ms['padding_left'] . "px; " : '';


        $mod_class  = (isset($ms['css_class']) && $ms['css_class'] != '') ? $ms['css_class'] : '';
        $animation = (isset($ms['animation']) && $ms['animation'] != '') ? $ms['animation'] : "";

        //Get general module options
        $instance = @array_shift(array_shift($instance));

        if($instance):
            foreach($instance as $k=>$v):
            $ins[$k] = is_array($v)?$v:stripslashes($v);
            endforeach;
        endif;


    if( current_user_can('edit_posts') && get_option( 'minimax_frontend_editing', 0 ) == 1 && ( is_page() || is_single() ) ) {
    echo "<li id='module_{$id}_{$z}' class='minimax_module $module'  rel='{$id}'>";
    echo "<div class='mx-md-overlay'></div>";
    echo <<<HFL
        <input class="mx-input" type="hidden" id="modid_module_{$id}_{$z}" name="modules[{$id}][]" value="{$module}" />
        <input class="mx-input" id="modset_module_{$id}_{$z}" type="hidden" name="modules_settings[{$id}][]" value="{$minimax_modules_settings[$id][$index]}" />
        <div class="mod-ctrl">{$mod->name}
        <nobr class="ctl">
            <i class="handle icon icon-move dtooltip" title="Drag this module"></i>
            <i class='delete_module icon icon-trash dtooltip' title='Delete this module?' rel='#module_{$id}_{$z}'></i>
            <i class="insert icon icon-cog dtooltip" title="Module settings" wname="{$mod->name}" id="modset_module_{$id}_{$z}_icon" rel="$module" data="{$id}|{$index}" datafield="modset_module_{$id}_{$z}"></i>
            <i class="module-clone icon icon-copy dtooltip" title="Clone this module" col_id = "module_{$id}_{$z}" mod_name="{$mod->name}" mod_id={$module}></i>
        </nobr>
        </div>
        <div class='minimax_module wow $animation $module $mod_class' style='$mod_style' data-wow-duration='1s' data-wow-delay='1s'>

HFL;
    } else
        echo "<div class='minimax_module wow $animation $module $mod_class' style='$mod_style' data-wow-duration='1s' data-wow-delay='1s'>";

    the_widget($module, $ins, $args = array('before_widget' => '<div class="%s">') );

    echo (current_user_can('edit_posts') && get_option('minimax_frontend_editing',0)==1 && (is_page()||is_single()))?"</div></li>":"</div>";
    $mcount++;
    endforeach;
    endif;
    
}


//Insert Module (admin)
function minimax_insert_module(){    
    require_once("insert-module.php");
    die();
}
//Import Layout (admin)
function minimax_import_layout(){    
    require_once("import-layout.php");
    die();
}


//Module Settings Form (admin)
function minimax_module_settings(){
    global $mxwidgets, $minimax_modules_settings;  
    $ins = explode('|',$_REQUEST['instance']);
    if(is_array($minimax_modules_settings[$ins[0]]))
    $instance = @unserialize(@base64_decode($minimax_modules_settings[$ins[0]][$ins[1]]));    
    //$form_prefix = isset($_REQUEST['instance']) ? "update-" : "";
    if(isset($_REQUEST['instance'])) { $form_prefix = "update-"; }
    $datafield = $_REQUEST['datafield'];
    $mod = $mxwidgets[$_GET['module']];  
    $instance = @array_shift($instance["widget-".$mod->id_base]); //[$mod->number];    
    $data_inst = @unserialize(@base64_decode($_POST['data_inst']));
    
    //Get Extra styling data     
    $ms = $data_inst['ms'];  
    //Get module options
    $data_inst = $data_inst["widget-".$mod->id_base][$mod->number];
    
    echo "<form class='ui-form' datafield='{$datafield}' method='post' id='{$form_prefix}module-settings-form'>";
    if($instance):
    foreach($instance as $k => $c){
        $iinstance[$k] = is_array($c) ? $c : stripcslashes($c);
    }
    endif;
    if($data_inst):
    foreach($data_inst as $k => $c){
        $data_inst[$k] = is_array($c) ? $c : stripcslashes($c);
    }
    endif;
    
    if(is_array($data_inst)) $iinstance = $data_inst;
    ?>
        <script>
        jQuery(function() {
          //jQuery( "#mod_set_tabs" ).tabs();
        });
        </script>
      
    <style>
        .nav-tabs{
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
        }
        .tab-content{
            border: 1px solid #dddddd;
            border-top: 0;
            padding: 20px;
        }
        .nav-tabs a{
            font-weight: 900;
            color: #aaaaaa;
        }
        .nav-tabs a:focus,
        .nav-tabs a:active{
            outline: none !important;
            box-shadow: none !important;
        }
    </style>
        <div class="w3eden">
        <ul class="nav nav-tabs">
          <li class="tab active"><a data-toggle="tab" href="#tabs-1">General Options</a></li>
          <li class="tab"><a data-toggle="tab" href="#tabs-2">Extra Styling Options</a></li>
        </ul>
        <div class="tab-content">
        <div class="tab-pane active" id="tabs-1">
          <?php $mxwidgets[$_GET['module']]->form($iinstance);  ?>
        </div>
        <div class="tab-pane" id="tabs-2">
            <?php include("module-settings.php"); ?>    
        </div>
        </div>
        </div><br/>
    <?php
        
    echo "
    
    <input type='submit' class='ui-button' value='Save Settings' />        
    <input type='button' class='ui-button' onclick='jQuery(\"#dialog\").dialog(\"close\");jQuery(\"#dialog\").html(\"Loading...\");' value='Cancel' />
    </form>
    <script>jQuery('#ui-dialog-title-dialog').html('".$mxwidgets[$_GET['module']]->name." Options');jQuery('.ui-button,.ui-form input[type=button]').button();</script>";
    die();
}

//Format module instance data (admin)
function minimax_module_settings_data(){    
    //print_r($_POST);
    $data = base64_encode(serialize($_POST));    
    echo $data;
    die();
}

//Raw JS Code
function minimax_header_js(){
    ?>
    <script type="text/javascript">    
      var base_theme_url = "<?php echo base_theme_url; ?>",pageid="<?php echo isset($_GET['post'])?$_GET['post']:''; ?>",post_type="<?php echo get_post_type(); ?>";
      var mx_admin_url = "<?php echo admin_url(); ?>";
      <?php if(!is_admin()) echo "var adminurl='".admin_url('/')."', ajaxurl = adminurl+'admin-ajax.php';"; ?>
    </script>
    <?php
}

//Save minimax theme option
function minimax_save_theme_options(){

    //Save Layouts
    update_option("wpeden_admin",$_POST['wpeden_admin']);
    update_option("minimax_layout",$_POST['layouts']);
    update_option("minimax_layout_settings",$_POST['layout_settings']);
    update_option("minimax_modules",$_POST['modules']);
    update_option("minimax_modules_settings",$_POST['modules_settings']);       
    die();
}


//Register minimax modules
function minimax_register_modules(){      
    $modules = get_option("minimax_allowed_modules");
    $module_dir = MX_THEME_DIR.'/modules/';
   
    if(is_array($modules)){
        foreach($modules as $module){             
            if(file_exists("$module_dir/$module/$module.php"))
            include("$module_dir/$module/$module.php");
        }
    }
}



function minimax_save_page_layout( $post_id ) {
     
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if(!$_POST||!isset($_POST['layout_settings'])) return;
    
    $squeeze_page = $_POST['squeeze_page'] == '1' ? 1 : 0;
    
    update_post_meta($post_id, "squeeze_page",$squeeze_page);
    update_post_meta($post_id, "sptemplate",$_POST['sptemplate']);
    update_post_meta($post_id, "bodybgcolor",$_POST['bodybgcolor']);
    update_post_meta($post_id, "bodybgimage",$_POST['bodybgimage']);
    update_post_meta($post_id, "minimax_layout",$_POST['layouts']);
    update_post_meta($post_id, "minimax_layout_settings",$_POST['layout_settings']); 
    update_post_meta($post_id,"minimax_grid_settings",$_POST['layout_grids']);
    update_post_meta($post_id, "minimax_modules",$_POST['modules']);
    update_post_meta($post_id, "minimax_modules_settings",$_POST['modules_settings']);
    
    @unlink(MX_CACHE_DIR.$post_id);
                                                                  
}

function minimax_save_frontend_page_layout( ) {

    if(!isset($_POST['frontend_minimax'])) return;

    $post_id = $_REQUEST['post_id'];
    $squeeze_page = $_POST['squeeze_page'] == '1' ? 1 : 0;

    update_post_meta($post_id, "minimax_layout",$_POST['layouts']);
    update_post_meta($post_id, "minimax_layout_settings",$_POST['layout_settings']);
    update_post_meta($post_id,"minimax_grid_settings",$_POST['layout_grids']);
    update_post_meta($post_id, "minimax_modules",$_POST['modules']);
    update_post_meta($post_id, "minimax_modules_settings",$_POST['modules_settings']);

    @unlink(MX_CACHE_DIR.$post_id);

}

function minimax_export_page_layout() {
     
    if ( !is_admin()|| !isset($_REQUEST['minimaxexport']) ) return;
    $post_id = $_REQUEST['minimaxexport'];
    $minimax_page['squeeze_page'] = get_post_meta($post_id, "squeeze_page", true);
    $minimax_page['bodybgcolor'] = get_post_meta($post_id, "bodybgcolor",true);
    $minimax_page['bodybgimage'] = get_post_meta($post_id, "bodybgimage",true);
    $minimax_page['minimax_layout'] = get_post_meta($post_id, "minimax_layout",true);
    $minimax_page['minimax_layout_settings'] = get_post_meta($post_id, "minimax_layout_settings", true);
    $minimax_page['minimax_grid_settings'] = get_post_meta($post_id, "minimax_grid_settings", true);
    $minimax_page['minimax_modules'] = get_post_meta($post_id, "minimax_modules",true);
    $minimax_page['minimax_modules_settings'] = get_post_meta($post_id, "minimax_modules_settings",true);

    $data = serialize($minimax_page);    
    header("Content-Description: File Transfer");
    header("Content-Type: text/plain");
    header("Content-Disposition: attachment; filename=\"minimax-page-{$post_id}.txt\"");
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: " . strlen($data));
    echo $data; 
    die();        
                                                                  
} 

function minimax_clone_page(){
    $post_id = isset($_REQUEST['minimaxclone'])?$_REQUEST['minimaxclone']:''; 
    if(is_admin()&&$post_id!=''){
      $post_data = get_post($post_id, ARRAY_A);
      unset($post_data['ID']);
      $post_data['post_title'] = "Clone: ".$post_data['post_title'];
      $post = wp_insert_post($post_data);  
      add_post_meta($post, "squeeze_page",get_post_meta($post_id, "squeeze_page", true));
      add_post_meta($post, "bodybgcolor",get_post_meta($post_id, "bodybgcolor",true));
      add_post_meta($post, "bodybgimage",get_post_meta($post_id, "bodybgimage",true));
      add_post_meta($post, "minimax_layout",get_post_meta($post_id, "minimax_layout",true));
      add_post_meta($post, "minimax_layout_settings",get_post_meta($post_id, "minimax_layout_settings", true));
      add_post_meta($post, "minimax_grid_settings",get_post_meta($post_id,'minimax_grid_settings', true));
      add_post_meta($post, "minimax_modules",get_post_meta($post_id, "minimax_modules",true));
      add_post_meta($post, "minimax_modules_settings",get_post_meta($post_id, "minimax_modules_settings",true));
      header("location: post.php?post={$post}&action=edit");
      die();
    }
}

function minimax_import_layout_data(){    
    if(is_admin()&&isset($_FILES['importlayout'])&&is_uploaded_file($_FILES['importlayout']['tmp_name'])){
    $post_id = $_GET['post'];    
    
    $data = file_get_contents($_FILES['importlayout']['tmp_name']);
    @unlink($_FILES['importlayout']['tmp_name']);
    $minimax_layout = unserialize($data);
     
    update_post_meta($post_id, "squeeze_page",$minimax_layout['squeeze_page']);
    update_post_meta($post_id, "bodybgcolor",$minimax_layout['bodybgcolor']);
    update_post_meta($post_id, "bodybgimage",$minimax_layout['bodybgimage']);
    update_post_meta($post_id, "minimax_layout",$minimax_layout['minimax_layout']);
    update_post_meta($post_id, "minimax_layout_settings",$minimax_layout['minimax_layout_settings']);
    update_post_meta($post_id,"minimax_grid_settings",$minimax_layout['minimax_grid_settings']);
    update_post_meta($post_id, "minimax_modules",$minimax_layout['minimax_modules']);
    update_post_meta($post_id, "minimax_modules_settings",$minimax_layout['minimax_modules_settings']);
    header("location: ".$_SERVER['REQUEST_URI']);            
    die();
    }   
}

function minimax_new_meida_buttons(){
    $post_type = get_post_type();
    $pageid = isset($_GET['post'])?$_GET['post']:'';
    if($pageid!='')
        $mxnb = "<a class='button export' href='".admin_url()."?minimaxexport=".$pageid."' ><span>Export Layout</span></a>
                 <a class='button import-layout import' rel='".$pageid."' href='#' ><span>Import Layout</span></a>
                 <a class='button clone' href='".admin_url()."?minimaxclone=".$pageid."' ><span>Clone</span></a>";
    else
        $mxnb = "<a class='button export' href='#' onclick='alert(\"".$post_type." is not published or saved yet!\");return false;' ><span>Export Layout</span></a>
                 <a class='button import' href='#' onclick='alert(\"".$post_type." is not published or saved yet!\");return false;' ><span>Import Layout</span></a>
                 <a class='button clone' href='#' onclick='alert(\"".$post_type." is not published or saved yet!\");return false;' ><span>Clone</span></a>";
            
   echo $mxnb;
}


//Register front-end script
function minimax_enqueue_scripts(){
    $minimax_options = get_option("wpeden_admin");
    wp_enqueue_script("jquery");

    if ( get_post_meta(get_the_ID(), 'squeeze_page', true) != 1 && get_option('minimax_bootstrap_css', 1) != 0 ) {
        wp_enqueue_style("minimax-bootstrap", base_theme_url . '/assets/bootstrap/css/bootstrap.css');
    }
    wp_enqueue_style("row-styles",base_theme_url.'/css/front.css');
    wp_enqueue_style("fa", "//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css");
    wp_enqueue_style('font-awesome',"//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.min.css");
    wp_enqueue_style('minimax-icon-font', plugins_url('/page-layout-builder/assets/fonts/minimax-icons/style.css'));

    if( get_option('minimax_bootstrap_js', 1) != 0 ) wp_enqueue_script("bootstrap-js",base_theme_url.'/assets/bootstrap/js/bootstrap.min.js');

    wp_enqueue_style('animate',base_theme_url.'/css/animate.css');
    wp_enqueue_script("wow",base_theme_url.'/js/wow.js');

    wp_enqueue_script("minimax-front", base_theme_url . '/js/minimax-front.js');

    if( current_user_can( 'edit_posts' ) && get_option( 'minimax_frontend_editing', 0 ) == 1 && ( is_page() || is_single() ) ){

        //Styles
        wp_enqueue_style('front-editor-font', '//fonts.googleapis.com/css?family=Titillium+Web');
        wp_enqueue_style("jquery-ui-main", plugins_url('/page-layout-builder/assets/jquery-ui/jquery-ui.css'));
        wp_enqueue_style("front-builder", base_theme_url.'/css/front-builder.css');
        wp_enqueue_style("chosen-ui", plugins_url('/page-layout-builder/assets/chosen/chosen.min.css'));

        //Scripts
        wp_enqueue_script("jquery-cookie",base_theme_url.'/js/jquery.ck.js',array('jquery'));
        wp_enqueue_script("jqueryuiall", "//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js");
        wp_enqueue_script("thickbox");
        wp_enqueue_script("jquery-form", array('jquery'));
        wp_enqueue_script("operations-js",base_theme_url.'/js/operations-front.js');
        wp_enqueue_script('chosen-js', plugins_url('/page-layout-builder/assets/chosen/chosen.jquery.min.js'), array('jquery'));
    }
}

/*Add WP Color Picker Scripts in Front End*/
function wp_color_picker_frontend() {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'iris',admin_url( 'js/iris.min.js' ),array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ),false,1);
    wp_enqueue_script('wp-color-picker',admin_url( 'js/color-picker.min.js' ),array( 'iris' ),false,1);
    $colorpicker_l10n = array(
        'clear' => __( 'Clear' ),
        'defaultString' => __( 'Default' ),
        'pick' => __( 'Select Color' )
    );
    wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', $colorpicker_l10n ); 
}
 
function minimax_admin_enqueue_scripts(){
    
    wp_enqueue_script("jquery");
     
    //Admin Styles
    if(isset($_GET['page'])&&$_GET['page']=='minimax') wp_enqueue_style("admin-reset",base_theme_url.'/css/reset.css');

    wp_enqueue_style("admin-bootstrap",base_theme_url.'/assets/bootstrap/css/bootstrap.css');
    wp_enqueue_style("admin-theme-style",base_theme_url.'/css/admin-style.css');
    wp_enqueue_style("frame-grid",base_theme_url.'/css/grid.css');
    wp_enqueue_style("gh-buttons",base_theme_url.'/css/gh-buttons.css');
    wp_enqueue_style("thickbox");
    wp_enqueue_style("jquery-ui-main",plugins_url('/page-layout-builder/assets/jquery-ui/jquery-ui.css'));
    wp_enqueue_style("wp-color-picker");
    wp_enqueue_style('chosen-ui',plugins_url('/page-layout-builder/assets/chosen/chosen.min.css'));
    wp_enqueue_style('minimax-icon-font', plugins_url('/page-layout-builder/assets/fonts/minimax-icons/style.css'));
    
    //Admin Scripts
    wp_enqueue_script("jquery-ui-all");
    wp_enqueue_script("jquery-ui-sortable");
    wp_enqueue_script("jquery-form");
    wp_enqueue_script("thickbox");
    wp_enqueue_script("jquery-cookie",base_theme_url.'/js/jquery.ck.js',array('jquery'));
    wp_enqueue_script("minimax-opr",base_theme_url.'/js/operations.js',array('jquery'));
    wp_enqueue_script("wp-color-picker");
    wp_enqueue_script("jquery-ui-dialog");
    wp_enqueue_script("jquery-ui-tabs");
    wp_enqueue_script("bootstrap-js",base_theme_url.'/assets/bootstrap/js/bootstrap.min.js');
    wp_enqueue_script('chosen-js', plugins_url('/page-layout-builder/assets/chosen/chosen.jquery.min.js'), array('jquery'));
}


function minimax_dynamic_thumb($path, $size){
    $name_p = explode(".",$path);
    $ext = ".".end($name_p);
    $thumbpath = str_replace($ext, "-".implode("x", $size).$ext, $path);
    if(file_exists($thumbpath)) return $thumbpath;
    $image = wp_get_image_editor( $path );
    if ( ! is_wp_error( $image ) ) {
        $image->resize( $size[0], $size[1], true );
        $image->save( $thumbpath );
    }
    return $thumbpath;
}

/*Animation library wow.js init*/
function wow_init(){
    ?> <script> new WOW().init(); </script> <?php
}

/* Generate cutom excerpt */
function minimax_get_excerpt($postid, $length, $echo = true, $c = '...'){
    $post = get_post($postid);
    $uexcerpt = $post->post_excerpt?$post->post_excerpt:preg_replace("/\[([^\]]*)\]/","",$post->post_content);
    $uexcerpt = strip_tags($uexcerpt);
    $uexcerpt = esc_html($uexcerpt);
    $excerpt = substr($uexcerpt,0,$length);
    $eexcerpt = substr($uexcerpt,$length);
    $eexcerpt = explode(" ",$eexcerpt);
    $excerpt .= array_shift($eexcerpt);
    $excerpt =  $excerpt?$excerpt.$c:$excerpt;
    if(!$echo) return $excerpt;
    echo $excerpt;
}


function minimax_cache_status(){
    update_option('minimax_cache_status', $_POST['cache_status']);
    die("OK");
}
function minimax_module_preview(){
    update_option('minimax_module_preview', $_POST['module_preview']);
    die("OK");
}
function minimax_bootstrap_css(){
    update_option('minimax_bootstrap_css', $_POST['bootstrap_status']);
    die("OK");
}
function minimax_bootstrap_js(){
    update_option('minimax_bootstrap_js', $_POST['bootstrap_status']);
    die("OK");
}

add_action("init", "minimax_enable_frontend");
//add_action('media_buttons', 'minimax_new_meida_buttons', 99);
add_action('init', 'minimax_load_theme_option_data');

//Ajax Actions
add_action("wp_ajax_insert_layout", "minimax_insert_layout");
add_action("wp_ajax_insert_layout_front", "minimax_insert_layout_front");
add_action("wp_ajax_import_layout", "minimax_import_layout");
add_action("wp_ajax_insert_module", "minimax_insert_module");
add_action("wp_ajax_module_settings", "minimax_module_settings");
add_action("wp_ajax_module_settings_data", "minimax_module_settings_data");
add_action("wp_ajax_layout_settings", "minimax_layout_settings");
add_action("wp_ajax_layout_settings_data", "minimax_layout_settings_data");
add_action("wp_ajax_get_module_preview", "minimax_get_module_preview");

add_action('save_post', 'minimax_save_page_layout');
add_action('admin_init', 'minimax_export_page_layout');
add_action('admin_init', 'minimax_import_layout_data');
add_action('admin_init', 'minimax_clone_page');

//Register Minimax modules
minimax_register_modules();

add_action('admin_head', 'minimax_header_js');
add_action('wp_head', 'minimax_header_js');
add_action("admin_enqueue_scripts", "minimax_admin_enqueue_scripts");
add_action("wp_enqueue_scripts", "wp_color_picker_frontend", 100);
add_action("wp_enqueue_scripts", "minimax_enqueue_scripts");
add_filter("the_content", "minimax_page_layout");

//Actions
add_action('init', "minimax_wp_widgets");
add_action('wp_ajax_save_frontend_layout', "minimax_save_frontend_page_layout");
add_action('wp_ajax_minimax_cache', "minimax_cache_status");
add_action('wp_ajax_minimax_module_preview', "minimax_module_preview");
add_action('wp_ajax_minimax_bootstrap_css', "minimax_bootstrap_css");
add_action('wp_ajax_minimax_bootstrap_js', "minimax_bootstrap_js");
add_action('wp_footer','wow_init');

//Save all settings
add_action("wp_ajax_minimax_save_theme_options", "minimax_save_theme_options");
