<?php
// PukiWiki - Yet another WikiWikiWeb clone
// recent.inc.php
// Copyright
//   2002-2017 PukiWiki Development Team
//   2002      Y.MASUI http://masui.net/pukiwiki/ masui@masui.net
// License: GPL v2 or (at your option) any later version
//
// Recent plugin -- Show RecentChanges list
//   * Usually used at 'MenuBar' page
//   * Also used at special-page, without no #recnet at 'MenuBar'

// Default number of 'Show latest N changes'
define('PLUGIN_RECENT_DEFAULT_LINES', 10);

// Limit number of executions
define('PLUGIN_RECENT_EXEC_LIMIT', 2); // N times per one output

// ----

define('PLUGIN_RECENT_USAGE', '#recent(number-to-show)');

// Place of the cache of 'RecentChanges'
define('PLUGIN_RECENT_CACHE', CACHE_DIR . 'recent.dat');

function plugin_recent_convert()
{
	global $vars, $date_format, $_recent_plugin_frame;
	static $exec_count = 1;

	$recent_lines = PLUGIN_RECENT_DEFAULT_LINES;
	if (func_num_args()) {
		$args = func_get_args();
		if (! is_numeric($args[0]) || isset($args[1])) {
			return PLUGIN_RECENT_USAGE . '<br />';
		} else {
			$recent_lines = $args[0];
		}
	}

	// Show only N times
	if ($exec_count > PLUGIN_RECENT_EXEC_LIMIT) {
		return '#recent(): You called me too much' . '<br />' . "\n";
	} else {
		++$exec_count;
	}

	if (! file_exists(PLUGIN_RECENT_CACHE)) {
		put_lastmodified();
		if (! file_exists(PLUGIN_RECENT_CACHE)) {
			return '#recent(): Cache file of RecentChanges not found' . '<br />';
		}
	}

	// Get latest N changes
	$lines = file_head(PLUGIN_RECENT_CACHE, $recent_lines);
	if ($lines == FALSE) return '#recent(): File can not open' . '<br />' . "\n";
	$date = $items = '';
	foreach ($lines as $line) {
		list($time, $page) = explode("\t", rtrim($line));
		if (check_non_list($page)) continue;

		$_date = get_date($date_format, $time);
		if ($date != $_date) {
			// End of the day
			if ($date != '') $items .= '</ul>' . "\n";

			// New day
			$date = $_date;
			$items .= '<strong>' . $date . '</strong>' . "\n" .
				'<ul class="recent_list">' . "\n";
		}

		$s_page = htmlsc($page);

		if ($page === $vars['page']) {
			// No need to link to the page you just read, or notify where you just read
			$items .= ' <li>' . $s_page . '</li>' . "\n";
		} else {
			$attrs = get_page_link_a_attrs($page);
			$items .= ' <li><a href="' . get_page_uri($page) . '" class="' .
				$attrs['class'] . '" data-mtime="' . $attrs['data_mtime'] .
				'">' . $s_page . '</a></li>' . "\n";
		}
	}
	// End of the day
	if ($date != '') $items .= '</ul>' . "\n";

	return sprintf($_recent_plugin_frame, count($lines), $items);
}
