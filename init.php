<?php
/**
 * Plugin Name: Import demo data (Multisite Only)
 * Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
 * Description: Import demo data when a new site is created in a multisite.
 * Version: 1.0.1
 * Author: Bappi D Great (Ash)
 * Author URI: http://bappi-d-great.com
 * License: GPL2 or later
 
    Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

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


require 'radium-one-click-demo-install/init.php';

add_action( 'wpmu_new_blog', 'wpmu_new_blog_cb', 999, 6 );
function wpmu_new_blog_cb( $blog_id, $user_id, $domain, $path, $site_id, $meta ){
    switch_to_blog( $blog_id );
    $import_obj = new Radium_Theme_Demo_Data_Importer(); 

    //calling various methods by a class object
    $import_obj->set_demo_data( $import_obj->content_demo );
    $import_obj->set_demo_theme_options( $import_obj->theme_options_file );
    $import_obj->set_demo_menus();
    $import_obj->process_widget_import_file( $import_obj->widgets );
    restore_current_blog();
}