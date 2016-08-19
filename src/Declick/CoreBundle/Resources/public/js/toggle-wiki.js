var wikiVisible = false;
var $localFrame, $wikiFrame;


function openWiki() {
    $wikiFrame.stop().show().animate({
                  'left': '0'
                  }, 200);
    $localFrame.stop().animate({
            'padding-left': '365px'
            },200);
    wikiVisible = true;
}

function closeWiki() {
    $wikiFrame.stop().animate({
            left: '-365px'
            }, 200, function(){$('#wiki-frame').hide();});
    $localFrame.animate({
            'padding-left': '0px'
            },200);
    wikiVisible = false;
}

function hideWiki() {
    if (wikiVisible) {
        $wikiFrame.hide();
    }
}

function showWiki() {
    if (wikiVisible) {
        $wikiFrame.show();
    }
}

function toggleWiki() {
    if (wikiVisible) {
        closeWiki();
    } else {
        openWiki();
    }
}


$(function() {
    $localFrame = $('#local-frame');
    $wikiFrame = $('#wiki-frame');
    $wikiFrame.hide();
});