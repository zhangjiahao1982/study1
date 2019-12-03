<?php
session_start();
require_once("lm/admin_zjh/global.php");
require_once("public/user_login.php");
require_once("public/specialstr_pinglun.php");
require_once("public/top.php");
if($_SESSION["wwwzongyuanxiaozhaoid"] != "" || $_COOKIE["wwwzongyuanxiaozhaoid"] != ""){
    $query = $mysqli->query("select * from `user_edu` where `u_id`='$u_id' LIMIT 1");
    if(mysqli_num_rows($query)!=0){
        while($rel = $query->fetch_assoc()){    
            $id = $rel['id']; 
            $e_xueli = $rel['e_xueli']; 
            $e_ruxue_date = $rel['e_ruxue_date']; 
            $e_biye_date = $rel['e_biye_date']; 
            $e_school_name = $rel['e_school_name']; 
            $e_school_yuanxi = $rel['e_school_yuanxi']; 
            $e_zhuanye = $rel['e_zhuanye']; 
            $e_cj_paiming = $rel['e_cj_paiming']; 
            $e_daoshi_name = $rel['e_daoshi_name']; 
            $e_eng_level = $rel['e_eng_level']; 
            $e_eng_cj = $rel['e_eng_cj'];
            $e_other_level = $rel['e_other_level'];
            $e_other_cj = $rel['e_other_cj'];
            $e_other_zhengshu = $rel['e_other_zhengshu'];
        }
    }
?>

<div class="page-content">
    <div class="content">
        <div class="title">教育背景</div>
        <div class="step clearfix">
            <ul>
                <li class=""><a href="u">1.个人信息<i class="icon icon-error"></i><i class="icon icon-right"></i></a></li>
                <li class="this"><a href="e">2.教育背景<i class="icon icon-error"></i><i class="icon icon-right"></i></a></li>
                <li class=""><a href="h">3.实践经历<i class="icon icon-error"></i><i class="icon icon-right"></i></a></li>
            </ul>
        </div>
        <div class="select-content">
            <div class="education-content" id="education-box">
                    <div class="education-section clearfix">
                    <div class="education-title clearfix mb35">
                        <div class="fl education_title">学历信息-<?php echo $u_name?><input type="hidden" name="uid" value="<?php echo $u_id?>"></div>
                        <!-- <div class="fr"><a class="add" href="javascript:"><i class="icon-add"></i>添加学历</a></div> -->
                    </div>
                    <div class="list-section clearfix">
                        <div class="title">学历*</div>
                        <div class="right">
                            <div class="select fl normal">
                                <span class="selected">请选择</span>
                                <ul class="scroll" id="xueli_list">
                                    <li li-id="大专">大专</li>
                                    <li li-id="本科">本科</li>
                                    <li li-id="硕士研究生">硕士研究生</li>
                                    <li li-id="博士研究生">博士研究生</li>
                                    <li li-id="高中">高中</li>
                                </ul>
                                <input type="hidden" name="xueli"  name1="input" name2="20" name3="学历" value="">
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="tips mt15">提示：请完整填写本科/专科及以上的每一段学历</div>
                    </div>
                    <div class="list-section clearfix ruxue">
                        <div class="title">入学日期*</div>
                        <div class="right">
                            <div class="select fl">
                                <span class="selected">请选择</span>
                                <ul class="scroll" id="ruxue_year_list">
                                    <li li-id="2027">2027</li>
                                    <li li-id="2026">2026</li>
                                    <li li-id="2025">2025</li>
                                    <li li-id="2024">2024</li>
                                    <li li-id="2023">2023</li>
                                    <li li-id="2022">2022</li>
                                    <li li-id="2021">2021</li>
                                    <li li-id="2020">2020</li>
                                    <li li-id="2019">2019</li>
                                    <li li-id="2018">2018</li>
                                    <li li-id="2017">2017</li>
                                    <li li-id="2016">2016</li>
                                    <li li-id="2015">2015</li>
                                    <li li-id="2014">2014</li>
                                    <li li-id="2013">2013</li>
                                    <li li-id="2012">2012</li>
                                    <li li-id="2011">2011</li>
                                    <li li-id="2010">2010</li>
                                </ul>
                                <input type="hidden" name="ruxue_year"  name1="input" name2="20" name3="入学年份" value="">
                            </div>
                            <span class="txt fl">年</span>
                            <div class="select fl">
                                <span class="selected">请选择</span>
                                <ul class="scroll" id="ruxue_month_list">
                                    <li li-id="01">01</li>
                                    <li li-id="02">02</li>
                                    <li li-id="03">03</li>
                                    <li li-id="04">04</li>
                                    <li li-id="05">05</li>
                                    <li li-id="06">06</li>
                                    <li li-id="07">07</li>
                                    <li li-id="08">08</li>
                                    <li li-id="09">09</li>
                                    <li li-id="10">10</li>
                                    <li li-id="11">11</li>
                                    <li li-id="12">12</li>
                                </ul>
                                <input type="hidden"  name="ruxue_month"  name1="input" name2="20" name3="入学月份" value="">
                            </div>
                            <span class="txt fl">月</span>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="list-section clearfix biye">
                        <div class="title">毕业日期*</div>
                        <div class="right">
                            <div class="select fl">
                                <span class="selected">请选择</span>
                                <ul class="scroll" id="biye_year_list">
                                    <li li-id="2027">2027</li>
                                    <li li-id="2026">2026</li>
                                    <li li-id="2025">2025</li>
                                    <li li-id="2024">2024</li>
                                    <li li-id="2023">2023</li>
                                    <li li-id="2022">2022</li>
                                    <li li-id="2021">2021</li>
                                    <li li-id="2020">2020</li>
                                    <li li-id="2019">2019</li>
                                    <li li-id="2018">2018</li>
                                    <li li-id="2017">2017</li>
                                    <li li-id="2016">2016</li>
                                    <li li-id="2015">2015</li>
                                    <li li-id="2014">2014</li>
                                    <li li-id="2013">2013</li>
                                    <li li-id="2012">2012</li>
                                    <li li-id="2011">2011</li>
                                    <li li-id="2010">2010</li>
                                </ul>
                                <input type="hidden" name="biye_year"  name1="input" name2="20" name3="毕业年份"  value="">
                            </div>
                            <span class="txt fl">年</span>
                            <div class="select fl">
                                <span class="selected">请选择</span>
                                <ul class="scroll" id="biye_month_list">
                                    <li li-id="01">01</li>
                                    <li li-id="02">02</li>
                                    <li li-id="03">03</li>
                                    <li li-id="04">04</li>
                                    <li li-id="05">05</li>
                                    <li li-id="06">06</li>
                                    <li li-id="07">07</li>
                                    <li li-id="08">08</li>
                                    <li li-id="09">09</li>
                                    <li li-id="10">10</li>
                                    <li li-id="11">11</li>
                                    <li li-id="12">12</li>
                                </ul>
                                <input type="hidden"  name="biye_month"  name1="input" name2="20" name3="毕业月份"  value="">
                            </div>
                            <span class="txt fl">月</span>
                            <div class="clear"></div>
                            <div class="tips mt15">提示：请填写国内获得毕业证和学位证时间；港澳台及海外院校填写获得学位证时间。</div>
                        </div>
                    </div>
                    <div class="list-section clearfix">
                        <div class="title">学校*</div>
                        <div class="right">
                            <div class="input"><input type="text" placeholder="输入学校全称"  name="school_name"  name1="input" name2="50" name3="学校名称" value="<?php echo $e_school_name?>" ></div>
                        </div>
                    </div>
                    <div class="list-section clearfix">
                        <div class="title">院系*</div>
                        <div class="right">
                            <div class="input"><input type="text" placeholder="请填写您的院系"  name="school_yuanxi"  name1="input" name2="50" name3="院系" value="<?php echo $e_school_yuanxi?>"></div>
                        </div>
                    </div>
                    <div class="list-section clearfix">
                        <div class="title">专业*</div>
                        <div class="right">
                            <div class="input"><input type="text" placeholder="请填写您的专业"  name="school_zhuanye"  name1="input" name2="50" name3="专业名称" value="<?php echo $e_zhuanye?>"></div>
                        </div>
                    </div>
                    <div class="list-section clearfix">
                        <div class="title">成绩排名*</div>
                        <div class="right">
                            <div class="select fl normal">
                                <span class="selected">请选择</span>
                                <ul class="scroll" id="cj_paiming_list">
                                    <li li-id="前5%">前5%</li>
                                    <li li-id="前10%">前10%</li>
                                    <li li-id="前20%">前20%</li>
                                    <li li-id="其他">其他</li>
                                </ul>
                                <input type="hidden"  name="cj_paiming"  name1="input" name2="20" name3="成绩排名" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="list-section clearfix">
                        <div class="title">导师*</div>
                        <div class="right">
                            <div class="input"><input type="text" placeholder="请填写您的导师"  name="daoshi_name"  name1="input" name2="50" name3="导师名称" value="<?php echo $e_daoshi_name?>"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="education-title clearfix mb35">
                <div class="fl">英语水平</div>
            </div>
            <div class="list-section clearfix english-section">
                <div class="title">英语等级</div>
                <div class="right">
                    <div class="select fl">
                        <span>请选择</span>
                        <ul class="scroll" id="eng_level_list">
                            <li li-id="CET-4">CET-4</li>
                            <li li-id="CET-6">CET-6</li>
                            <li li-id="TEM-4">TEM-4</li>
                            <li li-id="TEM-8">TEM-8</li>
                        </ul>
                        <input type="hidden" name="eng_level" value="0" id="eng_level_val">
                    </div>
                    <span class="fl txt">成绩</span>
                    <div class="input"><input type="text" name="eng_score"  value="<?php echo $e_eng_cj?>"></div>
                </div>
            </div>
            <div class="list-section clearfix english-section">
                <div class="title">其他英语等级</div>
                <div class="right">
                    <div class="select fl">
                        <span>请选择</span>
                        <ul class="scroll" id="other_level_list">
                            <li li-id="TOEFL">TOEFL</li>
                            <li li-id="TOEFL">GRE</li>
                            <li li-id="TOEFL">GMAT</li>
                            <li li-id="TOEFL">IELTS</li>
                        </ul>
                        <input type="hidden" name="other_language" value="0" id="other_language_val">
                    </div>
                    <span class="fl txt">成绩</span>
                    <div class="input"><input type="text" name="other_language_score"  value="<?php echo $e_other_cj?>"></div>
                </div>
            </div>
            <div class="list-section clearfix english-section">
                <div class="title">其他语言水平/证书</div>
                <div class="right">
                    <div class="textarea"><textarea name="other_language_card"><?php echo $e_other_zhengshu?></textarea></div>
                </div>
            </div>
            <div class="list-section clearfix">
                <div class="title"></div>
                <div class="right clearfix">
                    <input class="btn save blue fl" type="submit" name="submit" value="保存并下一步">
                    <a href="u" class="btn back white fl ml30">返回上一步</a>
                </div>
            </div>

        </div>
    </div>
    <!--学校选择弹窗-->
</div>
<?php
}else{
    echo "<div class='tip'>请先登录</div>";
}
?>
<div class="page-copy pt20 pb20">
    <a target="blank" href="http://www.greatsource.cn/">关于宗源</a>
    <p>版权所有©北京营销策划股份有限公司京ICP备12014581号-1</p>
