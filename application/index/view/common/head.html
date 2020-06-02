<script type="text/javascript">
    var login_url="{:url('member/Account/checkLogin')}";
    var logout_url="{:url('member/User/logout')}";
    var cart_goods_num = "{:url('index/Flow/cartGoodsNum')}";
</script>
<script type="text/javascript" src="__index__/js/login.js"></script>
<div class="site-nav" id="site-nav">
    <div class="w <?php if(isset($show_right)){ echo 'w1200'; }else{ echo 'w1390'; } ?>">
        <div class="fl">
          <div class="txt-info" id="ECS_MEMBERZONE">
            <div class='scrollBody' id='scrollBody'></div>
    	  </div>
        </div>
        <ul class="quick-menu fr">
            {volist name="navRes.top" id="top_nav"}
            <li>
            	<div class="dt"><a {if condition="$top_nav['open'] eq 1"} target="_blank" {/if} href="{$top_nav.nav_url}" >{$top_nav.nav_name}</a></div>
            </li>
            {/volist}
        </ul>
    </div>
</div>
<div class="header">
    <div class="w <?php if(isset($show_right)){ echo 'w1200'; }else{ echo 'w1390'; } ?>">
        <div class="logo">
            <div class="logoImg"><a href="#"><img src="__index__/img/logo.png" /></a></div>
        </div>
        <div class="dsc-search">
            <div class="form">
                <form id="searchForm" name="searchForm" method="get" action="search.php" onSubmit="return checkSearchForm()" class="search-form">
                        <input autocomplete="off" onKeyUp="lookup(this.value);" name="keywords" type="text" id="keyword" value="内衣" class="search-text"/>
                    <input type="hidden" name="store_search_cmt" value="0">
                    <button type="submit" class="button button-goods" onclick="checkstore_search_cmt(0)" >搜商品</button>
                </form>
    <ul class="keyword">
    <li><a href="#" target="_blank">周大福</a></li>
    <li><a href="#" target="_blank">内衣</a></li>
    <li><a href="#" target="_blank">Five Plus</a></li>
    <li><a href="#" target="_blank">手机</a></li>
    </ul>
    
                <div class="suggestions_box" id="suggestions" style="display:none;">
                    <div class="suggestions_list" id="auto_suggestions_list">
                        &nbsp;
                    </div>
                </div>
                
            </div>
        </div>
        <div class="shopCart" data-ectype="dorpdown" id="ECS_CARTINFO" data-carteveval="0">
            
<div class="shopCart-con dsc-cm">
	<a href="#">
		<i class="iconfont icon-carts"></i>
		<span>我的购物车</span>
		<em id="cart_goods_num" class="count cart_num">0</em>
	</a>
</div>

<script type="text/javascript">
//ajax异步获取顶级分类下的子分类、品牌、频道等相关信息在右侧菜单显示
var ajax_cate_url="{:url('index/Category/getCateInfo')}";
//加载中图片路径
var load_img="__index__/img/loadGoods.gif";
// 上面自定义
function changenum(rec_id, diff, warehouse_id, area_id)
{
	var cValue = $('#cart_value').val();
    var goods_number =Number($('#goods_number_' + rec_id).text()) + Number(diff);
 
	if(goods_number < 1)
	{
		return false;	
	}
	else
	{
		change_goods_number(rec_id,goods_number, warehouse_id, area_id, cValue);
	}
}
function change_goods_number(rec_id, goods_number, warehouse_id, area_id, cValue)
{
	if(cValue != '' || cValue == 'undefined'){
	   var cValue = $('#cart_value').val(); 
	}   
	Ajax.call('flow.php?step=ajax_update_cart', 'rec_id=' + rec_id +'&goods_number=' + goods_number +'&cValue=' + cValue +'&warehouse_id=' + warehouse_id +'&area_id=' + area_id, change_goods_number_response, 'POST','JSON');                
}
function change_goods_number_response(result)
{    
	var rec_id = result.rec_id;           
    if (result.error == 0)
    {
       $('#goods_number_' +rec_id).val(result.goods_number);//更新数量
       $('#goods_subtotal_' +rec_id).html(result.goods_subtotal);//更新小计
       if (result.goods_number <= 0)
        {
			//数量为零则隐藏所在行
            $('#tr_goods_' +rec_id).style.display = 'none';
            $('#tr_goods_' +rec_id).innerHTML = '';
        }
        $('#total_desc').html(result.flow_info);//更新合计
        if($('ECS_CARTINFO'))
        {//更新购物车数量
            $('#ECS_CARTINFO').html(result.cart_info);
        }

		if(result.group.length > 0){
			for(var i=0; i<result.group.length; i++){
				$("#" + result.group[i].rec_group).html(result.group[i].rec_group_number);//配件商品数量
				$("#" + result.group[i].rec_group_talId).html(result.group[i].rec_group_subtotal);//配件商品金额
			}
		}

		$("#goods_price_" + rec_id).html(result.goods_price);
		$(".cart_num").html(result.subtotal_number);
	}
	else if (result.message != '')
	{
		$('#goods_number_' +rec_id).val(result.cart_Num);//更新数量
		alert(result.message);
	}                
}

