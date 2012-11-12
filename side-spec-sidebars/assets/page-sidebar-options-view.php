<div class="wrap" id="page-specific-sidebar-settings">
<div id="icon-options-general" class="icon32"></div>
    <h2>Page Specific Sidebar Plugin</h2>
    <div id="body-wrap" class="meta-box-sortables ui-sortable">
        <div id="metabox_desc" class="postbox">
            <div class="handlediv" title="Click to toggle">
                <br>
            </div>
            <div class="hndle">
                <h3><ul class="top-menu clearfix">
                    <li class="current-menu-tab menu-item"><a href="#settings">Settings</a></li>
                    <li class="menu-item"><a href="#help">Help</a></li>
                </ul></h3>
            </div>
            <div class="group settings current-tab inside">
                <?php if( $_SERVER['REQUEST_METHOD'] == "POST" ) echo "<div id='message' class='updated'>Page Sidebars Settings Successfully Saved!</div>"; ?>
                <?php //parent::fprint_r( $this->valid->all_formdata() ); ?>
                <form action="" method="post">
                    <ul>
                        <li><label for="primary-slug">Primary Sidebar</label></li>
                        <li><?php
							if( is_array( $wp_registered_sidebars ) ): ?>
								<select id="primary-slug" name="primary_sidebar_slug"><?php
									foreach( $wp_registered_sidebars as $slug => $sidebar ):
										if( !preg_match( '`page-sidebar-`', $slug ) ): ?>
											<option value="<?php echo $slug . '"' . selected( $slug, $this->widget_name, false ); ?>><?php
											echo $sidebar['name']; ?>
											</option><?php
										endif;
									endforeach; ?>
								</select><?php
							else: ?>
								It appears you have no sidebars registered with this theme.<?php
							endif; ?>
						</li>
                        <li><label for="home-id">Home Page ID *</label></li>
                        <li><input id="home-id" type="text" name="home_page_id" value="<?php echo $this->home_pg_id; ?>"/></li>
                        <li>* - Home page with blog posts have issues with the page ID being overridden.  This ensures that the right page ID is being used. (optional)</li>
                        <li><button type="submit" name="save_page_data">Update Settings</button></li>
                    </ul>
                </form>
            </div>
            <div class="group help inside">
                <p>To use this plugin, simply got to a page you wish to overwrite or extend the sidebar on, and select the apropriate checkbox:</p>
                <img src="<?php echo DGSIDEBAR_URL . '/screenshot-1.png'; ?>"/>
                <p>Add the Widgets to the sidebar as you wish:</p>
                <img src="<?php echo DGSIDEBAR_URL . '/screenshot-2.png'; ?>"/>
                <p>
                    This Plugin is theme sensitive. That means the sidebar naming is dependant on the theme.  Some themes name the primary sidebar
                    "sidebar-primary", others name it "sidebar-1".  To find the name of the sidebar, simply right click on the primary sidebar (widget page)
                    and view the page source. See image below:
                </p>
                <img src="<?php echo DGSIDEBAR_URL . '/screenshot-3.png'; ?>"/>
                <p>
                    Homepage ID is the post_id of your Homepage.  To find the page id, simply go to Pages->Home (orwhatever your home page is named) and click edit.
                    In the address bar of your browser, you'll see something like mysite.com/wp-admin/post.php?post=x  where x is some number.  That is the Page ID. 
                </p>
            </div>
        </div>
    </div>
    <div id="sidebar-wrap">
            <?php require_once 'desc.php'; ?>
    </div>
</div>
