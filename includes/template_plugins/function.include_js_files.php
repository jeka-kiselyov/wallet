<?php

function smarty_function_include_js_files($params, $template)
{   
    if (!isset($template->smarty->tpl_vars['head_js'])) {
        trigger_error("[plugin] need to be called in framework template file",E_USER_NOTICE);
        return;
    }

    $prepend_offset = 0;
    $already_included = 0;
    $site_path = '/';
    $js_merge = false;
    $version_suffix = 'none';
    $current_verion = '';
    $prepend = false; //// include only prepended files

    $prepend_offset = isset($template->smarty->tpl_vars['head_js_prepend_offset']) ? (int)$template->smarty->tpl_vars['head_js_prepend_offset'] : 0;
    $already_included = isset($template->smarty->tpl_vars['head_js_already_included']) ? (int)$template->smarty->tpl_vars['head_js_already_included'] : 0;
    $prepend = (isset($params['prepend']) && $params['prepend']) ? true : false;

    if ($prepend)
        $to_count = (int)$prepend_offset;
    else
        $to_count = count($template->smarty->tpl_vars['head_js']->value);

    if ($to_count <= $already_included)
        return '';

    if (isset($template->smarty->tpl_vars['settings'], $template->smarty->tpl_vars['settings']->value) && is_object($template->smarty->tpl_vars['settings']->value))
    {
        $settings = $template->smarty->tpl_vars['settings']->value;

        if ($settings->site_path !== NULL)
            $site_path = $settings->site_path;
        if ($settings->minify_js_merge !== NULL && $settings->minify_js_merge)
            $js_merge = true;
        if ($settings->js_version_suffix !== NULL && ($settings->js_version_suffix == 'get' || $settings->js_version_suffix == 'suffix') )
            $version_suffix = $settings->js_version_suffix;
        if ($settings->cloudfront_domain !== NULL && $settings->cloudfront_domain)
            $site_path = $settings->cloudfront_domain."/app/public";
        if ($settings->version !== NULL)
            $current_verion = $settings->version;
    }
    
    $ret = '';
    $displayed = false;

    if ($js_merge)
    {
        $hash_items = '';
        $has_local = false;
        for ($i = $already_included; $i < $to_count; $i++)
        if (strpos('//', $template->smarty->tpl_vars['head_js']->value[$i]) === false)
        {
            $file = $template->smarty->tpl_vars['head_js']->value[$i];

            if (strpos($file, '//') === false && strpos($file, 'http://') === false && strpos($file, 'https://') === false)
            {
                $hash_items.= '||'.$file;
                $has_local = true;
            }
        }

        if ($has_local)
        {
            $hashed = md5($hash_items);
            if (@is_file(SITE_PATH_APP.'public/scripts/dist/'.$hashed.".min.js"))
            {
                if ($version_suffix == 'get')
                    $ret.="<script src=\"".$site_path."/scripts/dist/".$hashed.".min.js?v=".$current_verion."\" type=\"text/javascript\"></script>\n";
                elseif ($version_suffix == 'suffix')
                    $ret.="<script src=\"".$site_path."/scripts/dist/".$hashed.".min.v".$current_verion.".js\" type=\"text/javascript\"></script>\n";
                else
                    $ret.="<script src=\"".$site_path."/scripts/dist/".$hashed.".min.js\" type=\"text/javascript\"></script>\n";

                for ($i = $already_included; $i < $to_count; $i++)
                {
                    if (!(strpos($file, '//') === false && strpos($file, 'http://') === false && strpos($file, 'https://') === false))
                    {
                        ///// Remote
                        $ret.="<script src=\"".$file['file']."\" type=\"text/javascript\"></script>\n";
                    }
                }

                $already_included = $i;
                $displayed = true;
            } else {
                $data = explode('||', $hash_items);
                $data = array_filter($data); /// remove empty elements

                if (!is_file(SITE_PATH_CACHE.'/minification/js-'.$hashed.'.json'))
                {
                    $json['elements'] = $data;
                    $json['hash'] = $hashed;

                    $json = json_encode($json);
                    file_put_contents(SITE_PATH_CACHE.'/minification/js-'.$hashed.'.json', $json);
                }    
            }
        }
    }


    if (!$displayed)
    {
        for ($i = $already_included; $i < $to_count; $i++)
        {
            $file = $template->smarty->tpl_vars['head_js']->value[$i];

            if (strpos($file, '//') === false && strpos($file, 'http://') === false && strpos($file, 'https://') === false)
            {
                //// Local
                if ($version_suffix == 'get')
                    $ret.="<script src=\"".$site_path."/".$file.".js?v=".$current_verion."\" type=\"text/javascript\"></script>\n";
                elseif ($version_suffix == 'suffix')
                    $ret.="<script src=\"".$site_path."/".$file.".v".$current_verion.".js\" type=\"text/javascript\"></script>\n";
                else
                    $ret.="<script src=\"".$site_path."/".$file.".js\" type=\"text/javascript\"></script>\n";
            } else {
                //// Remote
                $ret.="<script src=\"".$file."\" type=\"text/javascript\"></script>\n";
            }
        }
        $already_included = $i;

        $displayed = true;
    }

    $template->smarty->tpl_vars['head_js_already_included'] = $already_included;

    return $ret;
}

