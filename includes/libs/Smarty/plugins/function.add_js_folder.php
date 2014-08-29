<?php

function smarty_function_add_js_folder($params, $template)
{   
    if (empty($params['path'])) {
        trigger_error("[plugin] fetch parameter 'path' cannot be empty",E_USER_NOTICE);
        return;
    }
    if (!isset($template->smarty->tpl_vars['head_js'])) {
        trigger_error("[plugin] need to be called in framework template file",E_USER_NOTICE);
        return;
    }

    $path = $params['path'];

    if ($handle = opendir(SITE_PATH."/app/public/".$path)) 
    {
        while (false !== ($entry = readdir($handle)))
            if ($entry && substr($entry, -3) === '.js')
                $template->smarty->tpl_vars['head_js']->value[] = $path.substr($entry, 0, -3);

        closedir($handle);
    }

    //$template->smarty->tpl_vars['head_js']->value[] = $file;

    return;
}

