<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {input_select} function plugin
 *
 * Type:     function<br>
 * Name:     input_select<br>
 */
function smarty_function_input_select($params, $template)
{
    $name = "";
    $id = "";
    $value = "";
    $caption = "";
    $values = array();

    $default_value = "";
    $default_text = "";
    $get_value_from_post = false;

    foreach($params as $_key => $_val) {
        switch($_key) {

            case 'caption':  $caption = htmlspecialchars($_val); break;
            case 'name': $name = htmlspecialchars($_val); break;
            case 'id': $id = htmlspecialchars($_val); break;
            case 'value': $value = htmlspecialchars($_val); break;
            case 'size': $size = $_val; break;

            case 'default_value': $default_value = htmlspecialchars($_val); break;
            case 'default_text': $default_text = htmlspecialchars($_val); break;
            case 'values': $values = $_val; break;
            case 'getvaluefrompost': $get_value_from_post = $_val; break;

            default: break;
        }
    }

    if ($get_value_from_post && isset($_POST[$name])) $value = htmlspecialchars($_POST[$name]);

    $_html_result = "\n";
    $_html_result.= "  <div class=\"jform_element\">\n";
    $_html_result.= "   <div class=\"jform_caption\">".$caption."</div>\n";
    $_html_result.= "   <div class=\"jform_input\">\n";
    $_html_result.= "    <select name=\"".$name."\" ";
    if (isset($size) && $size) $_html_result.= " size=\"".$size."\"  ";
    if ($id) $_html_result.= " id=\"".$id."\" ";
    $_html_result.= ">\n";
    if ($default_text) $_html_result.= "     <option value=\"".$default_value."\">".$default_text."</option>\n";

    if (isset($values[0]) && isset($values[0]['text']) && isset($values[0]['value']))
    foreach ($values as $v)
    {
     $_html_result.= "     <option value=\"".$v['value']."\"";
     if ($value == $v['value']) $_html_result.= " selected=\"selected\"";
     $_html_result.= ">".$v['text']."</option>\n";
    } else
    if (isset($values[0]) && isset($values[0]['id']) && isset($values[0]['name']))
    foreach ($values as $v)
    {
     $_html_result.= "     <option value=\"".$v['id']."\"";
     if ($value == $v['id']) $_html_result.= " selected=\"selected\"";
     $_html_result.= ">".$v['name']."</option>\n";
    }

    $_html_result.= "    </select>\n";
    $_html_result.= "   </div>\n";
    $_html_result.= "   <div class=\"clear\"></div>\n";
    $_html_result.= "  </div>\n";

    return $_html_result;
}

?>