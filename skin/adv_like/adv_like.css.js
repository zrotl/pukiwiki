// Cookies
const getCookie = Cookies.get('pkwk-darkmode');

changeStyleSheet((typeof getCookie === 'undefined') ? 
    window.matchMedia('(prefers-color-scheme: dark)').matches
    : JSON.parse(Cookies.get('pkwk-darkmode'))
);

// define functions
function changeStyleSheet(mode) {
    if(mode) {
        $stylesheet.attr('href', dir+'adv_like.color.dark.css');
        $colorcheckbox.prop("checked", true);
    } else {
        $stylesheet.attr('href', dir+'adv_like.color.light.css');
        $colorcheckbox.prop("checked", false);
    }
}