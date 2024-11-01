<div class="wrap">
	<?php screen_icon('tools'); ?> <h2><?php _e('WPMU Google Sitemap','wpmugs'); ?></h2>
	
	<form method="POST" action="admin.php?page=wpmugs-settings">
	<h3><?php _e('Priority settings','wpmugs'); ?></h3>
	<table class="form-table">
		<tr valign="top">
			<th scope="row">
				<label for="pages"><?php _e('Pages','wpmugs'); ?></label>
			</th>
			<td>
				<select name="pages" id="pages" style="width: 50px;">
				<?php for ( $i=0.1;$i<=1;$i=$i+0.1): ?><option<?php if ( wpmugs::get('pages') == strval($i) ) echo ' SELECTED'; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option><?php endfor; ?>
				</select>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">
				<label for="posts"><?php _e('Posts','wpmugs'); ?></label>
			</th>
			<td>
				<select name="posts" id="posts" style="width: 50px;">
				<?php for ( $i=0.1;$i<=1;$i=$i+0.1): ?><option<?php if ( wpmugs::get('posts') == strval($i) ) echo ' SELECTED'; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option><?php endfor; ?>
				</select>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">
				<label for="blogidx"><?php _e('Blog frontpage','wpmugs'); ?></label>
			</th>
			<td>
				<select name="blogidx" id="blogidx" style="width: 50px;">
				<?php for ( $i=0.1;$i<=1;$i=$i+0.1): ?><option<?php if ( wpmugs::get('blogidx') == strval($i) ) echo ' SELECTED'; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option><?php endfor; ?>
				</select>
			</td>
		</tr>
	</table>
	
	<h3><?php _e('Change frequency settings','wpmugs'); ?></h3>
	<table class="form-table">
		<tr valign="top">
			<th scope="row">
				<label for="ch_pages"><?php _e('Pages','wpmugs'); ?></label>
			</th>
			<td>
				<select name="ch_pages" id="ch_pages" style="width: 100px;"><?php echo wpmugs::getchangefreq( wpmugs::get('ch_pages') ); ?></select>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">
				<label for="ch_posts"><?php _e('Posts','wpmugs'); ?></label>
			</th>
			<td>
				<select name="ch_posts" id="ch_posts" style="width: 100px;"><?php echo wpmugs::getchangefreq( wpmugs::get('ch_posts') ); ?></select>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row">
				<label for="ch_blogidx"><?php _e('Blog index','wpmugs'); ?></label>
			</th>
			<td>
				<select name="ch_blogidx" id="ch_posts" style="width: 100px;"><?php echo wpmugs::getchangefreq( wpmugs::get('ch_blogidx') ); ?></select>
			</td>
		</tr>
	</table>
	
	<h3><?php _e('XML URL settings','wpmugs'); ?></h3>
	<table class="form-table">
		<tr valign="top">
			<th scope="row">
				<label for="urlname"><?php _e('Sitemap XML name','wpmugs'); ?></label>
			</th>
			<td>
				<input type="text" name="urlname" id="urlname" value="<?php echo wpmugs::get('urlname'); ?>" /><br />
				<a href="<?php echo get_bloginfo('url'); ?>/<?php echo wpmugs::get('urlname'); ?>" target="_blank"><?php echo get_bloginfo('url'); ?>/<?php echo wpmugs::get('urlname'); ?></a>
			</td>
		</tr>
	</table>

	
	<p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes','wpmugs') ?>" /></p>
	</form>