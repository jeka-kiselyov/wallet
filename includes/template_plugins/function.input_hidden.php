<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {input_hidden} function plugin
 *
 * Type:     function<br>
 * Name:     input_hidden<br>
 */
function smarty_function_input_hidden($params, $template)
{
    $name = "";
    $id = "";
    $value = "";

    foreach($params as $_key => $_val) {
        switch($_key) {

            case 'name': $name = htmlspecialchars($_val); break;
            case 'id': $id = htmlspecialchars($_val); break;
            case 'value': $value = htmlspecialchars($_val); break;

            default: break;
        }
    }

    $_html_result = "\n";
    $_html_result.= "    <input type=\"hidden\" name=\"".$name."\" value=\"".$value."\" id=\"".$id."\">";

    return $_html_result;
}

?>