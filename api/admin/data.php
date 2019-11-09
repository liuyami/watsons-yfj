<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <title>屈臣氏后台管理</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <?php
    require '../bootstrap.php';
    $openid  =  isset($_SESSION['admin']) ? trim($_SESSION['admin']) : "";
    if (empty($openid)){
        $url="http://watsons.yscase.com/api/admin/index.php";
        header('location:'. $url);
        exit;
    }
    ?>

    <script src="js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="js/dateRange.js"></script>
    <link rel="stylesheet" type="text/css" href="css/dateRange.css" />
    <style>
        *{ margin: 0; padding: 0;}
        .block{ display: block;}
        .biao_con{ width: 1100px; margin: 50px auto 0; color: #353535; font-size: 14px;}
        .biao_con .v1{ border-bottom:#e0e0e0 2px solid; padding-bottom: 5px; font-weight: bold; font-size: 16px;}
        .biao_con .v1 .span_1{ border-bottom: #f00 2px solid;  padding-bottom: 5px; position: relative; top: 2px;}
        .biao_con .tr1{ background: #f4f5f9; }
        .biao_con .tab1{ width: 100%; margin-top: 30px;border-collapse:collapse;border-spacing:0; text-align: center;}
        .biao_con .tab1 td,.biao_con .tab1 th{ border:1px solid #e7e7eb; padding: 10px;}
        .biao_con .dy span{ display: block;text-overflow: ellipsis; overflow: hidden; white-space: nowrap; width: 200px;}
        .biao_con .tab1 td a{ }
        .fen_ye{ text-align: right; margin-top: 20px; margin-bottom: 20px;}
        .fen_ye b{ font-weight: normal;}
        .fen_ye a,.fen_ye span{ margin: 0 10px; color: #098ff9;}
        .fen_ye span.cur{ color: #f00;}

        .biao_con .v2{ margin: 20px 0; overflow: hidden;}
        .biao_con .in_put{ position: relative; line-height: 30px; height: 30px;vertical-align:middle; width: 150px; margin-left:40px; float: left; width: 150px;}
        .biao_con .in_put input{ width: 100px; height: 22px; border:none;}
        .biao_con .in_put a{ position: absolute;  right: 0; top: 3px;}
        .biao_con .in_select{ height: 30px; line-height: 30px; float: left; margin-left: 40px; width: 130px; }

        .biao_con .in_select,.biao_con .in_put{ border:1px solid #e7e7eb; padding: 0 10px; color: #353535;}
        .dao_c{ float: right; height: 40px}
        .dao_c a{ color: #576b95; position: relative; top: 8px;}
        .biao_con a{ text-decoration: none;}
        #btn_name :hover{
            background-color: yellow;
        }
    </style>

</head>

</head>
<body>
<div class="biao_con">
    <div class="v1"><span class="span_1">屈臣氏后台管理</span></div>
    <div class="v2">
<!--        <div class="ta_date" id="div_date1">

            <select id="sel" style="width: 200px;height: 30px">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
            <a id="btn"><img src="images/sou_1.png" alt=""></a>

        </div>-->
<!--        <div class="in_put">
            <input id="name" type="text" placeholder="请输入姓名">
            <a id="btn_name" style="margin-top: -4px;"><img src="images/sou_1.png" alt=""></a>
        </div>

        <div class="in_put">
            <input id="tel" type="text" placeholder="请输入电话号码">
            <a id="btn_tel" style="margin-top: -4px;"><img src="images/sou_1.png" alt=""></a>
        </div>-->

        <div class="dao_c" style="margin-left: 30px"><a href='http://watsons.yscase.com/api/admin/print_horse.php' id="toxlsx1">导出</a></div>
<!--        <div class="dao_c"><a href='http://h5.rowchina.cn/ohui_v1/dashboard/ban.php' id="toxlsx1">黑名单列表</a></div>-->
    </div>

    <form method="post" action="submit.php" id="listForm">
    <table class="tab1">
        <tr class="tr1">
            <th><input type="checkbox" id="selectAll" onclick="toggleSelect('ids[]')"> </th>
            <th style="width: 50px">opendid</th>
            <th style="width: 200px">文字</th>
            <th >点赞数</th>
     <!--       <th style="width: 50px">图片地址</th>-->
<!--            <th style="width: 110px">是否发布</th>-->
            <th >创建时间</th>
            <th style="width: 110px">发布时间</th>
        </tr>

        <tr class="user">
            <td><input type="checkbox" name="ids[]" value=""></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>

        </tr>

    </table>

        <input type="hidden" name="pageindex" value="" id="pageindex">

    <div class="fen_ye">

        <button type="button" class="btn btn-outline-danger mt-1" onclick="submitFormCheckSelect('ids[]','#listForm')">发布选中</button>

        <span class="">总共 <b id="count"></b> 条,共 <b id="pagecount"></b> 页</span>

        <a href='#' data-url="" id="left" class="disabled">上一页 </a>

        <a href='javascirpt:;' data-url="" id="left">当前 <b id="page"></b>页 </a>

        <a href='#' data-url="" id="right" class="disabled"> 下一页</a>
    </div>

    </form>
</div>

<script type="text/javascript">
    var host=window.location.host;
    console.log(host);
    var page;
    /* 初始化数据*/
    $.ajax({
        type : "get",
        url : "http://"+host+"/api/admin/init.php",
        data : 'json',
        success : function(result) {
            console.log(result);
            var t_user=toTemplate(result['horse'],'horse');
            $('#count').html(result['count']);
            $('#pagecount').html(result['pagecount']);
            $('#page').html(result['page']);
            //url : "http://"+host+"/ohui_v1/dashboard/mutual.php?method=init",


            $('#left').attr('data-url',"http://"+host+"/api/admin/init.php?pageindex="+(parseInt(result['page'])-1));

            $('#right').attr('data-url',"http://"+host+"/api/admin/init.php?pageindex="+(parseInt(result['page'])+1));


            $('#idx').html();

            if(result['page'] == 1 ){
                $("#left").hide();
            }else{
                $("#left").show();
            }
            if(result['page'] == result['pagecount'] ){
                $("#right").hide();
            }else{
                $("#right").show();
            }


            /*$('#sel').html(t_store);*/
            $('[class=user]').remove();
            $('.tab1').append(t_user);

        },
        error : function(e){
        }
    });

    $("#left").click(function () {
        var herf=$("#left").attr("data-url");
        $.ajax({
            type : "get",
            url  :herf,
            data : 'json',
            success : function(result) {

                console.log(result);
                var t_user=toTemplate(result['horse'],'horse');
                $('#count').html(result['count']);
                $('#pagecount').html(result['pagecount']);
                $('#page').html(result['page']);
                $("#pageindex").val(result['page']);
                $('#left').attr('data-url',"http://"+host+"/api/admin/init.php?pageindex="+(parseInt(result['page'])-1));

                $('#right').attr('data-url',"http://"+host+"/api/admin/init.php?pageindex="+(parseInt(result['page'])+1));


                $('#idx').html();

                if(result['page'] == 1 ){
                    $("#left").hide();
                }else{
                    $("#left").show();
                }

                if(result['page'] == result['pagecount'] ){
                    $("#right").hide();
                }else{
                    $("#right").show();
                }


                /*$('#sel').html(t_store);*/
                $('[class=user]').remove();
                $('.tab1').append(t_user);

            },
            error : function(e){
            }
        });
    });

    $("#right").click(function () {
        var herf=$("#right").attr("data-url");
        $.ajax({
            type : "get",
            url  :herf,
            data : 'json',
            success : function(result) {

                var t_user=toTemplate(result['horse'],'horse');
                $('#count').html(result['count']);
                $('#pagecount').html(result['pagecount']);
                $('#page').html(result['page']);

                $("#pageindex").val(result['page']);

                $('#left').attr('data-url',"http://"+host+"/api/admin/init.php?pageindex="+(parseInt(result['page'])-1));

                $('#right').attr('data-url',"http://"+host+"/api/admin/init.php?pageindex="+(parseInt(result['page'])+1));


                $('#idx').html();

                if(result['page'] == 1 ){
                    $("#left").hide();
                }else{
                    $("#left").show();
                }
                if(result['page'] == result['pagecount'] ){
                    $("#right").hide();
                }else{
                    $("#right").show();
                }


                /*$('#sel').html(t_store);*/
                $('[class=user]').remove();
                $('.tab1').append(t_user);

            },
            error : function(e){
            }
        });
    });

    /*模板替换*/
    function  toTemplate(data,res) {
        var template='';
        switch (res) {
            case 'horse':
                for (var i=0;i<data.length;i++){
                    if(data[i]['opentype'] == 0){
                        data[i]['opentype'] = '未发布';
                        //           console.log("  data[i]['opentype'] == '未领取';");
                    }else if(data[i]['opentype'] == 1){
                        data[i]['opentype'] = '已发布';
                    }
                    if(data[i]['updated_at'] == null){
                        data[i]['updated_at']='未发布'
                    }

                }
                for (var i=0;i<data.length;i++){
                    template +='<tr class="user">'+
                            //<td><input type="checkbox" name="ids[]" value=""></td>
                        "<td><input type='checkbox' name='ids[]' value=" +data[i]['id']+ "></td>"+
                        '<td>'+data[i]['openid']+'</td>'+
                        '<td>'+data[i]['content']+'</td>'+
                        '<td>'+data[i]['spotvalue']+'</td>'+
  /*                      '<td>'+data[i]['imageUrl']+'</td>'+*/
/*                        '<td>'+data[i]['opentype']+'</td>'+*/
                        '<td>'+data[i]['created_at']+'</td>'+
                        '<td>'+data[i]['updated_at']+'</td>'+
                        '<tr>';
                }
                return template;
            default:
                break;
        }
    }

    function submitFormCheckSelect(elementName, formSelector)
    {
        if ( $(':checkbox[name="'+elementName+'"]:checked').length < 1) {
            alert('请选中至少一条数据记录才能进行操作');
            return false;
        } else {

            var bol=confirm('发布后将不能撤销，确认发布吗？');
            if (bol){
                $(formSelector).submit();
                page
            }
        }
    }
    function toggleSelect(elementName)
    {
        var check_status  = $("#selectAll").is(':checked');;

        $(':checkbox[name="'+elementName+'"]').each(function(){
            $(this).prop('checked', check_status);
        });
    }
</script>


</body>
</html>
