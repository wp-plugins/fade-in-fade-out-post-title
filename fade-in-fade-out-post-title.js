/**
 *     Fade in fade out post title
 *     Copyright (C) 2011 - 2014 www.gopiplus.com
 * 
 *	   http://www.gopiplus.com/work/2011/07/31/fade-in-fade-out-post-title-wordpress-plugin/
 *
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

var fifopost_FadeInterval;

window.onload = fifopost_FadeRotate

var fifopost_Links;
var fifopost_Titles;
var fifopost_Cursor = 0;
var fifopost_Max;


function fifopost_FadeRotate() 
{
  fifopost_FadeInterval = setInterval(fifopost_Ontimer, 10);
  fifopost_Links = new Array();
  fifopost_Titles = new Array();
  fifopost_SetFadeLinks();
  fifopost_Max = fifopost_Links.length-1;
  fifopost_SetFadeLink();
}

function fifopost_SetFadeLink() {
  var ilink = document.getElementById("fifopost_Link");
  ilink.innerHTML = fifopost_Titles[fifopost_Cursor];
  ilink.href = fifopost_Links[fifopost_Cursor];
}

function fifopost_Ontimer() {
  if (fifopost_bFadeOutt) {
    fifopost_Fade+=fifopost_FadeStep;
    if (fifopost_Fade>fifopost_FadeOut) {
      fifopost_Cursor++;
      if (fifopost_Cursor>fifopost_Max)
        fifopost_Cursor=0;
      fifopost_SetFadeLink();
      fifopost_bFadeOutt = false;
    }
  } else {
    fifopost_Fade-=fifopost_FadeStep;
    if (fifopost_Fade<fifopost_FadeIn) {
      clearInterval(fifopost_FadeInterval);
      setTimeout(Faderesume, fifopost_FadeWait);
      fifopost_bFadeOutt=true;
    }
  }
  var ilink = document.getElementById("fifopost_Link");
  if ((fifopost_Fade<fifopost_FadeOut)&&(fifopost_Fade>fifopost_FadeIn))
    ilink.style.color = "#" + ToHex(fifopost_Fade);
}

function Faderesume() {
  fifopost_FadeInterval = setInterval(fifopost_Ontimer, 10);
}

function ToHex(strValue) {
  try {
    var result= (parseInt(strValue).toString(16));

    while (result.length !=2)
            result= ("0" +result);
    result = result + result + result;
	//alert(result);
    return result.toUpperCase();
  }
  catch(e)
  {
  }
}