</div>
<script src='http://libs.baidu.com/jquery/2.0.0/jquery.min.js'></script>
<script src="public/jquery_002.js" type="text/javascript"></script>
<script src="public/jquery.js" type="text/javascript"></script>
<script src="public/index.js" type="text/javascript"></script>
<script src="public/page.js" type="text/javascript"></script>
<script type="text/javascript" src="public/stats" charset="UTF-8"></script><script src="public/jquery_003.js" type="text/javascript"></script>
<script src="public/layer.js" type="text/javascript"></script>
<script src="public/layer/layer.js" type="text/javascript" charset="utf-8"></script>
<script src="public/zhengze.js"></script>
<script src="public/submit2.js"></script>
<script type="text/javascript">
_num = $(".education-section").length;
$(".page-content .select-content .education-title a.add").on("click",function(){
    $(".education-section:last").after('<div class="education-section clearfix">'+$(".education-section:last").html()+'</div>');
    $(".education-section:last input").val("");
    $(".education-section:last .selected").html("请选择");
    _num ++;
    $(".education_title:last").html("学历信息-"+_num);
    $(".education-section:last .add").after('<button class="delete">删除学历</button>').remove();

    //新生成的html，重新绑定
    select_init();
    return false;
})

//select赋值。。。
function select_init() {
    $(".scroll li").unbind().click(function () {
        var T = $(this);
        T.parent().next().val(T.attr("li-id"));
    });
}

