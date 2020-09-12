<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Inc;

class Enqueue
{

    public function prefixRegister()
    {
        add_action('wp_enqueue_scripts', array($this, 'prefixScriptsStyle'));
    }

    public function prefixScriptsStyle()
    {
        if (is_page('prefix-todo')) {
            wp_enqueue_script('qs', 'https://unpkg.com/qs/dist/qs.js');
            wp_enqueue_script('axios',
                'https://unpkg.com/axios/dist/axios.min.js');
            wp_enqueue_script('vuejs',
                'https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.js');
            wp_enqueue_script('prefix-app-js',
                PREFIX_PLUGINURL.'/jwp-todo/js/app.js', '', '', true);

            $credentials = array(
                'root' => esc_url_raw(rest_url()),
                'nonce' => wp_create_nonce('wp_rest')
            );
            wp_localize_script('prefix-app-js', 'object', $credentials);

            // Enqueued script with localized data.
            wp_enqueue_script('some_handle');

            wp_enqueue_style('prefix-custom-css',
                PREFIX_PLUGINURL.'/jwp-todo/css/custom.css');
        }
    }
}