<?php

function smarty_function_add_js($params, $template)
{   
    if (empty($params['file'])) {
        trigger_error("[plugin] fetch parameter 'file' cannot be empty",E_USER_NOTICE);
        return;
    }
    if (!isset($template->smarty->tpl_vars['head_js'])) {
        trigger_error("[plugin] need to be called in framework template file",E_USER_NOTICE);
        return;
    }

    $file = $params['file'];
    $prepend = false;
    if (isset($params['prepend']) && $params['prepend'])
        $prepend = true;

    if (!$prepend)
    {
        $template->smarty->tpl_vars['head_js']->value[] = $file;
        return;
    } else {
        $prepend_offset = 0;
        if (isset($template->smarty->tpl_vars['head_js_prepend_offset']))
            $prepend_offset = (int)$template->smarty->tpl_vars['head_js_prepend_offset'];

        array_splice($template->smarty->tpl_vars['head_js']->value, $prepend_offset, 0, $file);
        $template->smarty->tpl_vars['head_js_prepend_offset'] = $prepend_offset + 1;
        return;
    }
}

