// I'm with Barkley - picture swap javascript code
//<![CDATA[
	if (document.all||document.getElementById) {
		document.write('<style id="tmpStyle" type="text/css">#pic {-moz-opacity:0.00;filter:alpha(opacity=0);opacity:0;-khtml-opacity:0;}<\/style>')
		var objG;
		var _startDegree = 40;
		var degree = _startDegree;
		var fadeAssist = 0;
		var imgArray = [
			"/images/Before_2.jpg",
			"/images/After_2.jpg",
			"/images/Before_3.jpg",
			"/images/After_3.jpg",
		];
		var currImgIdx = 0;
	}
	
	function fadeInPic(obj) {
		objG=obj
		if (!document.getElementById&&!document.all) return;
			var tS=document.all? document.all['tmpStyle'] : document.getElementById('tmpStyle')
			if (degree<100) {
				degree+=5
				if (objG.filters&&objG.filters[0]&&fadeAssist)
					fadeAssist(objG, degree)
				else if (typeof objG.style.MozOpacity=='string')
					objG.style.MozOpacity=degree/101
				else if (typeof objG.style.KhtmlOpacity=='string')
					objG.style.KhtmlOpacity=degree/100
				else if (typeof objG.style.opacity=='string'&&!objG.filters)
					objG.style.opacity=degree/101
				else
 					tS.disabled=true
				setTimeout("fadeInPic(objG)", 50);
			}
		else {
			tS.disabled=true
			setTimeout("fadeOutPic(objG)", 2000);
		}
		//tS.disabled=true
	}
	
	function fadeOutPic(obj) {
		objG=obj
		if (!document.getElementById&&!document.all) return;
			var tS=document.all? document.all['tmpStyle'] : document.getElementById('tmpStyle')
		if (degree>_startDegree) {
			degree-=5
			if (objG.filters&&objG.filters[0]&&fadeAssist)
				fadeAssist(objG, degree)
			else if (typeof objG.style.MozOpacity=='string')
				objG.style.MozOpacity=degree/101
			else if (typeof objG.style.KhtmlOpacity=='string')
				objG.style.KhtmlOpacity=degree/100
			else if (typeof objG.style.opacity=='string'&&!objG.filters)
				objG.style.opacity=degree/101
			else
				tS.disabled=true
			setTimeout("fadeOutPic(objG)", 50);
		}
		else {
			tS.disabled=true
			changeImage('pic');
		}
		//tS.disabled=true
	}
	
	function changeImage(id) {
		setTimeout("changeImage(id)", 3000);
		currImgIdx++;
		if(currImgIdx >= imgArray.length) currImgIdx = 0;
		degree = _startDegree;
		fadeAssist = 0;
		var picImg = document.getElementById(id);
		picImg.src = imgArray[currImgIdx];
		fadeInPic(id);
	}
	
	function loadFirst(id) {
		fadeInPic(id);
	}
	//]]>