function school_nation_change(nation_id,nation_txt) {
    $.post("school_catch.php",{
        nation:nation_id,
        province:0
    },function (databack) {
        if(databack.status == 1){
            current_nation = nation_id

            $(".school_nation_select").prev("span").html(nation_txt);
            $(".school_province_select").prev().removeClass("selected").html("请选择")
            $(".school_province_select").html("");
            $("#school-body").html("");

            $.each(databack.province,function (i,v) {
                $(".school_province_select").append('<li onclick="school_province_change(\''+i+'\',\''+v+'\');">'+v+'</li>')
            });
        }
    },"json")
}

var current_nation = 1

function school_province_change(province_id,province_txt) {
    $.post("school_catch.php",{
        nation:current_nation,
        province:province_id
    },function (databack) {
        if(databack.status == 1){
            $("#school-body").html("");
            $.each(databack.school,function (i,v) {
                var _html = '<div class="checkbox">'+
                    '<input id="'+ v.id +'" type="radio" name="city" value="'+ v.schoolName +'">'+
                    '<label for="'+ v.id +'">'+ v.schoolName +'</label>'+
                    '</div>';
                $("#school-body").append(_html);
            });

            $(".school-box .list-section .checkbox").on("click",function(){
                var _val = $(this).find("input").val();
                $(".school-txt").eq(current_school_tab).val(_val)
                $(".school-bg,.school-model").fadeOut(150);
            })
        }
    },"json")
}

