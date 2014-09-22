<?php

function smarty_function_include_css_files($params, $template)
{   
    if (!isset($template->smarty->tpl_vars['head_css'])) {
        trigger_error("[plugin] need to be called in framework template file",E_USER_NOTICE);
        return;
    }
    $prepend_offset = 0;
    if (isset($template->smarty->tpl_vars['head_css_prepend_offset']))
        $prepend_offset = (int)$template->smarty->tpl_vars['head_css_prepend_offset'];
    $already_included = 0;
    if (isset($template->smarty->tpl_vars['head_css_already_included']))
        $already_included = (int)$template->smarty->tpl_vars['head_css_already_included'];
    $site_path = '/';
    if (isset($template->smarty->tpl_vars['settings']) && $template->smarty->tpl_vars['settings']->value->site_path)
        $site_path = $template->smarty->tpl_vars['settings']->value->site_path;
    $css_merge = false;
    if (isset($template->smarty->tpl_vars['settings']) && $template->smarty->tpl_vars['settings']->value->minify_css_merge)
        $css_merge = true;

    $ret = '';
    if (!$css_merge)
    {
        for ($i = $already_included; $i < count($template->smarty->tpl_vars['head_css']->value); $i++)
        {
            $ret.="<link href=\"".$site_path."/".$template->smarty->tpl_vars['head_css']->value[$i].".css\" media=\"screen\" rel=\"stylesheet\" type=\"text/css\" />\n";
        }
        $already_included = $i;
    } else {
        $styles = array();
        for ($i = $already_included; $i < count($template->smarty->tpl_vars['head_css']->value); $i++)
        {
            $styles[] = $template->smarty->tpl_vars['head_css']->value[$i].".css";
        }
        $already_included = $i;

        if ($styles)
            $ret = "<link href=\"".$site_path."/min/?v=1.0&f=".implode(",", $styles)."\" media=\"screen\" rel=\"stylesheet\" type=\"text/css\" />\n";
        else
            $ret = "";
    }

    $template->smarty->tpl_vars['head_css_already_included'] = $already_included;

    return $ret;
}

