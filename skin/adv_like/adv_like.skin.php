<?php
// PukiWiki - Yet another WikiWikiWeb clone.
// pukiwiki.skin.php
// Copyright
//   2023 root-sbh
//   2002-2021 PukiWiki Development Team
//   2001-2002 Originally written by yu-ji
// License: GPL v2 or (at your option) any later version
//
// Customized PukiWiki default skin

// ------------------------------------------------------------
// Settings (define before here, if you want)

// Set site identities
$_IMAGE['skin']['logo']     = 'pukiwiki.png';
$_IMAGE['skin']['favicon']  = ''; // Sample: 'image/favicon.ico';

// SKIN_DEFAULT_DISABLE_TOPICPATH
//   1 = Show reload URL
//   0 = Show topicpath
if (! defined('SKIN_DEFAULT_DISABLE_TOPICPATH'))
	define('SKIN_DEFAULT_DISABLE_TOPICPATH', 1); // 1, 0

// Show / Hide navigation bar UI at your choice
// NOTE: This is not stop their functionalities!
if (! defined('PKWK_SKIN_SHOW_NAVBAR'))
	define('PKWK_SKIN_SHOW_NAVBAR', 1); // 1, 0

// Show / Hide toolbar UI at your choice
// NOTE: This is not stop their functionalities!
if (! defined('PKWK_SKIN_SHOW_TOOLBAR'))
	define('PKWK_SKIN_SHOW_TOOLBAR', 1); // 1, 0

// ------------------------------------------------------------
// Code start

// Prohibit direct access
if (! defined('UI_LANG')) die('UI_LANG is not set');
if (! isset($_LANG)) die('$_LANG is not set');
if (! defined('PKWK_READONLY')) die('PKWK_READONLY is not set');

$lang  = & $_LANG['skin'];
$link  = & $_LINK;
$image = & $_IMAGE['skin'];
$rw    = ! PKWK_READONLY;

$current_url = $link['top'].substr($_SERVER['REQUEST_URI'],1);

// MenuBar
// $menu = arg_check('read') && exist_plugin_convert('menu') ? do_plugin_convert('menu') : FALSE;
//常に表示
$menu = exist_plugin_convert('menu') ? do_plugin_convert('menu') : FALSE;
// RightBar
$rightbar = FALSE;
// if (arg_check('read') && exist_plugin_convert('rightbar')) {
//常に表示(存在すれば)
if (exist_plugin_convert('rightbar')) {
	$rightbar = do_plugin_convert('rightbar');
}
// ------------------------------------------------------------
// Output

// HTTP headers
pkwk_common_headers();
header('Cache-control: no-cache');
header('Pragma: no-cache');
header('Content-Type: text/html; charset=' . CONTENT_CHARSET);

?>
<!DOCTYPE html>
<html lang="<?php echo LANG ?>">
<head>
 <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CONTENT_CHARSET ?>" />
 <meta name="viewport" content="width=device-width, initial-scale=1.0" />
 <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
<?php if ($nofollow || ! $is_read)  { ?> <meta name="robots" content="NOINDEX,NOFOLLOW" /><?php } ?>
<?php if ($html_meta_referrer_policy) { ?> <meta name="referrer" content="<?php echo htmlsc($html_meta_referrer_policy) ?>" /><?php } ?>

 <title><?php echo $title ?> - <?php echo $page_title ?></title>

 <link rel="SHORTCUT ICON" href="<?php echo $image['favicon'] ?>" />
 <link rel="stylesheet" type="text/css" href="<?php echo SKIN_DIR ?>adv_like.css" />
 <link rel="stylesheet" type="text/css" href="<?php echo SKIN_DIR ?>adv_color.css" />
 <link rel="alternate" type="application/rss+xml" title="RSS" href="<?php echo $link['rss'] ?>" /><?php // RSS auto-discovery ?>
 <script type="text/javascript" src="skin/main.js" defer></script>
 <script type="text/javascript" src="skin/search2.js" defer></script>

<?php echo $head_tag ?>
</head>
<body>
<?php echo $html_scripting_data ?>
<div id="header">
 <div id="header-logo">
  <a href="<?php echo $link['top'] ?>"><img id="logo" src="<?php echo IMAGE_DIR . $image['logo'] ?>" alt="<?php echo $page_title ?>" title="<?php echo $page_title ?>" /></a>
 </div>
 <div id="header-title">
  <h1 class="title"><a href="<?php echo $link['top'] ?>"><?php echo $page_title ?></a></h1>

  <?php if(SKIN_DEFAULT_DISABLE_TOPICPATH) { ?>
   <h2 class="title"><a href="<?php echo $current_url ?>"><span class="small"><?php echo $title ?></span></a></h2>
  <?php } else { ?>
   <span class="small">
   <?php require_once(PLUGIN_DIR . 'topicpath.inc.php'); echo plugin_topicpath_inline(); ?>
   </span>
  <?php } ?>
  <div id="lastmodified">&nbsp;
  <?php if ($lastmodified != '') { ?>
   Last-modified: <?php echo $lastmodified ?><?php if ($is_freeze) { ?>&nbsp;<i class="fas fa-ban"></i><?php } ?>
  <?php } ?>
  </div>
 </div>
</div>

<input type="checkbox" class="openMenubar" id="openMenubar">
<input type="checkbox" class="openNavibar" id="openNavibar">

