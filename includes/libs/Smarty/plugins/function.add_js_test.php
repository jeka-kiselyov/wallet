<?php

function smarty_function_add_js_test($params, $template)
{   
    if (empty($params['name'])) {
        trigger_error("[plugin] parameter 'name' cannot be empty",E_USER_NOTICE);
        return;
    }

    $name = $params['name'];
    $template->smarty->tpl_vars['js_tests']->value[] = $name;

    return;
}

