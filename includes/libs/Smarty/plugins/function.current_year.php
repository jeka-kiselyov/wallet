<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {current_year} function plugin
 *
 * Type:     function<br>
 * Name:     current_year<br>
 */
function smarty_function_current_year($params, $template)
{
    return date("Y");
}

?>