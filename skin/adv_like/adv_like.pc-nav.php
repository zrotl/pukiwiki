<?php if(PKWK_SKIN_SHOW_NAVBAR) { ?>

 [
  <?php _navigator('list') ?>
  |
  <?php if (arg_check('list')) { ?>
	<?php _navigator('filelist') ?>
	|
  <?php } ?>
  <?php _navigator('search') ?>
  |
  <?php _navigator('recent') ?>
  |
  <?php _navigator('help')   ?>
 ]

 [
	<?php if ($rw) { ?>
		<?php _navigator('new') ?>
	<?php } ?>
	<?php if ($is_page) { ?>
		<?php if ($rw) { ?>
			<?php if (!$is_freeze) { ?>
				| <?php _navigator('edit') ?>
			<?php } ?>
			<?php if ($enable_logout) { ?>
				| <?php _navigator('rename') ?>
				<?php if ($is_read && $function_freeze) { ?>
					| <?php (! $is_freeze) ? _navigator('freeze') : _navigator('unfreeze') ?>
				<?php } ?>
			<?php } ?>
			<?php if (!$is_freeze && (bool)ini_get('file_uploads')) { ?>
				| <?php _navigator('upload') ?>
 			<?php } ?>
		<?php } ?>
		<?php if ($do_backup) { ?>
			| <?php _navigator('backup') ?>
		<?php } ?>
		| <?php _navigator('diff') ?>
	<?php } ?>
 ]

 <?php if ($enable_login || $enable_logout) { ?>
	[
		<?php if ($enable_login) { ?>
			<?php _navigator('login') ?>
 		<?php } ?>
 		<?php if ($enable_logout) { ?>
 			<?php _navigator('logout') ?>
 		<?php } ?>
	]
 <?php } ?>

<?php } // PKWK_SKIN_SHOW_NAVBAR ?>
