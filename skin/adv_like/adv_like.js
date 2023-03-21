// html elements
const logo = document.getElementById("header-logo");
const spheader = document.getElementById("sp-header");
const spnavi = document.getElementById("sp-navigator");
const contents = document.getElementById("contents");
const menubar = document.getElementById("menubar");
const checkbox = document.getElementById("color_mode_switch");
const getCookie = Cookies.get('pkwk-darkmode');

// initialize Positions
calcLogoSize();
calcContentsHeight();
changeStyleSheet((typeof getCookie === 'undefined') ? 
    window.matchMedia('(prefers-color-scheme: dark)').matches
    : JSON.parse(Cookies.get('pkwk-darkmode'))
);


// display mode change(sp -> pc)
const mql = window.matchMedia('(max-width: 767px)');
mql.onchange = (e) => {
    if (!e.matches) calcLogoSize();
}


// Window Size Event Listener(sp)
window.addEventListener('resize',function(){
    calcContentsHeight();
});

// CSS selector switch
checkbox.addEventListener('change', function () {
    changeStyleSheet(checkbox.checked);
    Cookies.set('pkwk-darkmode', (checkbox.checked ? 1 : 0), {expires: 180});
});


// define functions
function changeStyleSheet(mode) {
    const element = document.getElementById("colorstyle");

    if(mode) {
      element.href = dir + "adv_like.color.dark.css";
      checkbox.checked = true;
    } else {
      element.href = dir + "adv_like.color.light.css";
      checkbox.checked = false;
    }
}

function calcContentsHeight() {
    spnavi.style.marginTop = "-"+spnavi.getBoundingClientRect().height+"px";
    spnavi.style.top = (spheader.getBoundingClientRect().height-spnavi.getBoundingClientRect().height)+"px";
    contents.style.top = spheader.getBoundingClientRect().height+"px";
    menubar.style.top = spheader.getBoundingClientRect().height+"px";
}

function calcLogoSize() {
    logo.style.width = logo.getBoundingClientRect().height+"px";
}