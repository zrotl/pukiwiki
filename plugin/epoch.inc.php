<?php
/**
 * PukiWiki Plus! epoch plugin.
 *
 * @copyright   Copyright &copy; 2008, Katsumi Saito <katsumi@jo1upk.ymt.prug.or.jp>
 * @version     $Id: epoch.inc.php,v 0.4.1 2023/04/13 00:40:00 root Exp $
 * @license     http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 *  for BugTrack/83
 *
 * &epoch(1234578098);
 * &epoch(1234578098,[class name, such as 'comment_date']);
 */

// 1day = 86400;

// use PukiWiki\Utility;
// use PukiWiki\Time;

function plugin_epoch_init() {
    $_epoch_labels = array(
        'week' => array(
            array('Sun', 'Sunday'),
            array('Mon', 'Monday'),
            array('Tue', 'Tuesday'),
            array('Wed', 'Wednesday'),
            array('Thu', 'Thursday'),
            array('Fri', 'Friday'),
            array('Sat', 'Saturday')
        ),
        'month'=> array(
            1	=>array('_Jan', 'January'),
            2	=>array('_Feb', 'February'),
            3	=>array('_Mar', 'March'),
            4	=>array('_Apr', 'April'),
            5	=>array('_May', 'May'),
            6	=>array('_Jun', 'June'),
            7	=>array('_Jul', 'July'),
            8	=>array('_Aug', 'August'),
            9	=>array('_Sep', 'September'),
            10	=>array('_Oct', 'October'),
            11	=>array('_Nov', 'November'),
            12	=>array('_Dec', 'December')
        )
    );
    set_plugin_messages($_epoch_labels);
}

function plugin_epoch_inline()
{
    plugin_epoch_init();
	$value = func_get_args();
	$args = func_num_args();

	if ($args > 3){
		return '&epoch(utime[,class]);';
	}
	
	$array = explode(',',$value[0]);

	$format = format($array[0]);
	$passaage = passage($array[0]);
	
	$class = (!empty($array[1])) ? $array[1] : 'epoch';

	$ret = '<time datetime="'.get_date('c',$value[0]).'" class="'.$class.'" title="'.$passaage.'">'.$format.'</time>';
	
	if (!empty($value[1])){
		$erapse = MUTIME - $value[0];
		
		if ($erapse < 432000){
			$ret .= ' <span class="';
			if ($erapse < 86400){
				$ret .= 'new1';
			}else{
				$ret .= 'new5';
			}
			$ret .= '">New</span>';
		}
	}
	
	return $ret;
}

function format($time, $quote = FALSE, $format = null)
{
    global $date_format, $time_format;

    //$time += ZONETIME;
    $wday = date('w', $time);

    $week   = $_epoch_labels['week'][$wday];

    if ($wday == 0) {
        // Sunday
        $style = 'week_sun';
    } else if ($wday == 6) {
        // Saturday
        $style = 'week_sat';
    }else{
        $style = 'week_day';
    }
    if (!isset($format)){
        $date = date($date_format, $time) .
            '(<abbr class="' . $style . '" title="' . $week[1]. '">'. $week[0] . '</abbr>)' .
            date($time_format, $time);
    }else{
        $month  = $_epoch_labels['month'][date('n', $time)];
        $month_short = $month[0];
        $month_long = $month[1];

        $date = str_replace(
            array(
                date('M', $time),	// 月。3 文字形式。
                date('l', $time),	// 曜日。フルスペル形式。
                date('D', $time)		// 曜日。3文字のテキスト形式。
            ),
            array(
                '<abbr class="month" title="' . $month[1]. '">'. $month[0] . '</abbr>',
                $week[1],
                '(<abbr class="' . $style . '" title="' . $week[1]. '">'. $week[0] . '</abbr>)'
            ),
            date($format, $time)
        );
    }

    return $quote ? '(' . $date . ')' : $date;
}

function passage($time){
    static $units = array('m'=>60, 'h'=>24, 'd'=>1);

    $time = max(0, (UTIME - $time) / 60); // minutes

    foreach ($units as $unit=>$card) {
        if ($time < $card) break;
        $time /= $card;
    }
    $time = floor($time) . $unit;

    return $time;
}

/* End of file epoch.inc.php */
/* Location: ./plugin/epoch.inc.php */
