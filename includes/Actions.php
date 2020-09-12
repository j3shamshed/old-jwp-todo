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
                        return '';
                    }
            ));
            register_rest_route('jwptodo/v1', '/allComplete',
                array(
                    'methods' => 'GET',
                    'callback' => array($this, 'prefixGetAllActiveTodo'),
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
            register_rest_route('jwptodo/v1', '/update/(?P<id>\d+)',
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'prefixUpdateTodo'),
                    'permission_callback' => function () {
                        return current_user_can('edit_others_posts');
                    }
            ));
            register_rest_route('jwptodo/v1', '/makeItComplete',
                array(
                    'methods' => 'POST',
                    'callback' => array($this, 'prefixMakeItCompleteTodo'),
                    'permission_callback' => function () {
                        return current_user_can('edit_others_posts');
                    }
            ));
            register_rest_route('jwptodo/v1', '/delete/(?P<id>\d+)',
                array(
                    'methods' => 'DELETE',
                    'callback' => array($this, 'prefixDeleteTodo'),
                    'permission_callback' => function () {
                        return current_user_can('edit_others_posts');
                    }
            ));
            register_rest_route('jwptodo/v1', '/deleteAllCompleted',
                array(
                    'methods' => 'DELETE',
                    'callback' => array($this, 'prefixDeleteAllCompleted'),
                    'permission_callback' => function () {
                        return current_user_can('edit_others_posts');
                    }
            ));
        });
    }

    public function prefixDeleteTodo($data)
    {
        global $wpdb;
        $tableName = self::getTableName();
        return $wpdb->delete($tableName, array('id' => $data['id']), array('%d'));
    }

    public function prefixDeleteAllCompleted()
    {
        global $wpdb;
        $tableName = self::getTableName();
        return $wpdb->delete($tableName,
                array('flag' => esc_html__('active', 'domain')), array('%s'));
    }

    public function prefixUpdateTodo($data)
    {
        global $wpdb;
        $tableName = self::getTableName();
        $id        = $data['id'];
        if (isset($_POST['name'])) {
            return $wpdb->update(
                    $tableName,
                    array(
                        'name' => esc_html($_POST['name'])
                    ), array('id' => $id),
                    array(
                        '%s',
                    ), array('%d')
            );
        }
    }

    public function prefixMakeItCompleteTodo()
    {
        global $wpdb;
        $tableName = self::getTableName();
        if (isset($_POST['ids'])) {
            $ids = $_POST['ids'];
            foreach ($ids as $value):
                $wpdb->update(
                    $tableName,
                    array(
                        'flag' => esc_html__('active', 'domain')
                    ), array('id' => $value),
                    array(
                        '%s',
                    ), array('%d')
                );
            endforeach;
            return true;
        }
    }

    public function prefixPostTodo()
    {
        global $wpdb;
        $tableName = self::getTableName();
        if (isset($_POST['name'])) {
            $name = esc_html($_POST['name']);

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
        $query     = "SELECT id,name FROM $tableName WHERE flag='todo'";
        $list      = $wpdb->get_results($query);
        return $list;
    }

    public function prefixGetAllActiveTodo()
    {
        global $wpdb;
        $tableName = self::getTableName();
        $query     = "SELECT id,name FROM $tableName WHERE flag='active'";
        $list      = $wpdb->get_results($query);
        return $list;
    }

    public function prefixUserDetaila()
    {
        $vueId = apply_filters('prefix_change_vue_id', 'jubayerID');
        //$str   = "<div id='".esc_attr($vueId)."'><todo-component v-bind:todo='message'></todo-component></div>";
        $str   = "<div class='jubayer' id='".esc_attr($vueId)."'><h4>".esc_html__('To-Do Lists','domain')."</h4>"
            ."<input class='onInsert' type='text' v-model='data.todoName' @keyup.enter='onInsert' ref='onInsert'>"
            ."<ul class='todo' v-if='todo'>"
            ."<li v-for='todoVal in todos' :key='todoVal.id'><input type='checkbox' @click='updateId(todoVal.id)'>"
            ."<input type='text' v-model='todoVal.name' @click='updateTodoName(todoVal.name)' @keyup.enter='onUpdate(todoVal.id)' :ref='todoVal.id'><a href='#' @click='onDelete(todoVal.id)'>delete</a></li></ul>"
            ."<ul class='completeTodo' v-else>"
            ."<li v-for='todoCom in todos' :key='todoCom.id'>{{todoCom.name}}</li></ul>"
            ."<ul class='controller'>"
            ."<li>({{count}})Todo</li>"
            ."<li><button @click='showToDoList'>All</button></li>"
            ."<li><button @click='makeItComplete'>Active</button></li>"
            ."<li><button @click='showCompletedList'>Completed</button></li>"
            ."<li><button @click='deleteAllCompleted'>Clear ALl Completed</button></li></ul>"
            ."</div>";
        return $str;
    }
}