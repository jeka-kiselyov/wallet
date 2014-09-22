<?php

function smarty_function_run_js_tests($params, $template)
{   
    $site_path = '/';
    if (isset($template->smarty->tpl_vars['settings']) && $template->smarty->tpl_vars['settings']->value->site_path)
        $site_path = $template->smarty->tpl_vars['settings']->value->site_path;

    $ret = '';

    $ret.="<link href=\"".$site_path."/vendors/jasmine/jasmine.css\" media=\"screen\" rel=\"stylesheet\" type=\"text/css\" />\n";
    $ret.="<script src=\"".$site_path."/vendors/jasmine/jasmine.js\" type=\"text/javascript\"></script>\n";
    $ret.="<script src=\"".$site_path."/vendors/jasmine/jasmine-html.js\" type=\"text/javascript\"></script>\n";
    $ret.="<script src=\"".$site_path."/vendors/jasmine/boot.js\" type=\"text/javascript\"></script>\n";
    $ret.="<script src=\"".$site_path."/vendors/jasmine/mock-ajax.js\" type=\"text/javascript\"></script>\n";

    for ($i = 0; $i < count($template->smarty->tpl_vars['js_tests']->value); $i++)
    {
        $ret.="<script src=\"".$site_path."/jstests/".$template->smarty->tpl_vars['js_tests']->value[$i].".js\" type=\"text/javascript\"></script>\n";
    }

    return $ret;
}

