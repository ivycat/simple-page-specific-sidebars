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
                                            <option value="<?php echo $slug . '"' . selected( $slug, $this->widget_name, false ); ?>"><?php
                                                echo $sidebar['name']; ?>
                                            </option><?php
                                        endif;
                                    endforeach; ?>
                                </select><?php
                            else: ?>
                                It appears you have no sidebars registered with this theme.<?php
                            endif; ?>
                        </li>
                        <!--<li><label for="home-id">Home Page ID *</label></li>
                        <li><input id="home-id" type="text" name="home_page_id" value="<?php //echo $this->home_pg_id; ?>"/></li>
                        <li>* - Home page with blog posts have issues with the page ID being overridden.  This ensures that the right page ID is being used. (optional)</li>-->
                        <li><button type="submit" name="save_page_data">Update Settings</button></li>
                    </ul>
                </form>
            </div>
            <div class="group help inside">
                <h2>To use this plugin...</h2>
                <h3>1. Define the Primary Sidebar</h3>
                <p>If you're reading this, then you've already navigated to this plugin's settings page.  Click on the settings tab, and select the primary sidebar from the dropdown menu.  The selected sidebar will be customized on the pages you choose.</p>
                <div class='img-wrapper'><img src="<?php echo DGSIDEBAR_URL . '/assets/primary_sidebar_dropdown.png'; ?>"/></div>
                <h3>2. Select a Page Whose Sidebar Will Be Customized</h3>
                <p>Navigate to the <em>Edit Page</em> screen for a page whose sidebar you want to customize, and select the appropriate box(es):</p>
                <div class="img-wrapper"><img src="<?php echo DGSIDEBAR_URL . '/assets/custom_page_option_checked.png'; ?>"/></div>
                <h3>3. Edit New Widget Area</h3>
                <p>When activated for a page, a new widget area will be created for that page.  Add widgets and customize the new widget area according to your design.</p>
                <div class='img-wrapper'><img src="<?php echo DGSIDEBAR_URL . '/assets/new_page_specific_sidebar_widget_area.png'; ?>"/></div>
            </div>
        </div>
    </div>
    <div id="sidebar-wrap">
            <?php require_once 'desc.php'; ?>
    </div>
</div>
