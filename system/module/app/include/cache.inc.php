<?php
/* 删除商品分类缓存 */
cache('app_lists',NULL);
model('app/app','service')->clear_cache();