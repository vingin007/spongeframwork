<p class="notice">您正在编辑 <em id="content-label" class="text-main">loading</em> 通知模板</p>

	<?php foreach($hooks as $tk=>$tv):?>

	<div id='edit_<?php echo $tk?>' style="display: none;" class="layout clearfix">
		<div class="form-layout-rank clearfix">
			<?php echo form::input('text', "{$tk}[title]", "{$template[template][$tk]['title']}", '短信模板id', '请在阿里大于控制台复制对应的短信模板id');?>
		</div>
	</div>
	<?php endforeach;?>