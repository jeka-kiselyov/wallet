<?php

function smarty_function_include_js_files($params, $template)
{   
    if (!isset($template->smarty->tpl_vars['head_js'])) {
        trigger_error("[plugin] need to be called in framework template file",E_USER_NOTICE);
        return;
    }
    $prepend_offset = 0;
    if (isset($template->smarty->tpl_vars['head_js_prepend_offset']))
        $prepend_offset = (int)$template->smarty->tpl_vars['head_js_prepend_offset'];
    $already_included = 0;
    if (isset($template->smarty->tpl_vars['head_js_already_included']))
        $already_included = (int)$template->smarty->tpl_vars['head_js_already_included'];

    $site_path = '/';
    if (isset($template->smarty->tpl_vars['settings']) && $template->smarty->tpl_vars['settings']->value->site_path)
        $site_path = $template->smarty->tpl_vars['settings']->value->site_path;

    $version = '';
    if (isset($template->smarty->tpl_vars['settings']) && $template->smarty->tpl_vars['settings']->value->version)
        $version = $template->smarty->tpl_vars['settings']->value->version;
        
    $js_merge = false;
    if (isset($template->smarty->tpl_vars['settings']) && $template->smarty->tpl_vars['settings']->value->minify_js_merge)
        $js_merge = true;

    $ret = '';
    if (!$js_merge)
    {
        for ($i = $already_included; $i < count($template->smarty->tpl_vars['head_js']->value); $i++)
        {
            $ret.="<script src=\"".$site_path."/".$template->smarty->tpl_vars['head_js']->value[$i].".js".($version ? '?v='.$version : '')."\" type=\"text/javascript\"></script>\n";
        }
        $already_included = $i;
    } else {
        $scripts = array();
        for ($i = $already_included; $i < count($template->smarty->tpl_vars['head_js']->value); $i++)
        {
            $scripts[] = $template->smarty->tpl_vars['head_js']->value[$i].".js";
        }
        $already_included = $i;

        if ($scripts)
            $ret = "<script src=\"".$site_path."/min/?v=1.0&f=".implode(",", $scripts)."\" type=\"text/javascript\"></script>";
        else
            $ret = "";
    }

    $template->smarty->tpl_vars['head_js_already_included'] = $already_included;

    return $ret;
}

