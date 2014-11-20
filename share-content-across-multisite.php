<?php
/*
Plugin Name: Share across network
Plugin URI: http://premium.wpmudev.org/
Description: A very simple widget plugin to share info across the network
Version: 1.0.1
Author: Ashok (Incsub)
Author URI: http://bappi-d-great.com
License: GNU General Public License (Version 2 - GPLv2)
Network: true
*/

/*
 * Translation is not ready
 */

namespace shareinfo; 
 
if ( ! defined( 'ABSPATH' ) ) exit;

if( ! class_exists( 'share_info' ) ){
    
    class share_info{
        
        public function __construct() {
            add_action( 'network_admin_menu', [ $this, 'site_info_page' ] );
        }
        
        
        public function site_info_page() {
            add_submenu_page( 'sites.php', 'Site Info', 'Site Info', 'manage_network', 'site-info', [ $this, 'site_info_page_cb' ] );
        }
        
        
        public function site_info_page_cb() {
            
            if( $_POST['share_info_submit'] ){
                
                if( ! is_super_admin() ){
                    return;
                }
                
                if ( ! isset( $_POST['share_info_nonce_field'] ) || ! wp_verify_nonce( $_POST['share_info_nonce_field'], 'share_info_action' ) ) {
                    return;
                }
                
                if( update_site_option( 'share_site_info', $_POST['share_info'] ) )
                    wp_redirect( admin_url( 'network/sites.php?page=site-info&msg=Data+Saved.' ) );
                
            }
            
            $sites = wp_get_sites();
            
            $info = get_site_option( 'share_site_info' );
            
            ?>
            <div class="wrap">
                <h2>Add the data you want to share for all sites</h2>
                <br>
                <?php if( $_REQUEST['msg'] ) { ?>
                <div id="message" class="updated below-h2"><p><?php echo str_replace( '+', ' ', $_REQUEST['msg'] ) ?></a></p></div>
                <?php } ?>
                <form action="<?php echo admin_url( 'network/sites.php?page=site-info&noheader=true' ) ?>" method="post">
                    <?php wp_nonce_field('share_info_action','share_info_nonce_field'); ?>
                    <table class="widefat">
                        <tr>
                            <th width="25%">Site name</th>
                            <th>Site info</th>
                        </tr>
                        <?php
                            $i = 0;
                            foreach( $sites as $site ) {
                                switch_to_blog( $site['blog_id'] ); ?>
                                <tr class="<?php echo $i++ % 2 == 0 ? 'alternate' : '' ?>">
                                    <td><?php echo get_bloginfo( 'name' ); ?></td>
                                    <td>
                                        <textarea style="width: 100%" rows="5" name="share_info[<?php echo $site['blog_id'] ?>]"><?php echo $info[$site['blog_id']] ?></textarea><br><em>HTML Allowed</em>
                                    </td>
                                </tr>
                            <?php
                                restore_current_blog();
                                }
                            ?>
                    </table>
                    <br>
                    <input type="submit" name="share_info_submit" class="button button-primary" value="Save">
                </form>
            </div>
            <?php
        }
        
    }
    
    new share_info();
    include 'share-info-widget/widget.php';
    
}