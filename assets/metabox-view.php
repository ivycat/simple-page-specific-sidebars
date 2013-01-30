<div class="group" id="custom-sidebar">
	<ul>
		<li>
			<input id="iscustom" type="checkbox" name="is-custom" value="y"<?php echo $checked; ?>/>
	        <label for="iscustom"><strong>Has Custom Sidebar</strong></label>
			<ul class="custom-sidebar<?php echo ( strlen( $checked ) > 0 ) ? '' : ' hidden-h';  ?>">
				<li>
					<input id="customsb" class="grpselect" type="radio" name="customsb" value="custom"<?php echo ( !$sb_group ) ? ' checked="checked"' : ''; ?>/>
					<label for="customsb">Custom Sidebar </label>
				</li>
				<li>
					<input id="groupsb" class="grpselect" type="radio" name="customsb" value="group"<?php echo ( $sb_group ) ? ' checked="checked"' : ''; ?>/>
					<label for="groupsb">Use Existing Sidebar </label>
					<ul class="existing-sidebars<?php echo ( !$sb_group ) ? ' hidden-h' : ''; ?>">
						<li><?php
							if( is_array( $wp_registered_sidebars ) ): ?>
								<select id="primary-slug" name="primary_sidebar_slug"><?php
									foreach( $wp_registered_sidebars as $slug => $sidebar ):?>
										<option value="<?php echo $slug . '"' . selected( $slug, $sb_group, false ); ?>><?php
										echo $sidebar['name']; ?>
										</option><?php
									endforeach; ?>
								</select><?php
							else: ?>
								It appears you have no sidebars registered with this theme.<?php
							endif; ?>
						</li>
					</ul>
				</li>
				<li class="add-replace">
					<input type="checkbox" id="addrplce" name="add2sidebar" value="add2chk"<?php echo $add2chk; ?>/>
					<label for="addrplce">Add to sidebar rather than replace: <label>
					<ul class="sidebar-add <?php echo $add2sb ? '' : ' hidden-h'; ?>">
						<li><label><input type="radio" name="pre-append" value="prepend"<?php echo $prepend ? ' checked="checked"' : ''  ?>/>
							Prepend Sidebar (before)</label>
						</li>
						<li><label><input type="radio" name="pre-append" value="append"<?php echo !$prepend ? ' checked="checked"' : ''  ?>/>
							Append Sidebar (after)</label>
						</li>
					</ul>
				</li>
			</ul>
		</li>
	</ul>
</div>