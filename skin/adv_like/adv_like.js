//html elements Position Adjustment
const logo = document.getElementById("header-logo");
logo.style.width = logo.getBoundingClientRect().height+"px";

const spheader = document.getElementById("sp-header");
const spnavi = document.getElementById("sp-navigator");
const contents = document.getElementById("contents");
spnavi.style.marginTop = "-"+spnavi.getBoundingClientRect().height+"px";
spnavi.style.top = (spheader.getBoundingClientRect().height-spnavi.getBoundingClientRect().height)+"px";

const mql = window.matchMedia('(max-width: 767px)');
mql.onchange = (e) => {
    if (!e.matches) {
        logo.style.width = logo.getBoundingClientRect().height+"px";
        contents.style.position = 'static';
    }
}

window.addEventListener('resize',function(){
    spnavi.style.marginTop = "-"+spnavi.getBoundingClientRect().height+"px";
    spnavi.style.top = (spheader.getBoundingClientRect().height-spnavi.getBoundingClientRect().height)+"px";
    contents.style.top = spheader.getBoundingClientRect().height+"px";
});


//CSS selector switch
const checkbox = document.getElementById("color_mode_switch");

checkbox.addEventListener('change', function () {
    changeStyleSheet(checkbox.checked);
    Cookies.set('pkwk-darkmode', (checkbox.checked ? 1 : 0), {expires: 180});
});

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

const getCookie = Cookies.get('pkwk-darkmode');
let dark_mode;
if (typeof getCookie === 'undefined') {
    dark_mode = window.matchMedia('(prefers-color-scheme: dark)').matches;
} else {
    dark_mode = JSON.parse(Cookies.get('pkwk-darkmode'));
}
changeStyleSheet(dark_mode);