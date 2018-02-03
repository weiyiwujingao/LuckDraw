function FengX(Cont){
	var Con = C.G(Cont).value;
	//var New = Con+"\n"+FxId;
	var title = encodeURIComponent(Con);
	/*分享到功能*/
	var commentId = "";
	var locationurl = window.location.href;
	var source = encodeURIComponent("www.cnfol.com");
	var sourceUrl = encodeURIComponent("http://www.cnfol.com/");
	var enUrl = encodeURIComponent(locationurl);
	var test=5566;
	var site = "中金在线";
	function share_qqk(){
	var p = {
		url:locationurl,
		showcount:'1',/*是否显示分享总数,显示：'1'，不显示：'0' */
		desc:'',/*默认分享理由(可选)*/
		summary:'',/*分享摘要(可选)*/
		title:document.title,/*分享标题(可选)*/
		site:site,/*分享来源 如：腾讯网(可选)*/
		pics:'', /*分享图片的路径(可选)*/
		style:'203',
		width:98,
		height:22
	};
	var s = [];
	for(var i in p){
		s.push(i + '=' + encodeURIComponent(p[i]||''));
	}
	var shareUrl = 'http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?'+s.join('&');
	   alert(shareUrl);
	window.open(shareUrl);
	}
	//share_sina();
	var shareUrl = 'http://service.t.sina.com.cn/share/share.php?ralateUid=1649470535&appkey=383706083&url='+locationurl+'&title='+title+'&source='+source+'&sourceUrl'+sourceUrl+'content=gb2312&pic=';
		window.open(shareUrl);
}
