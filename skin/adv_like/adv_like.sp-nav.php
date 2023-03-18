<?php if(PKWK_SKIN_SHOW_NAVBAR) { ?>
	<ul>
		<li><?php _navigator('list') ?></li>
		<li><?php _navigator('search') ?></li>
		<li><?php _navigator('recent') ?></li>
		<li><?php _navigator('help') ?></li>
		<li>|</li>
		<?php if ($rw) { ?><li><?php _navigator('new') ?></li><?php } ?>
		<?php if ($is_page) { ?>
			<?php if ($rw) { ?>
				<?php if (!$is_freeze) { ?><li><?php _navigator('edit') ?></li><?php } ?>
				<?php if ($enable_logout) { ?>
					<li><?php _navigator('rename') ?></li>
					<?php if ($is_read && $function_freeze) { ?>
						<li><?php (! $is_freeze) ? _navigator('freeze') : _navigator('unfreeze') ?></li>
					<?php } ?>
				<?php } ?>
				<?php if (!$is_freeze && (bool)ini_get('file_uploads')) { ?><li><?php _navigator('upload') ?></li><?php } ?>
			<?php } ?>
			<?php if ($do_backup) { ?><li><?php _navigator('backup') ?></li><?php } ?>
			<li><?php _navigator('diff') ?></li>
		<?php } ?>
		<?php if ($enable_login || $enable_logout) { ?><li>|</li><?php } ?>
		<?php if ($enable_login) { ?><li><?php _navigator('login') ?></li><?php } ?>
		<?php if ($enable_logout) { ?><li><?php _navigator('logout') ?></li><?php } ?>
		<li>|</li>
		<label for="color_mode_switch" class="color_switch">
  			<span class="color_switch_title">Color:&nbsp;</span>
  			<div class="switch">
				<div class="circle"></div>
  				<div class="slider"></div>
  			</div>
 		</label>
	</ul>
<?php } // PKWK_SKIN_SHOW_NAVBAR ?>
