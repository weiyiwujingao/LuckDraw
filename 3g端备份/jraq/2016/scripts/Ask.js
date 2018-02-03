//初始化事件绑定
function int() {
    $("#status").html("");
    $('.div_topic').removeClass('odd');
    $('.div_topic:even').addClass('odd');
    $('.div_topic p').click(function() {
        if ($(this).find('input[type="radio"]').val() != undefined) {
            $(this).find('input[type="radio"]').attr('checked', 'checked');
            var name = $(this).find('input[type="radio"]').attr('name'); //获得名称
            $('input[type="radio"][name="' + name + '"]').parents("p").removeClass('selected');
            $('input[type="radio"][name="' + name + '"]:checked').parents("p").addClass('selected');
        }
    });
    $("#check_answer").click(function() {
			$("#status").html("");
			$("h4").css("color", "#22536A");
			var answer_this_tip, set_answer, _temp_tip;
			_temp_tip = "yes";
			var tall = 0;
			$(".div_topic").each(function(i) {
				if ($(this).find('input[type="radio"]:checked').val() == undefined) {
					_temp_tip = "no";
					$(this).find("h4").css("color", "green");
				}
				tall++;
			});
			if (_temp_tip == "no") {
				//alert("还有题目没完成！");
				Dialog('WnBg','WnBx');
				$('#AskSucc').hide();
				$('#AskErr').show();
				$('#AskErrTxt').html('亲，还有题目没完成哦，再接再厉。');
				return;
			}
			var err = 0;
			$(".div_topic").each(function(i) {
				answer_this_tip = $(this).find(".answer_this_tip").html();
				set_answer = $(this).find('input[type="radio"]:checked').val();
				//$(this).find('input[type="radio"]').val() != undefined
				if (answer_this_tip != set_answer) {
					$(this).find("h4").css("color", "#ff3c00");
					err++;
				}
			});
			$("h4 span").show();
			if(err==0){
				//alert("恭喜全部答对，点击抽奖！");
				Dialog('WnBg','WnBx');
				$('#AskSucc').show();
				$('#AskErr').hide();
			}else{
				//alert("您有答错题，请返回继续答题，赢取抽奖机会！");
				Dialog('WnBg','WnBx');
				$('#AskSucc').hide();
				$('#AskErr').show();
				$('#AskErrTxt').html('您有题目答错了，赶快参考正确答案“补救”下。');
			}
    });
}
function set_str_len(str, len) {
    str = str + "";
    var temp = "";
    for (i = 0; i < (len - str.length); i++) {
        temp += "0";
    }
    return temp + str;
}
$(document).ready(function() {
	$(".box1").click(function(){
		$("#topic_all").show();
		$(".box1").hide();
	})
	var topic_num = $("#topic_num").val();
	topic_num = $("#topic_num").val();
    int();
});
//有奖竞猜补充
function StartAsk(ScrH){
	$('#AskBfr').hide();
	$('#AskBx').show();
	document.body.scrollTop = ScrH;
	document.documentElement.scrollTop = ScrH;
}
function AskTWm(){
	var patrn = /(^0{0,1}1[3|4|5|6|7|8|9][0-9]{9}$)/;
	var phone = $("#AskPh").val();
	if (phone==""){
		alert('手机号码不能为空');
	}else if (!patrn.exec(phone)) {
		alert('手机号码格式不正确');
	}else{
		document.getElementById('WnBx').submit();
		//window.location.href = Urls;//跳转到抽奖页面
	}
}
//初始化取消radio选中
function LoadIpt(){
	var Abi = document.getElementById('AskBx').getElementsByTagName('input');
	for(var i=0;i<Abi.length;i++){
		document.getElementById('AskBx').getElementsByTagName('input').item(i).checked = false;
	}
}
