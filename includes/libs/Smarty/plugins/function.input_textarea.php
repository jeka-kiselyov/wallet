<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {input_textarea} function plugin
 *
 * Type:     function<br>
 * Name:     input_textarea<br>
 */
function smarty_function_input_textarea($params, $template)
{
    $name = "";
    $id = "";
    $value = "";
    $caption = "";
    $get_value_from_post = false;
    $size = "";

    foreach($params as $_key => $_val) {
        switch($_key) {

            case 'caption':  $caption = htmlspecialchars($_val); break;
            case 'name': $name = htmlspecialchars($_val); break;
            case 'id': $id = htmlspecialchars($_val); break;
            case 'value': $value = htmlspecialchars($_val); break;
            case 'size': $size = $_val; break;
            case 'getvaluefrompost': $get_value_from_post = $_val; break;

            default: break;
        }
    }

    if ($get_value_from_post && isset($_POST[$name])) $value = htmlspecialchars($_POST[$name]);

    $_html_result = "\n";
    $_html_result.= "  <div class=\"jform_element\">\n";
    $_html_result.= "   <div class=\"jform_caption\">".$caption."</div>\n";
    $_html_result.= "   <div class=\"jform_input\">\n";
    $_html_result.= "    <textarea name=\"".$name."\" ";
    if ($id) $_html_result.= " id=\"".$id."\" ";
    $_html_result.= ">".$value."</textarea>\n";
    $_html_result.= "   </div>\n";
    $_html_result.= "   <div class=\"clear\"></div>\n";
    $_html_result.= "  </div>\n";

    return $_html_result;
}

?>