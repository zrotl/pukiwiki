<?php function _navigator($key, $value = '', $javascript = '') {
	    $lang = & $GLOBALS['_LANG']['skin'];
	    $link = & $GLOBALS['_LINK'];
	    if (! isset($lang[$key])) { echo 'LANG NOT FOUND'; return FALSE; }
        if (! isset($link[$key])) { echo 'LINK NOT FOUND'; return FALSE; }

        echo '<a href="' . $link[$key] . '" ' . $javascript . '>' .
            (($value === '') ? $lang[$key] : $value) .
            '</a>';

        return TRUE;
    }
?>