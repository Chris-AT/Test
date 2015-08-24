<?php
add_action('wp_enqueue_scripts', 'sax_enqueue_css');
function sax_enqueue_css() {
    wp_enqueue_style('bootstrap-style', get_stylesheet_directory_uri() . '/css/bootstrap.min.css');
    
    wp_enqueue_style('font-awesome', get_stylesheet_directory_uri() . '/css/font-awesome.min.css');
    wp_enqueue_style('share-css', get_stylesheet_directory_uri() . '/css/jquery.share.css');
    wp_enqueue_style('gallery-css', get_stylesheet_directory_uri() . '/css/magnific-popup.css');
    wp_enqueue_style('main-style', get_stylesheet_uri(), array(), '2.1');
    
    wp_enqueue_script('Bootstrap', get_stylesheet_directory_uri() . '/js/bootstrap.min.js', array(), '1.0', true);
    wp_enqueue_script('JQuery', get_stylesheet_directory_uri() . '/js/jquery-2.1.4.js');
    wp_enqueue_script('share', get_stylesheet_directory_uri() . '/js/jquery.share.js');
    wp_enqueue_script('image-gallery-js', get_stylesheet_directory_uri() . '/js/jquery.magnific-popup.js');
    if ( is_singular() ) { 
        wp_enqueue_script( "comment-reply" ); 
    }
}
add_action('admin_menu', 'missing_options_page_add');
function missing_options_page_add() {
    add_menu_page('Setup Website', 'Setup Website', 'edit_pages', 'missing_person_setup_website', 'missing_setup_page_output');
}
function missing_setup_page_output() { ?>
    <div class="wrap">
        <?php 
        
	//show saved options message
        if( isset($_GET['settings-updated']) ) : ?>
            <div id="message" class="updated">
                <p><strong><?php _e('Settings saved.') ?></strong></p>
            </div>
        <?php endif; ?>
        <h2>Setup Page for Missing Persons</h2>
        <form method="post" action="options.php" enctype="multipart/form-data">

        <?php settings_fields( 'missing_settings_group' ); ?>
        <?php do_settings_sections( 'missing_settings_group' ); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Label "Missing":</th>
                <td>
                    <input type="text" name="label_missing" value="<?php echo esc_attr( get_option('label_missing') ); ?>"/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Name of the missing person:</th>
                <td>
                    <input type="text" name="person_name" value="<?php echo esc_attr( get_option('person_name') ); ?>"/>
                        
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Main picture of the person (portrait):</th>
                <td>
                    <input type="file" name="person_main_picture"/><br/>
                        <?php if(get_option('person_main_picture_url') !== '') : ?>
                        
                        <img class="backend-main-picture" src="<?php echo get_option('person_main_picture_url') ?>" width="500px"/>
                       <?php endif; ?>
                </td>
            </tr>
            <tr valign="top" class="gallery-input">
                <th scope="row">Picture for Gallery (optional): </th>
                <td>
                    <input type="file" name="person_gallery_picture[]" multiple/><br/>

                </td>
                    
            </tr>
            
            <?php
            if(get_option('person_gallery_URLs') != '') : ?>
            <tr> <th></th>
                <td><?php
                    $URLs = get_option('person_gallery_URLs');
                    $IDs = get_option('person_gallery_IDs');
                    for($i = 0; $i < count($URLs); $i++) : ?>
                    <span class="relative-container">
                        <img src="<?php echo $URLs[$i]; ?>" width="150px" class="gallery-preview" data-id="<?php echo $IDs[$i]; ?>"/>
                        <div class="clicktodelete">
                            <p class="deletetext">Click to delete</p>
                        </div>
                    </span>
                    <?php
                    endfor; ?>
                </td>
                </tr>
                <?php 
                endif; ?>
                <tr>
                    <th><?php _e('Attributes'); ?></th>
                    <td>
                        <?php  
                        $attributes = get_option('attribute');
                        $attributeLabels = get_option('attributeLabel');
                        
                        for($i = 0; $i < count($attributes); $i++) : ?>
                        
                        <div class="attribute">
                            <input type="text" name="attributeLabel[]" value="<?php echo $attributeLabels[$i]; ?>"/>&nbsp;:&nbsp;<input type="text" name="attribute[]" value="<?php echo $attributes[$i]; ?>"/>&nbsp;<div class="btn btn-primary delete-attribute">x</div>
                        </div> <!-- .attribute -->
                        <?php endfor; ?>
                        <div class="btn btn-primary add-attribute-button">+&nbsp;Add Attribute</div>
                    </td>
                </tr>  
                <tr>
                    <th><?php _e('Contact Information:'); ?></th>
                    <td>
                        <table class="contact_table">
                            <tr>
                            <th>EMail:</th>
                            <td><input type="text" name="email_to_contact" value="<?php echo get_option('email_to_contact') ?>"></td>
                            </tr>
                            <tr>
                            <th>Phone:</th>
                            <td><input type="text" name="phone_to_contact" value="<?php echo get_option('phone_to_contact'); ?>"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
        </table>
        <?php submit_button(); ?>
        </form>
    </div>

    <?php gallery_picutre_ajax_script(); ?>
<?php }

 add_action('admin_init', 'missing_register_settings');
 function missing_register_settings() {
    register_setting('missing_settings_group', 'label_missing');
    register_setting('missing_settings_group', 'person_name');
    register_setting('missing_settings_group', 'attributeLabel');
    register_setting('missing_settings_group', 'attribute');
    register_setting('missing_settings_group', 'person_main_picture', 'missing_main_picture_sanitize');
    register_setting('missing_settings_group', 'person_gallery_picture', 'missing_gallery_picture_sanitize');
    register_setting('missing_settings_group', 'email_to_contact');
    register_setting('missing_settings_group', 'phone_to_contact');
 }
 
 function missing_main_picture_sanitize() {
     if(!empty($_FILES['person_main_picture']['name'])) {
        if(get_option('person_main_picture_id') != false || get_option('person_main_picture_id') != "") {
            wp_delete_attachment(get_option('person_main_picture_id'));
        }
        $attachment_id = media_handle_upload('person_main_picture', 0);
        $attachmentURL = wp_get_attachment_image_src($attachment_id, 'full')[0];
        update_option('person_main_picture_url', $attachmentURL);
        update_option('person_main_picture_id', $attachment_id);
     }
 }

