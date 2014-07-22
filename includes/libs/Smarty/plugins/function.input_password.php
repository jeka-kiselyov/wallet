<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty {input_password} function plugin
 *
 * Type:     function<br>
 * Name:     input_password<br>
 */
function smarty_function_input_password($params, $template)
{
    $name = "";
    $id = "";
    $value = "";
    $caption = "";
    $placeholder = "";
    $bootstrap_horizontal = false;

    $i18n = false;

    foreach($params as $_key => $_val) {
        switch($_key) {

            case 'caption':  $caption = htmlspecialchars($_val); break;
            case 'name': $name = htmlspecialchars($_val); break;
            case 'id': $id = htmlspecialchars($_val); break;
            case 'value': $value = htmlspecialchars($_val); break;
            case 'size': $size = $_val; break;
            case 'placeholder':  $placeholder = htmlspecialchars($_val); break;
            case 'bootstrap_horizontal': $bootstrap_horizontal = $_val; break;
            case 'i18n': $i18n = (bool)$_val; break;

            default: break;
        }
    }

    if ($i18n)
    {
        $i18n = autoloader_get_model_or_class('i18n');
        if ($placeholder)
            $placeholder = $i18n->translate($placeholder);
        if ($caption)
            $caption = $i18n->translate($caption);
    }

    $_html_result = "\n";

    if ($bootstrap_horizontal)
    $_html_result.= "  <div class=\"form-group\">\n";

    if ($caption) $_html_result.= "  <label for=\"".$id."\">".$caption."</label>";

    //if ($bootstrap_horizontal)
    //$_html_result.= "  <div class=\"controls\">";

    $_html_result.= "    <input type=\"password\" name=\"".$name."\" value=\"".$value."\" ";
    if (isset($size) && $size) $_html_result.= " size=\"".$size."\"  ";
    if ($id) $_html_result.= " id=\"".$id."\" ";
    if ($placeholder) $_html_result.= " placeholder=\"".$placeholder."\" ";
    if ($bootstrap_horizontal) $_html_result.=" class=\"form-control\" ";
    $_html_result.= ">\n";

    if ($bootstrap_horizontal)
    {
       // $_html_result.= "  </div>\n";
        $_html_result.= "  </div>\n";
    }
    return $_html_result;
}

?>