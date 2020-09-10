<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Inc;

trait TableName {
  static function getTableName(){
      global $wpdb;
      return $wpdb->prefix . "todo";
  }
}

