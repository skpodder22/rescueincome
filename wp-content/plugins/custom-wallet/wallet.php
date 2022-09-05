<?php
/*
 
Plugin Name: Custom Wallet
 
Plugin URI: https://rescueincome.com/
 
Description: Used by millions, Akismet is quite possibly the best way in the world to <strong>protect your blog from spam</strong>. It keeps your site protected even while you sleep. To get started: activate the Akismet plugin and then go to your Akismet Settings page to set up your API key.
 
Version: 4.1.7
 
Author: VS
 
*/
define('WALLET_FILE_URL', __FILE__);
  /**Plugin activate Hook*/  

  
register_activation_hook( WALLET_FILE_URL, 'create_plugin_database_table' );

    function create_plugin_database_table()
{
    global $table_prefix, $wpdb;

    $tblname = 'wallet_in';
    //$wp_track_table = $table_prefix . "$tblname ";


    $wp_track_table = $wpdb->prefix . 'wallet_in';
    $charset_collate = $wpdb->get_charset_collate();

    #Check to see if the table exists already, if not, then create it
//var_dump([$wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table]);die();
    if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table) 
    {

        // $sql = "CREATE TABLE `". $wp_track_table . "` ( ";
        // $sql .= "  `id`  int(11)   NOT NULL auto_increment, ";
        // $sql .= "  `pincode`  int(128)   NOT NULL, ";
        // $sql .= "  PRIMARY KEY `order_id` (`id`) "; 
        // $sql .= ") ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ; ";
        
        $sql = "CREATE TABLE `". $wp_track_table . "` (
            `id` int(5) NOT NULL,
            `userId` int(10) NOT NULL,
            `amount` double(10,2) NOT NULL,
            `code` varchar(50)  NOT NULL,
            `reason` varchar(50)  NOT NULL,
            `source` varchar(50)  NOT NULL,
            `person` varchar(150)  NOT NULL,
            `identity` varchar(150)  DEFAULT NULL,
            `date` date DEFAULT NULL,
            `time` timestamp NULL DEFAULT current_timestamp(),
            `deleted` enum('1','0')  DEFAULT '0'
        ) $charset_collate ;";

        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }
}
function delete_plugin_database_table() {
    global $wpdb;
    $table_name = $wpdb->prefix.'wallet_in';
    $sql = "DROP TABLE IF EXISTS $table_name";
    $wpdb->query($sql);
    delete_option("devnote_plugin_db_version");
}
register_deactivation_hook( __FILE__, 'delete_plugin_database_table' );

function getWalletStatement(){

    require_once '../wallet_class/Wallet.php';

    //Instantiate the wallet class
    $frontWallet	= new Wallet();

    //Now you are ready to use your wallet, just supply the relevant parameters
    try{
        $table1			= 'wallet_in'; 										//can't be NULL
        $table2			= 'wallet_out'; 									//can't be NULL
        $where_values	= array('userId'=>'2'); 				  			//For all Rows use empty array as  array();
        $fields_values  = array('id','amount','code');						//For all fields use array('*');

        $results		=	$frontWallet->walletStatement($table1, $table2, $where_values, $fields_values);

        //Read
        //wallet one
        while ($rows1    = mysqli_fetch_row($results['wallet_in']))
        {
            //out put here
            var_dump($rows1);
        }
        //wallet two
        while ($rows2    = mysqli_fetch_row($results['wallet_out']))
        {
            //out put here
            var_dump($rows2);
        }
    }
    catch (WalletException $e)
    {
        echo "Wallet error : ".$e->getMessage();
    }

    //Yeah you did it!!!
}