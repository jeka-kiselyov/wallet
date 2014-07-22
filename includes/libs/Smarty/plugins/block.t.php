<?php

function smarty_block_t_make_replaces($str)
{
	$tr = array();
	$p = 0;

	for ($i=1; $i < func_num_args(); $i++) {
		$arg = func_get_arg($i);
		
		if (is_array($arg)) {
			foreach ($arg as $aarg) {
				$tr['%'.++$p] = $aarg;
			}
		} else {
			$tr['%'.++$p] = $arg;
		}
	}
	
	return strtr($str, $tr);
}

function  smarty_block_t($params, $text, &$smarty, &$repeat)
{
	if (!$repeat)
	{
		if (!$text)
			return "";
		
		$i18n = autoloader_get_model_or_class('i18n');

		$text = stripslashes($text);
		$text = $i18n->translate($text);

		// run strarg if there are parameters
		if (count($params)) {
			$text = smarty_block_t_make_replaces($text, $params);
		}
		
		return $text;
	}
}
