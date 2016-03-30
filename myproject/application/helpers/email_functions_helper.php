<?php

function send_email_to_administrator($subject, $text)
{
    $ci =& get_instance();
    $ci->load->library('email');
    $ci->email->from($ci->config->item('bot_email'), 'PD Admin');
    $ci->email->to($ci->config->item('admin_email'));
    $ci->email->subject($subject);
    $ci->email->message($text);
    return $ci->email->send();
}

function send_email($to, $subject, $text)
{
    $ci =& get_instance();
    $ci->load->library('email');
    $ci->email->set_mailtype('html');
    $ci->email->from($ci->config->item('bot_email'), 'PD Admin');
    $ci->email->to($to);
    $ci->email->subject($subject);
    $ci->email->message($text);
    return $ci->email->send();
}