<div id="sp-header">
  <label for="openMenubar" class="menubarIconToggle">
    <div class="openMenubarButton">Menu</div>
  </label>
  <div class="wikiPageTitle">
	<a href="<?php echo $link['top'] ?>"><?php echo $page_title ?></a>&nbsp;-&nbsp;<a href="<?php echo $current_url ?>"><?php echo $title ?></a>
  </div>
  <label for="openNavibar" class="navibarIconToggle">
	<div class="openNavibarButton">Navi</div>
  </label>
</div>

<?php include("adv_like.nav.php"); ?>
<div id="navigator">
<?php include("adv_like.pc-nav.php"); ?>
</div>
<div id="sp-navigator">
 <?php include("adv_like.sp-nav.php"); ?>
</div>

<?php echo $hr ?>

<div id="contents">
 <div id="body"><?php echo $body ?></div>
<?php if ($menu) { ?>
 <div id="menubar"><?php echo $menu ?></div>
<?php } ?>
<?php if ($rightbar) { ?>
 <div id="rightbar"><?php echo $rightbar ?></div>
<?php } ?>
</div>

<?php if ($notes != '') { ?>
<div id="note"><?php echo $notes ?></div>
<?php } ?>

<?php if ($attaches != '') { ?>
<div id="attach">
<?php echo $hr ?>
<?php echo $attaches ?>
</div>
<?php } ?>

<?php echo $hr ?>

<?php if (PKWK_SKIN_SHOW_TOOLBAR) { ?>
<!-- Toolbar -->
<div id="toolbar">
<?php

// Set toolbar-specific images
$_IMAGE['skin']['reload']   = 'reload.png';
$_IMAGE['skin']['new']      = 'new.png';
$_IMAGE['skin']['edit']     = 'edit.png';
$_IMAGE['skin']['freeze']   = 'freeze.png';
$_IMAGE['skin']['unfreeze'] = 'unfreeze.png';
$_IMAGE['skin']['diff']     = 'diff.png';
$_IMAGE['skin']['upload']   = 'file.png';
$_IMAGE['skin']['copy']     = 'copy.png';
$_IMAGE['skin']['rename']   = 'rename.png';
$_IMAGE['skin']['top']      = 'top.png';
$_IMAGE['skin']['list']     = 'list.png';
$_IMAGE['skin']['search']   = 'search.png';
$_IMAGE['skin']['recent']   = 'recentchanges.png';
$_IMAGE['skin']['backup']   = 'backup.png';
$_IMAGE['skin']['help']     = 'help.png';
$_IMAGE['skin']['rss']      = 'rss.png';
$_IMAGE['skin']['rss10']    = & $_IMAGE['skin']['rss'];
$_IMAGE['skin']['rss20']    = 'rss20.png';
$_IMAGE['skin']['rdf']      = 'rdf.png';

function _toolbar($key, $x = 20, $y = 20){
	$lang  = & $GLOBALS['_LANG']['skin'];
	$link  = & $GLOBALS['_LINK'];
	$image = & $GLOBALS['_IMAGE']['skin'];
	if (! isset($lang[$key]) ) { echo 'LANG NOT FOUND';  return FALSE; }
	if (! isset($link[$key]) ) { echo 'LINK NOT FOUND';  return FALSE; }
	if (! isset($image[$key])) { echo 'IMAGE NOT FOUND'; return FALSE; }

	echo '<a href="' . $link[$key] . '">' .
		'<img src="' . IMAGE_DIR . $image[$key] . '" width="' . $x . '" height="' . $y . '" ' .
			'alt="' . $lang[$key] . '" title="' . $lang[$key] . '" />' .
		'</a>';
	return TRUE;
}
?>
 <?php _toolbar('top') ?>

<?php if ($is_page) { ?>
 &nbsp;
 <?php if ($rw) { ?>
	<?php _toolbar('edit') ?>
	<?php if ($is_read && $function_freeze) { ?>
		<?php if (! $is_freeze) { _toolbar('freeze'); } else { _toolbar('unfreeze'); } ?>
	<?php } ?>
 <?php } ?>
 <?php _toolbar('diff') ?>
<?php if ($do_backup) { ?>
	<?php _toolbar('backup') ?>
<?php } ?>
<?php if ($rw) { ?>
	<?php if ((bool)ini_get('file_uploads')) { ?>
		<?php _toolbar('upload') ?>
	<?php } ?>
	<?php _toolbar('copy') ?>
	<?php _toolbar('rename') ?>
<?php } ?>
 <?php _toolbar('reload') ?>
<?php } ?>
 &nbsp;
<?php if ($rw) { ?>
	<?php _toolbar('new') ?>
<?php } ?>
 <?php _toolbar('list')   ?>
 <?php _toolbar('search') ?>
 <?php _toolbar('recent') ?>
 &nbsp; <?php _toolbar('help') ?>
 &nbsp; <?php _toolbar('rss10', 36, 14) ?>
</div>
<?php } // PKWK_SKIN_SHOW_TOOLBAR ?>

<div id="sp-lastmodified">
<?php if ($lastmodified != '') { ?>
	<div id="lastmodified">Last-modified: <?php echo $lastmodified ?><?php if ($is_freeze) { ?>&nbsp;<i class="fas fa-ban"></i><?php } ?></div>
<?php } ?>
</div>

<?php if ($related != '') { ?>
<div id="related">Link: <?php echo $related ?></div>
<?php } ?>

<div id="footer">
 Founded by: <a href="<?php echo $modifierlink ?>"><?php echo $modifier ?></a>
 <p>
 HTML convert time: <?php echo elapsedtime() ?> sec.<br>
 <?php echo S_COPYRIGHT ?>.
 <!-- Powered by PHP <?php echo PHP_VERSION ?>. -->
 </p>
</div>
</body>
</html>
