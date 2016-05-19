function updateLocal(showEditor) {
    document.getElementById('local-frame').contentWindow.updateEnvironment(showEditor);
}

var localInitCallbacks = [];

function addLocalInitCallback(cb) {
    localInitCallbacks.push(cb);
}

function receiveMessage(event) {
    if(event.data === 'init') {
        for (var i=0; i<localInitCallbacks.length; i++) {
            localInitCallbacks[i].call(window);
        }
    }
}

window.addEventListener('message', receiveMessage, false);

function checkLocalUnsaved(message) {
    if (document.getElementById('local-frame').contentWindow.isUnsaved()) {
        return window.confirm(message);
    }
    return true;
}

function updateUserMenu(showLoading, local, showEditor) {
    /*if (typeof showLoading === 'undefined' || showLoading) {
        $("#user-dropdown-trigger").addClass("loading");
    }
    var $element = $("#user-menu");
    $element.load(url_user_menu, function() {
        $("#logout-link").click(logout);
        ajaxify($element);*/
    // quick fix
    // TODO: rearrange this
    if (local) {
        updateLocal(showEditor);
    }
    //});
}

function openContent() {
    var $content = $("#content");
    $content.show();
    $content.stop().animate({top:"0px"}, 600,function(){$('#local-frame').hide();});
    hideHelp();
}

function closeContent() {
    var $content = $("#content");
    var height = $content[0].scrollHeight;
    $('#local-frame').show();
    showHelp();
    $content.stop().animate({top:-height+"px"}, 600, function(){$(this).hide();});
}

function recordHistory(historyData) {
    if (typeof historyData.title === 'undefined') {
        historyData.title = "Declick";
    }
    window.history.pushState(historyData.data, historyData.title, historyData.url);        
}

function loadContent(url) {
    var $element = $("#content");
    $element.addClass("loading");
    $element.empty();
    $element.load(url, function() {
           ajaxify($(this));
           $element.removeClass("loading");
    });
}

function fetchContent(url, data, title) {
    if (typeof data !== 'undefined') {
        var historyData = {data:data, url:url};
        if (typeof data.content === 'undefined') {
            historyData.data.content = url;
        }
        if (typeof title !== 'undefined') {
            historyData.title = title;
        }
        recordHistory(historyData);
    }
    loadContent(url);
}

function removeActive() {
    $("#user-menu > li").removeClass("active").find("a").blur();
}

function setActive(elementName) {
    removeActive();
    $("#"+elementName).addClass("active");
    active_nav = elementName;
}

function create(event) {
    event.preventDefault();
    removeActive();
    closeContent();
    recordHistory({url:url_create, data:{active:'create'}});
}

function login(event) {
    event.preventDefault();
    var $form = $(this);
    var url = $form.attr( "action" );
    var posting = $.post(url, $form.serialize(), "json");
    $("#user-dropdown-trigger").addClass("loading");
    posting.done(function( data ) {
        if (typeof data.content !== 'undefined') {
            var $element = $("#user-menu");
            $element.html(data.content);
            $("#login-form").submit(login);
            $("#logout-link").click(logout);
            ajaxify($element);
        }
        if (typeof data.success !== 'undefined') {
            if (data.success) {
                updateLocal();
            }
        }
    });
}

function logout(event) {
    event.preventDefault();
    if (checkLocalUnsaved(messageUnsaved)) {
        var $link = $(this);
        var url = $link.attr( "href" );
        $("#user-dropdown-trigger").addClass("loading");
        var getting = $.get(url, "json");
        getting.done(function( data ) {
            if (typeof data.content !== 'undefined') {
                var $element = $("#user-menu");
                $element.html(data.content);
                $("#login-form").submit(login);
                $("#logout-link").click(logout);
                ajaxify($element);
                // If content is active, reload content
                var currentState = history.state;
                if (typeof currentState.content !== 'undefined') {
                    loadContent(currentState.content);
                }
            }
            if (typeof data.success !== 'undefined') {
                if (data.success) {
                    updateLocal();
                }
            }
        });
    }
}

function contentLink(event) {
    event.preventDefault();
    var $link = $(this);
    var url = $link.attr("href");
    var active = $link.attr("data-active");
    var title = $link.attr("data-title");
    openContent();
    if (typeof active !== 'undefined') {
        setActive(active);
        fetchContent(url, {active:active}, title);
    } else {
        fetchContent(url);
    }
}

function contentForm(event) {
    event.preventDefault();
    var currentUrl = location.href;
    var $form = $(this);
    var url = $form.attr( "action" );
    if (url.trim().length === 0) {
        url = currentUrl;
    }
    openContent();
    var $content = $("#content");
    $content.addClass("loading");
    $content.empty();
    
    
    var posting = $.post(url, $form.serialize(), function(data) {
        if (typeof data.redirect !== 'undefined') {
            // redirect
            fetchContent(data.redirect, {active:active_nav});
        } else if (typeof data.content !== 'undefined') {
            $content.html(data.content);
            ajaxify($content);
        }
    }, 'json');
    posting.done(function( data ) {
        $content.removeClass("loading");
    });
}

function ajaxify(element) {
    if (typeof element !== 'undefined') {
        element.find("a.content-link").click(contentLink);
        element.find("form.content-form").submit(contentForm);
    } else {
        $("a.content-link").click(contentLink);
        $("form.content-form").submit(contentForm);
    }
}

function switchEditor() {
    $("#switch_editor").hide();
    $("#switch_view").show();
}

function switchView() {
    $("#switch_view").hide();
    $("#switch_editor").show();
}

function displayEditor() {
    switchEditor();
    document.getElementById('local-frame').contentWindow.displayEditor();
}

function displayView() {
    switchView();
    document.getElementById('local-frame').contentWindow.displayView();
}

window.onpopstate = function(event) {
    var state = event.state;
    if (state) {
        // set content
        if (typeof state.content !== 'undefined') {
            openContent();
            fetchContent(state.content);
        } else {
            closeContent();
        }
        // set active nav
        if (typeof state.active !== 'undefined') {
            setActive(state.active);
        }
    }
};

$(function() {
    // ajaxify links and forms
    ajaxify();
    // hide content if requested
    var $content = $("#content.hide-at-startup");
    if ($content.length > 0) {
        var height = $content[0].scrollHeight;
        $('#local-frame').show();
        $content.css('top',-height+"px");
        $content.hide();
    }
    // set current history record
    var data = {active:active_nav};
    if (content_url)
        data.content = content_url;
    window.history.replaceState(data, 'Declick', document.URL);
    $("#switch_editor").click(displayEditor);
    $("#switch_view").click(displayView);
    
    // close dropdown menu when iframe clicked
    $(window).blur(function() {
        var menu = $("#menu");
        if (menu.hasClass("open")) {
            menu.removeClass("open");
        }
    });
});


