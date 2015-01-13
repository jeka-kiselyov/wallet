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

    if (isset($template->smarty->tpl_vars['settings']) && $template->smarty->tpl_vars['settings']->value->cloudfront_domain)
        $site_path = "//".$template->smarty->tpl_vars['settings']->value->cloudfront_domain."/app/public";

    $ret = '';
    if (!$css_merge)
    {
        for ($i = $already_included; $i < count($template->smarty->tpl_vars['head_css']->value); $i++)
        {
            $ret.="<link href=\"".$site_path."/".$template->smarty->tpl_vars['head_css']->value[$i].".css?v={$template->smarty->tpl_vars['settings']->value->version}\" media=\"screen\" rel=\"stylesheet\" type=\"text/css\" />\n";
        }
        $already_included = $i;
    } else {

        $hash_items = '';
        for ($i = $already_included; $i < count($template->smarty->tpl_vars['head_css']->value); $i++)
        {
            $hash_items.= '||'.$template->smarty->tpl_vars['head_css']->value[$i];
        }
        $hashed = crc32($hash_items);
        if (@is_file(SITE_PATH_APP.'public/css/dist/'.$hashed.".min.css"))
        {
            //// echo file
            $ret.="<link href=\"".$site_path."/css/dist/".$hashed.".min.css?v={$template->smarty->tpl_vars['settings']->value->version}\" media=\"screen\" rel=\"stylesheet\" type=\"text/css\" />\n";

        } else {
            $data = explode('||', $hash_items);
            $data = array_filter($data);

            if (!is_file(SITE_PATH_CACHE.'/minification/css-'.$hashed.'.json'))
            {
                $json['elements'] = $data;
                $json['hash'] = $hashed;

                $json = json_encode($json);
                file_put_contents(SITE_PATH_CACHE.'/minification/css-'.$hashed.'.json', $json);
            }

            foreach ($data as $file) {
                $ret.="<link href=\"".$site_path.'/'.$file.".css?v={$template->smarty->tpl_vars['settings']->value->version}\" media=\"screen\" rel=\"stylesheet\" type=\"text/css\" />\n";
            }
        }
    }

    $template->smarty->tpl_vars['head_css_already_included'] = $already_included;

    return $ret;
}

