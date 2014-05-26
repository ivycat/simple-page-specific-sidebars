<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Page_Sidebar_Settings_View {
    
    static final public function get_view( Page_Sidebar_Settings_Model $page_sidebar_settings){
        
        global $post, $wp_registered_sidebars;
        
        $disabled = ''; //used to disable input fields and grey-out labels
        
        
        if ( $page_sidebar_settings->get_parent_settings() ) {
?>
            <!-- Store the parent state in JavaScript vars, if applicable, in case user
                 decides to switch to inherit.  Fields should populate in that case. -->
            <script type="text/javascript">
                var parentSettings = {
                    isCustom : <?php echo $page_sidebar_settings->get_parent_Settings()->is_custom_sidebar() ? 'true' : 'false'; ?>,
                    isAddToSidebar : <?php echo $page_sidebar_settings->get_parent_Settings()->is_add_to_sidebar() ? 'true' : 'false'; ?>,
                    isPrepend : <?php echo $page_sidebar_settings->get_parent_Settings()->is_prepend() ? 'true' : 'false'; ?>,
                    
                    /**
                    * Since the expected behavior when using parent settings is to
                    * use the parent's sidebar, we'll override the normal "use existing
                    * sidebar" behavior to use the parent's sidebar.  Further progeny
                    * will inherit this new setting as well so that they use the common
                    * ancestor's sidebar as well.
                    */
                    isUseExistingSidebar : true, //always true when inheriting
                    existingSidebarToUse : "<?php echo $page_sidebar_settings->get_parent_Settings()->is_use_existing_sidebar() ?
                                                            $page_sidebar_settings->get_parent_Settings()->get_existing_sidebar_to_use() :
                                                            $page_sidebar_settings->get_parent_Settings()->get_sidebar_term() ?>"
                };
            </script>
<?php   } ?>
       

    
        <div class="group" id="custom-sidebar">
            <ul><?php
            
                //var_dump($page_sidebar_settings);
                    
                //CASE: page has a parent...
                //Decide whether we're inheriting settings or overriding them
                if ( $post->post_parent > 0 ) {
                    
                    echo '<li>Site-wide default: ' . 
                            ($page_sidebar_settings->is_global_inheritance() ? '' : 'do not ') . 
                            'inherit sidebar settings from parent.</li>';
                    
                    echo "<li><input type='checkbox' id='is-inherit-parent-settings' name='is-inherit-parent-settings' " . checked($page_sidebar_settings->is_inherit_parent_settings(), true, false) . " value='true'/><label for='is-inherit-parent-settings'>Inherit Parent Sidebar Settings</label></li>";
                    
                     //disable fields if inherited from parent
                    $disabled = $page_sidebar_settings->is_inherit_parent_settings() ? " disabled " : "";
                        
                //CASE: page has no parent...   
                //inheritance is moot.  Display custom sidebar settings without inheritance/override
                } ?>
                        <input id="iscustom" type="checkbox" name="is-custom" value="y" <?php echo checked( $page_sidebar_settings->is_custom_sidebar() ) . $disabled; ?> />
                        <label for="iscustom" class="<?php echo $disabled ?>" title="Leave unchecked to use the default, primary sidebar" >Use Custom Sidebar</label>
                        
                        <ul class="custom-sidebar<?php echo ( $page_sidebar_settings->is_custom_sidebar() ) ? '' : ' hidden-h';  ?>" >
                            
                            <li>
                                
                                <input id="customsb" class="grpselect" type="radio" name="customsb" value="custom" <?php checked( $page_sidebar_settings->is_use_existing_sidebar(), false) . $disabled; ?>/>
                                <label for="customsb" class="<?php echo $disabled; ?>">Custom Sidebar </label>
                            
                            </li>
                            <li>

                                <input id="groupsb" class="grpselect" type="radio" name="customsb" value="group" <?php checked( $page_sidebar_settings->is_use_existing_sidebar() ) . $disabled; ?>/>
                                <label for="groupsb" class="<?php echo $disabled; ?>">Use Existing Sidebar </label>
                                
                                <ul class="existing-sidebars<?php $page_sidebar_settings->is_use_existing_sidebar() ? ' hidden-h' : ''; ?>">
                                    <li>
                                        <?php if( is_array( $wp_registered_sidebars ) ): ?>
                                        
                                            <select id="existing_sidebar_list" name="existing_sidebar_slug" class="<?php echo $disabled ?>" <?php echo $disabled; ?>>
                                                
                                                <?php foreach( $wp_registered_sidebars as $slug => $sidebar ): ?>
                                                
                                                    <option value="<?php echo $slug ?>" <?php selected( $slug, $page_sidebar_settings->get_existing_sidebar_to_use() ); ?> class="<?php echo $disabled ?>">
                                                        <?php  echo $sidebar['name']; ?>
                                                        
                                                    </option>
                                                    
                                                <?php endforeach; ?>
                                                    
                                            </select>
                                        
                                        <?php else: ?>
                                        
                                            It appears you have no sidebars registered with this theme.
                                            
                                        <?php endif; ?>
                                            
                                    </li>
                                    
                                </ul>
                                
                            </li>
                            
                            <li class="add-replace">
                                
                                <input type="checkbox" id="addrplce" name="add2sidebar" value="add2chk" <?php echo checked( $page_sidebar_settings->is_add_to_sidebar() ) . $disabled; ?>/>
                                <label for="addrplce" class="<?php echo $disabled; ?>">Add to sidebar rather than replace: </label>
                                
                                <ul class="sidebar-add <?php echo $page_sidebar_settings->is_add_to_sidebar() ? '' : ' hidden-h'; ?>">
                                    
                                    <li>
                                        <label class="<?php echo $disabled; ?>">
                                            <input id="prepend" type="radio" name="pre-append" value="prepend"<?php echo checked( $page_sidebar_settings->is_prepend() ) . $disabled;  ?>/>
                                            Prepend Sidebar (before)
                                        </label>
                                    </li>
                                    
                                    <li>
                                        <label class="<?php echo $disabled; ?>">
                                            <input id="append" type="radio" name="pre-append" value="append"<?php echo checked( $page_sidebar_settings->is_prepend(), false ) . $disabled;  ?>/>
                                            Append Sidebar (after)
                                        </label>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
    

<?php
    }
}
?>