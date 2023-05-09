// html elements
const $logo = $('#header-logo');
const $spheader = $('#sp-header');
const $spnavi = $("#sp-navigator");
const $contents = $("#contents");
const $menubar = $("#menubar");
const $menubar_outer = $("#menubar-outer");
const $colorcheckbox = $("#color_mode_switch");
const $stylesheet = $('#colorstyle');

// initialize Positions
calcLogoSize();
calcContentsHeight();

// display mode change(sp -> pc)
window.matchMedia('(max-width: 767px)').onchange = (e) => {
    if (!e.matches) calcLogoSize();
}

// Window Size Event Listener(sp)
window.addEventListener('resize',function(){
    calcContentsHeight();
});

// CSS selector switch
$colorcheckbox.change(function () {
    changeStyleSheet($colorcheckbox.prop("checked"));
    Cookies.set('pkwk-darkmode', ($colorcheckbox.prop("checked") ? 1 : 0), {expires: 180});
});


// define functions
function calcContentsHeight() {
    var navi_height = $spnavi.outerHeight(true);
    var header_height =$spheader.outerHeight(true);

    $spnavi.css('margin-top', -navi_height-1+"px");
    $spnavi.css('top', (header_height-navi_height-1)+"px");
    $contents.css('top', header_height+"px");
    if (window.matchMedia("(max-width: 768px)").matches) {
        $menubar_outer.css('top', header_height+"px");
        $menubar.css('height', window.innerHeight-header_height-($menubar.innerHeight()-$menubar.height())+"px");
    } else {
        $menubar.css('height', 'auto');
    }
}

function calcLogoSize() {
    $logo.width($logo.outerHeight(true));
}