select_init();

$(document).on("click",".education-section .delete",function(){
    $(this).parents(".education-section").remove();
    _num --;
})

var current_school_tab = 0;

$(document).on("click",".input-school .icon-school",function(){
    var T = $(this);
    current_school_tab = ($(".input-school .icon-school").index(T))
    $(".school-bg,.school-model").fadeIn(150);
})

$(".school-model .icon-close").on("click",function(){
    current_school_tab = 0;
    $(".school-bg,.school-model").fadeOut(150);
})

$("#school_name_search_btn").click(function () {
    $("#school-body").children().show();

    if($("#school_name_search").val()!= ""){
        $("#school-body").children().each(function (i) {
            if($(this).children("label").html().indexOf($("#school_name_search").val())==-1){
                $(this).hide();
            }
        })
    }
});
$(function(){
    var e_xueli = '<?php echo $e_xueli;?>';
    if(e_xueli!=''){
        $("#xueli_list li").each(function(){
            var text = $(this).text();
            if(text==e_xueli){
                $(this).parents("ul").siblings("span").text(e_xueli);
                $("input[name='xueli']").val(e_xueli);
            }
        });
    }
    var e_cj_paiming = '<?php echo $e_cj_paiming;?>';
    if(e_cj_paiming!=''){
        $("#cj_paiming_list li").each(function(){
            var text = $(this).text();
            if(text==e_cj_paiming){
                $(this).parents("ul").siblings("span").text(e_cj_paiming);
                $("input[name='cj_paiming']").val(e_cj_paiming);
            }
        });
    }
    var e_other_level = '<?php echo $e_other_level;?>';
    if(e_other_level!=''){
        $("#other_level_list li").each(function(){
            var text = $(this).text();
            if(text==e_other_level){
                $(this).parents("ul").siblings("span").text(e_other_level);
                $("input[name='other_language']").val(e_other_level);
            }
        });
    }
    var e_eng_level = '<?php echo $e_eng_level;?>';
    if(e_eng_level!=''){
        $("#eng_level_list li").each(function(){
            var text = $(this).text();
            if(text==e_eng_level){
                $(this).parents("ul").siblings("span").text(e_eng_level);
                $("input[name='eng_level']").val(e_eng_level);
            }
        });
    }
    <?php 
    $e_ruxue_date = explode('-', $e_ruxue_date);
    $e_biye_date = explode('-', $e_biye_date);
    ?>
    var ruxue_year = '<?php echo $e_ruxue_date[0];?>';
    if(ruxue_year!=''){
        $("#ruxue_year_list li").each(function(){
            var text = $(this).text();
            if(text==ruxue_year){
                $(this).parents("ul").siblings("span").text(ruxue_year);
                $("input[name='ruxue_year']").val(ruxue_year);
            }
        });
    }
    var ruxue_month = '<?php echo $e_ruxue_date[1];?>';
    if(ruxue_month!=''){
        $("#ruxue_month_list li").each(function(){
            var text = $(this).text();
            if(text==ruxue_month){
                $(this).parents("ul").siblings("span").text(ruxue_month);
                $("input[name='ruxue_month']").val(ruxue_month);
            }
        });
    }
    var biye_year = '<?php echo $e_biye_date[0];?>';
    if(biye_year!=''){
        $("#biye_year_list li").each(function(){
            var text = $(this).text();
            if(text==biye_year){
                $(this).parents("ul").siblings("span").text(biye_year);
                $("input[name='biye_year']").val(biye_year);
            }
        });
    }
    var biye_month = '<?php echo $e_biye_date[1];?>';
    if(biye_month!=''){
        $("#biye_month_list li").each(function(){
            var text = $(this).text();
            if(text==ruxue_month){
                $(this).parents("ul").siblings("span").text(biye_month);
                $("input[name='biye_month']").val(biye_month);
            }
        });
    }
});
</script>
</body></html>