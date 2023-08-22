<?php
/*
* Template Name: Login
*/

$sf_client_id = get_field('salesforce_client_id', 'option');
$sf_callback_url = get_field('salesforce_callback_url', 'option');
$sf_community_url = get_field('salesforce_community_url', 'option');

$login_url = $sf_community_url .'/forms/services/oauth2/authorize?client_id='. $sf_client_id .'&redirect_uri='. $sf_callback_url .'&response_type=code&prompt=login';

wp_redirect($login_url); 

die;

?>