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
    $js_merge = false;
    if (isset($template->smarty->tpl_vars['settings']) && $template->smarty->tpl_vars['settings']->value->minify_js_merge)
        $js_merge = true;

    if (isset($template->smarty->tpl_vars['settings']) && $template->smarty->tpl_vars['settings']->value->cloudfront_domain)
        $site_path = "//".$template->smarty->tpl_vars['settings']->value->cloudfront_domain."/app/public";

    $ret = '';
    if (!$js_merge)
    {
        for ($i = $already_included; $i < count($template->smarty->tpl_vars['head_js']->value); $i++)
        {
            $ret.="<script src=\"".$site_path."/".$template->smarty->tpl_vars['head_js']->value[$i].".js?v={$template->smarty->tpl_vars['settings']->value->version}\" type=\"text/javascript\"></script>\n";
        }
        $already_included = $i;
    } else {
        $hash_items = '';
        for ($i = $already_included; $i < count($template->smarty->tpl_vars['head_js']->value); $i++)
        {
            $hash_items.= '||'.$template->smarty->tpl_vars['head_js']->value[$i];
        }
        $hashed = crc32($hash_items);
        if (@is_file(SITE_PATH_APP.'public/scripts/dist/'.$hashed.".min.js"))
        {
            $ret.="<script src=\"".$site_path."/scripts/dist/".$hashed.".min.js?v={$template->smarty->tpl_vars['settings']->value->version}\" type=\"text/javascript\"></script>\n";
        } else {
            $data = explode('||', $hash_items);
            $data = array_filter($data);

            if (!is_file(SITE_PATH_CACHE.'/minification/js-'.$hashed.'.json'))
            {
                $json['elements'] = $data;
                $json['hash'] = $hashed;

                $json = json_encode($json);
                file_put_contents(SITE_PATH_CACHE.'/minification/js-'.$hashed.'.json', $json);
            }

            foreach ($data as $file) {
                $ret.="<script src=\"".$site_path."/".$file.".js?v={$template->smarty->tpl_vars['settings']->value->version}\" type=\"text/javascript\"></script>\n";
            }
        }        
    }

    $template->smarty->tpl_vars['head_js_already_included'] = $already_included;

    return $ret;
}

