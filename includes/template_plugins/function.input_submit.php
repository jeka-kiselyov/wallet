<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {input_submit} function plugin
 *
 * Type:     function<br>
 * Name:     input_submit<br>
 */
function smarty_function_input_submit($params, $template)
{
    $name = "";
    $id = "";
    $value = "";
    $bootstrap_horizontal = false;

    $i18n = false;

    foreach($params as $_key => $_val) {
        switch($_key) {

            case 'name': $name = htmlspecialchars($_val); break;
            case 'id': $id = htmlspecialchars($_val); break;
            case 'value': $value = htmlspecialchars($_val); break;
            case 'bootstrap_horizontal': $bootstrap_horizontal = $_val; break;
            case 'i18n': $i18n = (bool)$_val; break;

            default: break;
        }
    }

    if ($i18n && $value)
    {
        $i18n = autoloader_get_model_or_class('i18n');
        $value = $i18n->translate($value);
    }

    $_html_result = "\n";

    if ($bootstrap_horizontal)
    {
       // $_html_result.= "  <div class=\"control-group\">\n";
       // $_html_result.= "  <div class=\"controls\">";
    }

    $_html_result.= "    <input type=\"submit\" name=\"".$name."\" value=\"".$value."\" id=\"".$id."\" class=\"btn btn-primary\">\n";

    if ($bootstrap_horizontal)
    {
       // $_html_result.= "  </div>\n";
       // $_html_result.= "  </div>\n";
    }
    
    return $_html_result;
}

?>