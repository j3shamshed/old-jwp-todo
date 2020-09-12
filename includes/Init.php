<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Init
 *
 * @author j3sha
 */

namespace Inc;

class Init
{

    use TableName;

    /**
     * Store all the classes inside an array
     * @return array Full list of classes
     */
    public static function prefixGetServices()
    {
        return [
            Actions::class,
            Enqueue::class
        ];
    }

    /**
     * Loop through the classes, initialize them
     * and call the register() method if it exists
     * @return
     */
    public static function prefixRegisterServices()
    {
        foreach (self::prefixGetServices() as $class) {
            $service = self::prefixInstantiate($class);
            if (method_exists($service, 'prefixRegister')) {
                $service->prefixRegister();
            }
        }
    }

    /**
     * Initialize the class
     * @param  class $class    class from the services array
     * @return class instance  new instance of the class
     */
    private static function prefixInstantiate($class)
    {
        $service = new $class();
        return $service;
    }

    /**
     * Checking PHP and WordPress Versions
     */
    public static function prefixActivation()
    {

        $exit_msg_wp  = sprintf(esc_html__("This plugin requires Wordpress version %s or newer. Please update.",
                "domain"), PREFIX_WP_VERSION);
        $exit_msg_php = sprintf(esc_html__("This plugin requires PHP version %s or newer. Please update.",
                "domain"), PREFIX_PHP_VERSION);

        if (version_compare(get_bloginfo('version'), PREFIX_WP_VERSION, '<=')) {
            exit($exit_msg_wp);
        } elseif (version_compare(PHP_VERSION, PREFIX_PHP_VERSION, '<=')) {
            exit($exit_msg_php);
        }

        self::prefixAddPages();
        self::prefixAddTables();
    }

    /**
     * Uninstall Plugin
     */
    public static function prefixUninstall()
    {
        global $wpdb;
        $tableName = self::getTableName();
        $wpdb->query("DROP TABLE IF EXISTS $tableName");
    }

    /**
     * Add pages
     */
    public static function prefixAddPages()
    {
        $page_definitions = array(
            'prefix-todo' => array(
                'title' => __('Todo', 'domain'),
                'content' => '[todo][/todo]'
            )
        );
        /*
         * "prefix_plugin_page_defination" filter
         * to HOOK to add new pages
         */
        $page_definitions = apply_filters('prefix_plugin_page_defination',
            $page_definitions);

        foreach ($page_definitions as $slug => $page) {
            // Check that the page doesn't exist already
            $query = new \WP_Query('pagename='.$slug);
            if (!$query->have_posts()) {
                // Add the page using the data from the array above
                wp_insert_post(
                    array(
                        'post_content' => $page['content'],
                        'post_name' => $slug,
                        'post_title' => $page['title'],
                        'post_status' => 'publish',
                        'post_type' => 'page',
                        'ping_status' => 'closed',
                        'comment_status' => 'closed'
                    )
                );
            }
        }
    }

    /**
     * Add Tables
     */
    public static function prefixAddTables()
    {
        global $wpdb;
        $table_name      = $tableName       = self::getTableName();
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            flag text NOT NULL,
            name varchar(55) DEFAULT '' NOT NULL,
            PRIMARY KEY  (id)
          ) $charset_collate;";

        require_once( ABSPATH.'wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }
}