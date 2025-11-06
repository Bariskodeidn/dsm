<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/userguide3/general/hooks.html
|
*/

// $hook['pre_controller'][] = array(
//   'class'    => 'UserAccess',
//   'function' => 'checkAccess',
//   'filename' => 'UserAccess.php',
//   'filepath' => 'hooks'
// );

$hook['post_controller_constructor'][] = array(
  'class'    => 'Subscription_check',
  'function' => 'check_user_subscription',
  'filename' => 'Subscription_check.php',
  'filepath' => 'hooks',
  'params'   => array()
);
