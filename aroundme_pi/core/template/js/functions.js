// -----------------------------------------------------------------------
// This file is part of AROUNDMe
// 
// Copyright (C) 2003-2008 Barnraiser
// http://www.barnraiser.org/
// info@barnraiser.org
// 
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with this program; see the file COPYING.txt.  If not, see
// <http://www.gnu.org/licenses/>
// -----------------------------------------------------------------------

//puts browser window at top (out of frames) - stops bug with registering from inside hotmail frame.
if (self != top){
   if (document.images) top.location.replace(document.location.href);
   else top.location.href = document.location.href;
}

/**
 * Some browser detection
 */
var clientPC  = navigator.userAgent.toLowerCase(); // Get client info
var is_gecko  = ((clientPC.indexOf('gecko')!=-1) && (clientPC.indexOf('spoofer')==-1) &&
                (clientPC.indexOf('khtml') == -1) && (clientPC.indexOf('netscape/7.0')==-1));
var is_safari = ((clientPC.indexOf('AppleWebKit')!=-1) && (clientPC.indexOf('spoofer')==-1));
var is_khtml  = (navigator.vendor == 'KDE' || ( document.childNodes && !document.all && !navigator.taintEnabled ));
if (clientPC.indexOf('opera')!=-1) {
    var is_opera = true;
    var is_opera_preseven = (window.opera && !document.childNodes);
    var is_opera_seven = (window.opera && document.childNodes);
}



var myWindow;

function launchPopupWindow(page, winWidth, winHeight) {
	if(myWindow && !myWindow.closed) {
			myWindow.close();
	}

	if (!winWidth) {
		var winWidth = 550;
	}

	if (!winHeight) {
		var winHeight = 350;
	}
	
	customise = "scrollbars=yes,width="+winWidth+",height="+winHeight+",status=0";
	
	customise = customise + ',left='+20;
	customise = customise + ',top='+100;
	
	myWindow = window.open(page,null,customise);
	myWindow.focus();
}

function objShowHide(id) {

	if (document.getElementById) {
		if (document.getElementById(id).style.display == 'block') {
			document.getElementById(id).style.display = 'none';
		}
		else {
			document.getElementById(id).style.display = 'block';
		}
	}
	else {
		if (document.layers) {
			if (document.id.visibility == 'block') {
				document.id.visibility = 'none';
			}
			else {
				document.id.visibility = 'block';
			}
		}
		else { // IE 4
			if (document.all.id.style.display == 'block') {
				document.all.id.style.display = 'none';
			}
			else {
				document.all.id.style.display = 'block';
			}
		}
	}
}

function checkImage(_image) {
	_image.onerror = function() {
		_image.style.display = 'none';
	}
}

function checkImages() { 
	_images = document.getElementsByTagName('img'); 
	for(i=0; i < _images.length; i++) { 
		checkImage(_images[i]); 
	} 
}