<?php
defined('BASEPATH') OR exit('No direct script access allowed');
function is_logged_in() {
	
    $CI =& get_instance();
    if(!$CI->session->has_userdata('wad_user')){
		return false;
	}else{
		return true;
	}
    
}

function toNumber($stringdata)
{
    return number_format($stringdata,8, '.',' ');
}


function covertDate($date_old)
{
    return date("d.m.y H.i", strtotime($date_old));
}

function datenotime($date_old)
{
    return date("d.m.y", strtotime($date_old));
}

?>