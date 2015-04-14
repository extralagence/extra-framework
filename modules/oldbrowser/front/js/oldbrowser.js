/*
 * Browser Detection
 * � 2010 DevSlide Labs
 *
 * Visit us at: www.devslide.com/labs
 */

var BD;
var BrowserDetection = {
	init: function(){
		BD = this;

		// Check if this is old browser
		var oldBrowser = false,
			toCheck;
		for (var i = 0; i < extra_oldbrowser_restrictions.length; i++) {

			toCheck = true;
			if ((typeof extra_oldbrowser_restrictions[i][0]) === 'string') {
				if ($.browser[extra_oldbrowser_restrictions[i][0]] != true) {
					toCheck = false;
				}
			} else {
				for(var j = 0; j < extra_oldbrowser_restrictions[i][0].length; j++) {
					if ($.browser[extra_oldbrowser_restrictions[i][0][j]] != true) {
						toCheck = false;
						break;
					}
				}
			}

			if (toCheck && (extra_oldbrowser_restrictions[i][1] === 'any' || $.browser.versionNumber <= parseInt(extra_oldbrowser_restrictions[i][1]))) {
				oldBrowser = true;
				break;
			}
		}

		if(oldBrowser){
			BD.show();
		}
	},

	show: function() {
		if(this.readCookie('bdnotice') == 1){
			return;
		}
		$.fancybox($("#oldBrowser"), {
			padding: 0,
			beforeClose: BD.remindMe
		});
	},

	remindMe: function(never){
		BD.writeCookie('bdnotice', 1, never == true ? 365 : 1);
	},

	writeCookie: function(name, value, days){
		var expiration = "";
		if(parseInt(days) > 0){
			var date = new Date();
			date.setTime(date.getTime() + parseInt(days) * 24 * 60 * 60 * 1000);
			expiration = '; expires=' + date.toGMTString();
		}

		document.cookie = name + '=' + value + expiration + '; path=/';
	},

	readCookie: function(name){
		if(!document.cookie){ return ''; }

		var searchName = name + '=';
		var data = document.cookie.split(';');

		for(var i = 0; i < data.length; i++){
			while(data[i].charAt(0) == ' '){
				data[i] = data[i].substring(1, data[i].length);
			}

			if(data[i].indexOf(searchName) == 0){
				return data[i].substring(searchName.length, data[i].length);
			}
		}

		return '';
	}
};

$(document).ready(function(){
	BrowserDetection.init();
	console.log($.browser.versionNumber);
});
