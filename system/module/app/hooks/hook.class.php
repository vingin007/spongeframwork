<?php
class module_admin_hook extends hd_hook
{
	public function update_cache() {
		model('app/app','service')->get_plugins();
	}
}