function deleteCartGoods(rec_id,index)
{
	Ajax.call('delete_cart_goods.php', 'id='+rec_id+'&index='+index, deleteCartGoodsResponse, 'POST', 'JSON');
}

/**
 * 接收返回的信息
 */
function deleteCartGoodsResponse(res)
{
  if (res.error)
  {
    alert(res.err_msg);
  }
  else if(res.index==1)
  {
		Ajax.call('get_ajax_content.php?act=get_content', 'data_type=cart_list', return_cart_list, 'POST', 'JSON');
  }
  else
  {
	  $("#ECS_CARTINFO").html(res.content);
	  $(".cart_num").html(res.cart_num);
  }
}

function return_cart_list(result)
{
	$(".cart_num").html(result.cart_num);
	$(".pop_panel").html(result.content);
	tbplHeigth();
}
</script>        </div>
    </div>
</div>
<div class="nav dsc-zoom">
    <div class="w <?php if(isset($show_right)){ echo 'w1200'; }else{ echo 'w1390'; } ?>">
        <div class="categorys <?php if(!isset($show_nav)){ echo 'site-mast'; } ?>">
            <div class="categorys-type"><a href="#" target="_blank">全部商品分类</a></div>
            <div class="categorys-tab-content">
                <div class="categorys-items" id="cata-nav">
                <!-- 菜单开始 -->
                {volist name="cateRes" id="cate"}
					<div class="categorys-item" ectype="cateItem" data-id="{$cate.id}" data-eveval="0">
                        <div class="item item-content">
							<i class="iconfont {$cate.iconfont}"></i>
							<div class="categorys-title">
								<strong>
							         <a href="{:url('index/Category/index',array('id'=>$cate['id']))}" target="_blank">{$cate.cate_name}</a>
							    </strong>
								<span>

                                    {volist name="cate.children" id="son_cate"}
                                    {if condition="$i lt 3"}
                                    <a href="{:url('index/Category/index',array('id'=>$son_cate['id']))}" target="_blank">{$son_cate.cate_name}</a>
                                    {/if}
                                    {/volist}
        	                    </span>
							</div>
                        </div>
                        <div class="categorys-items-layer" ectype="cateLayer">
                            <div class="cate-layer-con clearfix">
                                <div class="cate-layer-left">
                                    <div class="cate_channel" ectype="channels_{$cate.id}"></div>
                                    <div class="cate_detail" ectype="subitems_{$cate.id}"></div>
                                </div>
                                <div class="cate-layer-rihgt" ectype="brands_{$cate.id}"></div>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                {/volist}

                    <!-- 菜单结束 -->
	    </div>
        </div></div>
        <div class="nav-main" id="nav">
            <ul class="navitems">
	            <li><a href="{:url('index/Index/index')}" class="curr">首页</a></li>
                {volist name="navRes.mid" id="mid_nav"}
	            <li><a {if condition="$mid_nav['open'] eq 1"} target="_blank" {/if} href="{$mid_nav.nav_url}"  >{$mid_nav.nav_name}</a></li>
                {/volist}
        	</ul>
        </div>
    </div>
</div>