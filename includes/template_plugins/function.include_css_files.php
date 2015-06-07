<?php

function smarty_function_include_css_files($params, $template)
{   
    if (!isset($template->smarty->tpl_vars['head_css'])) {
        trigger_error("[plugin] need to be called in framework template file",E_USER_NOTICE);
        return;
    }

    $prepend_offset = 0;
    $already_included = 0;
    $site_path = '/';
    $css_merge = false;
    $version_suffix = 'none';
    $current_verion = '';

    $prepend_offset = isset($template->smarty->tpl_vars['head_css_prepend_offset']) ? (int)$template->smarty->tpl_vars['head_css_prepend_offset'] : 0;
    $already_included = isset($template->smarty->tpl_vars['head_css_already_included']) ? (int)$template->smarty->tpl_vars['head_css_already_included'] : 0;

    if (isset($template->smarty->tpl_vars['settings'], $template->smarty->tpl_vars['settings']->value) && is_object($template->smarty->tpl_vars['settings']->value))
    {
        $settings = $template->smarty->tpl_vars['settings']->value;

        if ($settings->site_path !== NULL)
            $site_path = $settings->site_path;
        if ($settings->minify_css_merge !== NULL && $settings->minify_css_merge)
            $css_merge = true;
        if ($settings->css_version_suffix !== NULL && ($settings->css_version_suffix == 'get' || $settings->css_version_suffix == 'suffix') )
            $version_suffix = $settings->css_version_suffix;
        if ($settings->cloudfront_domain !== NULL && $settings->cloudfront_domain)
            $site_path = $settings->cloudfront_domain."/app/public";
        if ($settings->version !== NULL)
            $current_verion = $settings->version;
    }

    $ret = '';
    $displayed = false;
    if ($css_merge)
    {
        $hash_items = '';
        for ($i = $already_included; $i < count($template->smarty->tpl_vars['head_css']->value); $i++)
        {
            $hash_items.= '||'.$template->smarty->tpl_vars['head_css']->value[$i]['file'].".".$template->smarty->tpl_vars['head_css']->value[$i]['extension'];
        }
        $hashed = md5($hash_items);

        if (@is_file(SITE_PATH_APP.'public/css/dist/'.$hashed.".min.css"))
        {
            //// echo file
            if ($version_suffix == 'get')
                $ret.="<link href=\"".$site_path."/css/dist/".$hashed.".min.css?v=".$current_verion."\" media=\"screen\" rel=\"stylesheet\" type=\"text/css\" />\n";
            elseif ($version_suffix == 'suffix')
                $ret.="<link href=\"".$site_path."/css/dist/".$hashed.".min.v".$current_verion.".css\" media=\"screen\" rel=\"stylesheet\" type=\"text/css\" />\n";
            else
                $ret.="<link href=\"".$site_path."/css/dist/".$hashed.".min.css\" media=\"screen\" rel=\"stylesheet\" type=\"text/css\" />\n";

            $displayed = true;
            $already_included = $i;
        } else {
            $data = explode('||', $hash_items);
            $data = array_filter($data); /// remove empty elements

            if (!is_file(SITE_PATH_CACHE.'/minification/css-'.$hashed.'.json'))
            {
                $json['elements'] = $data;
                $json['hash'] = $hashed;

                $json = json_encode($json);
                file_put_contents(SITE_PATH_CACHE.'/minification/css-'.$hashed.'.json', $json);
            }            
        }
    }


    $has_less = false;
    
    if (!$displayed)
    {
        for ($i = $already_included; $i < count($template->smarty->tpl_vars['head_css']->value); $i++)
        {
            $file = $template->smarty->tpl_vars['head_css']->value[$i];

            if ($file['extension'] == 'less')
            {
                $rel = 'stylesheet/less';
                $has_less = true;
            }
            else
                $rel = 'stylesheet';

            if ($version_suffix == 'get')
                $ret.="<link href=\"".$site_path."/".$file['file'].".".$file['extension']."?v=".$current_verion."\" media=\"screen\" rel=\"".$rel."\" type=\"text/css\" />\n";
            elseif ($version_suffix == 'suffix')
                $ret.="<link href=\"".$site_path."/".$file['file'].".v".$current_verion.".".$file['extension']."\" media=\"screen\" rel=\"".$rel."\" type=\"text/css\" />\n";
            else
                $ret.="<link href=\"".$site_path."/".$file['file'].".".$file['extension']."\" media=\"screen\" rel=\"".$rel."\" type=\"text/css\" />\n";
        }
        $already_included = $i;

        $displayed = true;
    }

    $template->smarty->tpl_vars['head_css_already_included'] = $already_included;

    if ($has_less)
    {
        $has_already = false;
        foreach ($template->smarty->tpl_vars['head_js']->value as $already)
            if ($already == '//cdnjs.cloudflare.com/ajax/libs/less.js/2.5.0/less.min.js')
                $has_already = true;

        if (!$has_already)
            $template->smarty->tpl_vars['head_js']->value[] = '//cdnjs.cloudflare.com/ajax/libs/less.js/2.5.0/less.min.js';
    }


    return $ret;
}

