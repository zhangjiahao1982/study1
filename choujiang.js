$(function() {

	var s_w = $(window).width();
	var s_h = $(window).height();

	var max_num;
	var min_num = 0;
	var start_chou;
	var start_if = 0;
	var zj_num = 0;
	var clicknum = 0;
	var person = 1;
	zj_type(); //开始就调用中奖类型
	zj_person(); //同时中奖人数
	user_mess(); //初始用户数据
	//=====中间切换的大图===============
	//=======随机选择人============

	//======随机选========
	function rand_xuan() { //还存在抽奖重复问题-------------
		var cho_num1 = parseInt(Math.random() * (max_num - min_num + 1) + min_num);
		//var cho_num1 = 11;
		var img_src1 = $(".nhc_1 li[name = '" + cho_num1 + "'] img").attr("src");
		var img_id1 = $(".nhc_1 li[name = '" + cho_num1 + "'] img").attr("name");
		$("#img_qie1>div").css({
			"background": "url('" + img_src1 + "')",
			"background-size": "100%"
		}).attr("name", img_id1);
		if (person > 1) {
			var cho_num2 = parseInt(Math.random() * (max_num - min_num + 1) + min_num);
			//var cho_num2 = 11;
			if (cho_num2 == cho_num1) {
				var cho_num2 = parseInt(Math.random() * (max_num - min_num + 1) + min_num);
                if (cho_num2 == cho_num1) {
                    var cho_num2 = parseInt(Math.random() * (max_num - min_num + 1) + min_num);
                    if (cho_num2 == cho_num1) {
                        var cho_num2 = parseInt(Math.random() * (max_num - min_num + 1) + min_num);
                    }
                }
			}
			var img_src2 = $(".nhc_1 li[name = '" + cho_num2 + "'] img").attr("src");
			var img_id2 = $(".nhc_1 li[name = '" + cho_num2 + "'] img").attr("name");
			$("#img_qie2>div").css({
				"background": "url('" + img_src2 + "')",
				"background-size": "100%"
			}).attr("name", img_id2);
		}
		//return false;
		//console.log(cho_num1,cho_num2,max_num,clicknum);

	}
	//=====制定选==========
	function zj_xuan(zj_num) {
		cho_num = parseInt(zj_num);

		var img_src = $(".nhc_1 li img[name = '" + cho_num + "']").attr("src");
		var img_idd = $(".nhc_1 li img[name = '" + cho_num + "']").attr("name");

		$(".nhc_1 li img").css("border", "2px solid #fff");
		$(".nhc_1 li img[name = '" + cho_num + "']").css({
			"border": "10px solid #fff"
		});
		//alert(img_src);
		$("#img_qie1>div").css({
			"background": "url('" + img_src + "')",
			"background-size": "100%"
		}).attr("name", img_idd);
	}

	//=开始抽奖==========
	$("#start_chou").on("click", function() {
		if (start_if == 0) {
			//判断是否还有次数；
			var count = $("#zj_show").find("p").length;
			zj_clicknum();
			if (person > 1) {
				clicknum = clicknum * 2;
			}
			if (count >= clicknum) {
				alert("本轮奖项已抽完，请进行下一轮抽奖");
			} else {
				$("#start_chou").html("点击停止");
				$('#img_qie1').show();
				if (person > 1) {
					$('#img_qie2').show();
					$('#img_qie1').css('left', '14%');
				} else {
					$('#img_qie2').hide();
					$('#img_qie1').css('left', 'calc(50% - 19%)');
				}
				start_chou = setInterval(rand_xuan, 100);
				start_if = 1;
			}
		} else {
			$(this).html("开始抽奖");
			//zj();//调用一下中奖id
			clearInterval(start_chou);
			if (person > 1) {
				$('#img_qie2').show();
				$('#img_qie1').css('left', '14%');
				var img_idd1 = $("#img_qie1>div").attr("name") //选中的用户id
				var img_idd2 = $("#img_qie2>div").attr("name") //选中的用户id
				var zj_type = $("#chou_type").val();
				zj_ids(img_idd1, img_idd2, zj_type); //判断是否有固定中奖人__并作记录
			} else {
				$('#img_qie2').hide();
				$('#img_qie1').css('left', 'calc(50% - 19%)');
				var img_idd = $("#img_qie1>div").attr("name") //选中的用户id
				var zj_type = $("#chou_type").val();
				zj_id(img_idd, zj_type); //判断是否有固定中奖人__并作记录
				
			}
			//jilu(img_idd,zj_type);
			//这里写入记录；
			start_if = 0;

		}
	})
	//记录多人中奖
	function zj_ids(id1, id2, t) {
		$.ajax({
			type: 'POST',
			url: "include/nianhui/zj_num.php",
			data: "type=jiluduo&zj_id1=" + id1 + "&zj_id2=" + id2 + "&chou_type=" + t,
			cache: false,
			async: true,
			dataType: 'json',
			success: function(data) {
				if (data == 1) {
					var zj_name1 = $(".nhc_1 li img[name='" + id1 + "']").attr("name1");
					var zj_name2 = $(".nhc_1 li img[name='" + id2 + "']").attr("name1");
					var zj_img1 = $(".nhc_1 li img[name='" + id1 + "']").attr("src");
					var zj_img2 = $(".nhc_1 li img[name='" + id2 + "']").attr("src");
					$("#zj_show").prepend("<p><img src='" + zj_img1 + "'><span>" + zj_name1 + "</span></p><p><img src='" +
						zj_img2 + "'><span>" + zj_name2 + "</span></p>");
					$(".nhc_1 ul").find("li[name1=" + id1 + "]").remove();
					$(".nhc_1 ul").find("li[name1=" + id2 + "]").remove();
					user_mess();
				}
			},
			error: function(data) {
				console.log(data);
			}
		});
	}
	//===固定中奖人id=========
	function zj_id(id, t) {
		$.ajax({
			type: 'POST',
			url: "include/nianhui/zj_num.php",
			data: "type=guding",
			cache: false,
			async: true,
			dataType: 'json',
			success: function(data) {
				if (data != 0) {
					zj_xuan(data); //指定
					jilu(data, t);

				} else {
					jilu(id, t);
					console.log("随机");

				}
			},
			error: function(data) {
				console.log(data);
			}
		});
	}


	//====中奖人记录==================
	function jilu(zj_jilu, chou_type) {
		$.ajax({
			type: 'POST',
			url: "include/nianhui/zj_num.php",
			data: "type=jilu&zj_jilu=" + zj_jilu + "&chou_type=" + chou_type,
			cache: false,
			async: true,
			dataType: 'json',
			success: function(data) {
				if (data == 1) {
					var zj_name = $(".nhc_1 li img[name='" + zj_jilu + "']").attr("name1");
					var zj_img = $(".nhc_1 li img[name='" + zj_jilu + "']").attr("src");
					$("#zj_show").append("<p><img src='" + zj_img + "'> <span>" + zj_name + "</span></p>");
					$(".nhc_1 ul").find("li[name1=" + zj_jilu + "]").remove();
					user_mess();
				}

			},
			error: function(data) {
				console.log(data);
			}
		});
	}
	//===========中将类型================
	function zj_type() {
		$.ajax({
			type: 'POST',
			url: "include/nianhui/zj_num.php",
			data: "type=zj_type",
			cache: false,
			async: false,
			dataType: 'html',
			success: function(data) {
				$("#chou_type").val(data);
			},
			error: function(data) {
				alert("123");
			}
		});
	}
	//======初始用户头像及名字调取================
	function user_mess() {
		$.ajax({
			type: 'POST',
			url: "include/nianhui/zj_num.php",
			data: "type=user_mess",
			cache: false,
			async: false,
			dataType: 'json',
			success: function(data) {
				$(".nhc_1 ul").empty();
				for (var i in data) {
					$(".nhc_1 ul").append("<li name='" + i + "' name1='" + data[i].id + "'>" +
						"<img src=\"" + data[i].headimgurl + "\" name=\"" + data[i].id + "\" name1 =\"" + data[i].nickname + "\">" +
						"</li>");
				} //循环渲染页面

				max_num = i; //给最大数赋值;

				//$("#chou_type").val(data);
			},
			error: function(data) {
				// alert("123");
			}
		});
	}
	//====中奖人次数======
	function zj_clicknum() {
		$.ajax({
			type: 'POST',
			url: "include/nianhui/zj_num.php",
			data: "type=clicknum",
			cache: false,
			async: false,
			dataType: 'json',
			success: function(data) {
				clicknum = data;
			},
			error: function(data) {
				// alert("123");
			}
		});
	}
	//=====同时中奖人数=====
	function zj_person() {
		$.ajax({
			type: 'POST',
			url: "include/nianhui/zj_num.php",
			data: "type=person",
			cache: false,
			async: false,
			dataType: 'json',
			success: function(data) {
				person = data;
				var html = "";
				for (var i = 1; i <= data; i++) {
					html += "<div id='img_qie" + i + "'><div></div></div>";
				}
				$(".bigimg").append(html);
			},
			error: function(data) {
				// alert("123");
			}
		});
	}

})
// JavaScript Document
