window.task = {
	getViews: function(callback) {
		var views = {
			task: {}
		};
		callback(views);
	},
	load: function(views, callback) {
		callback();
	},
	unload: function(callback) {
		callback();
	},
	getAnswer: function(callback) {
		callback("");
	},
	reloadAnswer: function(strAnswer, callback)	{
		callback();
	},
	getMetaData: function(callback, errorCallback)	{
		var link = document.location.href;
		var metaData = {
			id : link,
			language : "fr",
			version : 1.0,
			title : "title",
			authors : ["Colombbus - France-IOI"],
			license : "license",
			minWidth : "auto",
			fullFeedback : true,
			autoHeight : true
		};
		callback(metaData);

		callback();
	},
	getHeight: function(callback)	{
		callback($("html").outerHeight(true));
	},
	showViews: function(views, callback) {
		callback();
	},
	updateToken: function(token, callback) {
		callback();
	},
	getState: function(callback) {
		callback("");
	},
	reloadState: function(state, callback) {
		callback();
	}
};

/*$(window).resize(function() {
	platform.updateHeight($("html").outerHeight(true));
});*/	
