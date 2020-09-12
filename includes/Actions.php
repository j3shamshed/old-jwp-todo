<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Inc;

class Actions
{

    use TableName;

    public function prefixRegister()
    {
        add_shortcode('todo', array($this, 'prefixUserDetaila'));
        add_action('rest_api_init',
            function () {
            register_rest_route('jwptodo/v1', '/all',
                array(
                    'methods' => 'GET',
                    'callback' => array($this, 'prefixGetTodo'),
                    'permission_callback' => function () {
                        return current_user_can('edit_others_posts');
                    }
            ));
            register_rest_route('jwptodo/v1', '/allComplete',
                array(
                    'methods' => 'GET',
                    'callback' => array($this, 'prefixGetTodo'),
                    'permission_callback' => function () {
                        return current_user_can('edit_others_posts');
                    }
            ));
            register_rest_route('jwptodo/v1', '/store',
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'prefixPostTodo'),
                    'permission_callback' => function () {
                        return current_user_can('edit_others_posts');
                    }
            ));
        });
    }

    public function prefixPostTodo()
    {
        global $wpdb;
        $tableName = self::getTableName();
        if (isset($_POST['name'])) {
            $name = esc_html($_POST['name']);
//            $query = "INSERT INTO $tableName (flag, name) VALUES ('todo', '$name')";
//            return $wpdb->get_results($query);

            return $wpdb->query(
                    $wpdb->prepare(
                        "
                    INSERT INTO $tableName
                    ( flag, name)
                    VALUES ( %s, %s )
                    ", 'todo', $name
                    )
            );
        }
        return 0;
    }

    public function prefixGetTodo()
    {
        global $wpdb;
        $tableName = self::getTableName();
        $query     = "SELECT id,flag,name FROM $tableName";
        $list      = $wpdb->get_results($query);
        return $list;
    }

    public function prefixUserDetaila()
    {
        $vueId = apply_filters('prefix_change_vue_id', 'jubayerID');
        //$str   = "<div id='".esc_attr($vueId)."'><todo-component v-bind:todo='message'></todo-component></div>";
        $str   = "<div id='".esc_attr($vueId)."'>"
            ."<input type='text' v-model='data.todoName' @keyup.enter='onInsert'>"
            ."<ul v-if='todo'>"
            ."<li v-for='todoVal in todos' :key='todoVal.id'><input type='checkbox' @click='updateId(todoVal.id)'>"
            ."<input type='text' v-model='todoVal.name' @click='updateTodoName(todoVal.name)' @keyup.enter='onUpdate(todoVal.id)'><a href='#' @click='onDelete(todoVal.id)'>delete</a></li></ul>"
            ."<ul v-else>"
            ."<li v-for='todoCom in todoComplete' :key='todoCom.id'>{{todoCom.name}}</li></ul>"
            ."<ul>"
            ."<li>({{count}})Lists</li>"
            ."<li><button @click='showToDoList'>All</button></li>"
            ."<li><button @click='makeItComplete'>Active</button></li>"
            ."<li><button @click='showCompletedList'>Completed</button></li>"
            ."<li><button @click='deleteAllCompleted'>Clear Completed</button></li></ul>"
            ."</div>";
        return $str;
    }
}