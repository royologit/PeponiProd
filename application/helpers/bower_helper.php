<?php
if(!defined('BASEPATH')) exit('No direct script access allowed');

function bower_url($protocol = NULL)
{
	$bower_url = get_instance()->config->item('bower_url');
	return get_instance()->config->base_url($bower_url, $protocol);
}