function missing_gallery_picture_sanitize() {
    if(!empty($_FILES['person_gallery_picture']['name'][0])) {
        $files = reArrayFiles($_FILES['person_gallery_picture']);
        //reorder for use of wp_upload_media
        foreach($files as $key => $value) {
            $_FILES['person_gallery_picture_' . $key] = $value;
        }
        $galleryURLArray = array();
        $galleryURLArray = get_option('person_gallery_URLs');
        $galleryIDArray = array();
        $galleryIDArray = get_option('person_gallery_IDs');
        $highestKey = count($files);
        for($i = 0; $i < $highestKey; $i++) {
            $currentID = media_handle_upload('person_gallery_picture_' . $i, 0);
            $galleryIDArray[] = $currentID;
            $galleryURLArray[] = wp_get_attachment_image_src($currentID, 'full')[0];
        }
        update_option('person_gallery_IDs', $galleryIDArray);
        update_option('person_gallery_URLs', $galleryURLArray);
     }
}

function reArrayFiles(&$file_post) {

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}

add_action( 'wp_ajax_delete_gallery_picture', 'delete_gallery_picture_ajax' );
function delete_gallery_picture_ajax() {
    $id = $_POST['id'];
    $src = $_POST['url'];
    $galleryURLArray = get_option('person_gallery_URLs');
    $galleryIDArray = get_option('person_gallery_IDs');
    wp_delete_attachment($id);
    unset($galleryIDArray[array_search($id, $galleryIDArray)]);
    unset($galleryURLArray[array_search($src, $galleryURLArray)]);
    //reindex to 0
    $galleryIDArray = array_values($galleryIDArray);
    $galleryURLArray = array_values($galleryURLArray);
    update_option('person_gallery_IDs', $galleryIDArray);
    update_option('person_gallery_URLs', $galleryURLArray);
    wp_die(); // this is required to terminate immediately and return a proper response
}

function gallery_picutre_ajax_script() { ?>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('.clicktodelete').click(function() {
                var element = jQuery(this).parent().find('.gallery-preview');
                // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
                var data = {
                    action: 'delete_gallery_picture',
                    id: element.attr('data-id'),
                    url: element.attr('src')
                };
		jQuery.post(ajaxurl, data, function(response) {
                    console.log('action: ' + data.action + ', id: ' + data.id + ', url: ' + data.url);
		});
                element.parent().replaceWith('');
            });
            
            //attributes
            jQuery('.add-attribute-button').click(function() {
                jQuery(this).before('<div class="attribute">' +
                            '<input type="text" name="attributeLabel[]"/>&nbsp;:&nbsp;<input type="text" name="attribute[]"/>&nbsp;<div class="btn btn-primary delete-attribute">x</div>' +
                        '</div>');
            });
            
            //delete attribute
            jQuery('.delete-attribute').click(function() {
                jQuery(this).parent().detach();
            });
        });
    </script>
<?php }

function load_custom_wp_admin_style() {
        wp_enqueue_style( 'admin_css', get_stylesheet_directory_uri() . '/css/admin-style.css' );
        wp_enqueue_style( 'bootstrap-style', get_stylesheet_directory_uri() . '/css/bootstrap.min.css' );
}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );