function Dials() {
	Dials.prototype = {
		Init: function (o) {
			var ANums = o.id.slice(1).split("_");
			/**
			* o.P = {} 	P对象用于保存参数
			* o.P.D		disable 确保转盘开始后不可以再次点击
			* o.P.T		timer 转盘定时器
			* o.P.TS 	turns 旋转圈数
			* o.P.P 	prices 奖品总数
			* o.P.S		speed 旋转速度
			* o.P.SD  	stop deg 指针停止位置
			* o.P.ND 	now deg指针当前角度
			**/
			o.P = {};
			o.P.D = true;
			o.P.T = null;
			o.P.TS = ANums[0] || 11;
			o.P.P = ANums[1] || 12;
			o.P.SD = 0;
			o.P.ND = 0;
		},
		/**
		* @function	点击开始功能
		* @parm o	obj对象
		* @parm n	设置获取奖品位置
		* @parm fnEnd 回调函数
		**/
		Start: function (o, n, fnEnd) {
			if (o.P.D) {
				o.P.D = false;
				var n = n - 1;
			
				o.P.SD = n * (360 / o.P.P);
				o.P.T = setInterval(function () {
					Dials.prototype.Move(o, fnEnd);
				}, 30);
			}		
		},
		Move: function (o,fnEnd) {
			var bStop = true;
			var Speed = (360 * o.P.TS + o.P.SD  - o.P.ND) / 80;
			Speed = (Speed > 0) ? Math.ceil(Speed) : Math.floor(Speed); 
			o.P.ND += Speed;
			
			var Rotate = "rotate(" + o.P.ND + "deg)";
			cssSandpaper.setTransform(o, Rotate);
			
			if (360 * o.P.TS + o.P.SD  == o.P.ND) {
				bStop = false;
			}
			if(!bStop){
				clearInterval(o.P.T);
				o.P.ND = o.P.SD;
				o.P.D = true;
				
				if(fnEnd){
					fnEnd();
				}
			}
		}
	};
	
	C.Batch();
}
