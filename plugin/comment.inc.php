<?php
// PukiWiki - Yet another WikiWikiWeb clone
// comment.inc.php
// Copyright
//   2002-2020 PukiWiki Development Team
//   2001-2002 Originally written by yu-ji
// License: GPL v2 or (at your option) any later version
//
// Comment plugin

define('PLUGIN_COMMENT_DIRECTION_DEFAULT', '1'); // 1: above 0: below
define('PLUGIN_COMMENT_SIZE_MSG',  70);
define('PLUGIN_COMMENT_SIZE_NAME', 15);

// ----
define('PLUGIN_COMMENT_FORMAT_MSG',  '$msg');
define('PLUGIN_COMMENT_FORMAT_NAME', '[[$name]]');
define('PLUGIN_COMMENT_FORMAT_NOW',  '&new{$now};');
define('PLUGIN_COMMENT_FORMAT_STRING', "\x08MSG\x08 -- \x08NAME\x08 \x08NOW\x08");

function plugin_comment_action()
{
	global $vars, $now, $_title_updated, $_no_name;
	global $_msg_comment_collided, $_title_comment_collided;
	global $_comment_plugin_fail_msg;

	if (PKWK_READONLY) die_message('PKWK_READONLY prohibits editing');

	if (! isset($vars['msg'])) return array('msg'=>'', 'body'=>''); // Do nothing

	$vars['msg'] = str_replace("\n", '', $vars['msg']); // Cut LFs
	$head = '';
	$match = array();
	if (preg_match('/^(-{1,2})-*\s*(.*)/', $vars['msg'], $match)) {
		$head        = & $match[1];
		$vars['msg'] = & $match[2];
	}
	if ($vars['msg'] == '') return array('msg'=>'', 'body'=>''); // Do nothing

	$comment  = str_replace('$msg', $vars['msg'], PLUGIN_COMMENT_FORMAT_MSG);
	if(isset($vars['name']) || ($vars['nodate'] != '1')) {
		$_name = (! isset($vars['name']) || $vars['name'] == '') ? $_no_name : $vars['name'];
		$_name = ($_name == '') ? '' : str_replace('$name', $_name, PLUGIN_COMMENT_FORMAT_NAME);
		$_now  = ($vars['nodate'] == '1') ? '' :
			str_replace('$now', $now, PLUGIN_COMMENT_FORMAT_NOW);
		$comment = str_replace("\x08MSG\x08",  $comment, PLUGIN_COMMENT_FORMAT_STRING);
		$comment = str_replace("\x08NAME\x08", $_name, $comment);
		$comment = str_replace("\x08NOW\x08",  $_now,  $comment);
	}
	$comment = '-' . $head . ' ' . $comment;

	$postdata    = '';
	$comment_no  = 0;
	$above       = (isset($vars['above']) && $vars['above'] == '1');
	$comment_added = FALSE;
	foreach (get_source($vars['refer']) as $line) {
		if (! $above) $postdata .= $line;
		if (preg_match('/^#comment/i', $line) && $comment_no++ == $vars['comment_no']) {
			$comment_added = TRUE;
			if ($above) {
				$postdata = rtrim($postdata) . "\n" .
					$comment . "\n" .
					"\n";  // Insert one blank line above #commment, to avoid indentation
			} else {
				$postdata = rtrim($postdata) . "\n" .
					$comment . "\n";
			}
		}
		if ($above) $postdata .= $line;
	}
	$title = $_title_updated;
	$body = '';
	if ($comment_added) {
		// new comment added
		if (md5(get_source($vars['refer'], TRUE, TRUE)) !== $vars['digest']) {
			$title = $_title_comment_collided;
			$body  = $_msg_comment_collided . make_pagelink($vars['refer']);
		}
		page_write($vars['refer'], $postdata);
	} else {
		// failed to add the comment
		$title = $_title_comment_collided;
		$body  = $_comment_plugin_fail_msg . make_pagelink($vars['refer']);
	}
	$retvars['msg']  = $title;
	$retvars['body'] = $body;
	$vars['page'] = $vars['refer'];
	return $retvars;
}

function plugin_comment_convert()
{
	global $vars, $digest, $_btn_comment, $_btn_name, $_msg_comment;
	static $numbers = array();
	static $comment_cols = PLUGIN_COMMENT_SIZE_MSG;

	if (PKWK_READONLY) return ''; // Show nothing

	$page = $vars['page'];
	if (! isset($numbers[$page])) $numbers[$page] = 0;
	$comment_no = $numbers[$page]++;

	$options = func_num_args() ? func_get_args() : array();
	if (in_array('noname', $options)) {
		$nametags = '<label for="_p_comment_comment_' . $comment_no . '">' .
			$_msg_comment . '</label>';
	} else {
		$nametags = '<label for="_p_comment_name_' . $comment_no . '">' .
			$_btn_name . '</label>' .
			'<input type="text" name="name" id="_p_comment_name_' .
			$comment_no .  '" size="' . PLUGIN_COMMENT_SIZE_NAME .
			'" />' . "\n";
	}
	$nodate = in_array('nodate', $options) ? '1' : '0';
	$above  = in_array('above',  $options) ? '1' :
		(in_array('below', $options) ? '0' : PLUGIN_COMMENT_DIRECTION_DEFAULT);

	$script = get_page_uri($page);
	$s_page = htmlsc($page);
	$string = <<<EOD
<br />
<form action="$script" method="post" class="_p_comment_form">
 <div>
  <input type="hidden" name="plugin" value="comment" />
  <input type="hidden" name="refer"  value="$s_page" />
  <input type="hidden" name="comment_no" value="$comment_no" />
  <input type="hidden" name="nodate" value="$nodate" />
  <input type="hidden" name="above"  value="$above" />
  <input type="hidden" name="digest" value="$digest" />
  $nametags
  <input type="submit" name="comment" value="$_btn_comment" /><br>
  <textarea name="msg" id="_p_comment_comment_{$comment_no}"
   size="$comment_cols" required></textarea>
 </div>
</form>
EOD;

	return $string;
}
