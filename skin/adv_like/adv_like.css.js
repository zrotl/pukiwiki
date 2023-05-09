// jQuery
const $stylesheet = $('#colorstyle');


// Cookies
const getCookie = Cookies.get('pkwk-darkmode');
let darkmode = (typeof getCookie === 'undefined') ? window.matchMedia('(prefers-color-scheme: dark)').matches: JSON.parse(Cookies.get('pkwk-darkmode'));

initStyleSheet(darkmode);


// define functions
function initStyleSheet(mode) {
    if(mode) {
        $stylesheet.attr('href', dir+'adv_like.color.dark.css');
    } else {
        $stylesheet.attr('href', dir+'adv_like.color.light.css');
    }
}