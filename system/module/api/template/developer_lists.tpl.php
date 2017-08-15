<?php include template('header','admin');?>
<body>
		<div class="fixed-nav layout">
			<ul>
				<li class="first">开发者平台</li>
				<li class="spacer-gray"></li>
			</ul>
			<div class="hr-gray"></div>
		</div>

		<div class="content padding-big have-fixed-nav">
		<form action="" method="POST" data-reset=false>

			<div class="padding">
				<div class="table resize-table paging-table border clearfix">
					<div class="table-add-top">
						<div class="th layout">
							<span class="text-sub text-left">编辑开发者账号</span>
						</div>
					</div>
					<div class="tr border-none">
						<div class="th" data-width="35">
							<span class="td-con">appid</span>
						</div>
						<div class="th" data-width="45">
							<span class="td-con">secret</span>
						</div>
						<div class="th" data-width="10">
							<span class="td-con">账号是否开启</span>
						</div>
						<div class="th" data-width="10">
							<span class="td-con">操作</span>
						</div>
					</div>
					<?php foreach ($lists as $k => $list) {?>
					<div class="tr">
						<div class="td w35"><?php echo $k?></div>
						<div class="td w45"><?php echo $list['secret']?></div>
						<div class="td w10">
						<?php if($list['status'] == 1){?>
							<span class="td-con">
								<a class="ico_up_rack" data-id="<?php echo $k?>" href="javascript:;" title="点击禁用"></a>
								<input type="hidden" class="search" name="status[]" value="1">
							</span>
						<?php }else{?>
							<span class="td-con">
								<a class="ico_up_rack cancel" data-id="<?php echo $k?>" href="javascript:;" title="点击开启"></a>
								<input type="hidden" class="search" name="status[]" value="0">
							</span>
						<?php }?>
						</div>
						<div class="td w10">
							<a href="javascript:" class="del" data-id="<?php echo $k?>">删除</a>
						</div>
					</div>
					<?php }?>
					<div class="spec-add-button">
						<a href="javascript:;"><em class="ico_add margin-right"></em>添加一个账号</a>
					</div>
				</div>
			</div>
			<div class="padding submit-data">
				<input type="submit" class="button bg-main" name="dosubmit" value="确定" />
				<input type="button" class="button margin-left bg-gray" value="返回" />
			</div>
		</form>
		</div>
		<script>
			$(window).load(function(){
				var $val=$("input[type=text]").first().val();
				$("input[type=text]").first().focus().val($val);
				$('.resize-table').resizableColumns();
				//增加新规格属性行
				$('.attr-choose-wrap a').click(function(){
					var dataid = $(this).attr('data-id');
					if($(this).hasClass('current')){
						$(this).removeClass('current');
						$('#spec'+dataid).remove();
					}else{
						$(this).addClass('current');
						var text = '<input type="hidden" id="spec'+ dataid +'" name="spec_id[]" value="'+ dataid +'">';
						$(this).parent().append(text);
					}
				});
				var newid = parseInt($('input[name="attr_ids[]"]:last').val())+1;
				var i = newid > 0 ? newid : 2;
				$(".spec-add-button a").click(function(){
					$.get('<?php echo url("add")?>',{},function(ret){
						var html = '<div class="tr" style="visibility: visible;">'
									+'	<div class="td w35">'+ ret.result.appid +'</div>'
									+'<div class="td w45">'+ ret.result.secret +'</div>'
									+'	<div class="td w10">'
									+'	<span class="td-con"><a class="ico_up_rack" data-id="'+ ret.result.appid +'" href="javascript:;" title="点击取消"></a></span>'
									+'	</div>'
									+'	<div class="td w10">'
									+'		<a href="javascript:" class="del" data-id="'+ret.result.appid+'">删除</a>'
									+'	</div>'
									+'</div>';
						$(".spec-add-button a").parent().before(html);
						i++;
					},'json')
				});

				$(".table .ico_up_rack").live('click',function(){
					if(ajax_status($(this).attr('data-id')) == 1){
						if(!$(this).hasClass("cancel")){
							$(this).addClass("cancel");
							$(this).attr("title","点击开启");
						}else{
							$(this).removeClass("cancel");
							$(this).attr("title","点击关闭");
						}
					}
				});

				function ajax_status(appid){
					status = 1;
					$.post('<?php echo url("status")?>',{'appid':appid},function(data){
						if(data.status == 1){
							status =  1;
						}else{
							status =  0;
						}
					},'json');
					return status;
				}

				$('.del').live('click',function(){
					var $_this = $(this);
					if (!confirm("确认要删除么？")) {
                        return false;
                    }
                    $.post('<?php echo url("delete")?>',{'appid':$(this).attr('data-id')},function(ret){
						$_this.parents('.tr').remove();
					},'json');
				})

			})
		</script>
<?php include template('footer','admin');?>