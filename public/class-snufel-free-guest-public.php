<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://snufel.com/team
 * @since      1.0.0
 *
 * @package    Snufel_Free_Guest
 * @subpackage Snufel_Free_Guest/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Snufel_Free_Guest
 * @subpackage Snufel_Free_Guest/public
 * @author     Snufel <contact@snufel.com>
 */
class Snufel_Free_Guest_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Snufel_Free_Guest_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Snufel_Free_Guest_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/snufel-free-guest-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Snufel_Free_Guest_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Snufel_Free_Guest_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/snufel-free-guest-public.js', array( 'jquery' ), $this->version, false );

	}

}





add_shortcode('free_guestpost_form','free_guestpostform_layout');
function free_guestpostform_layout(){
	add_action('wp_footer','snufel_freeguestpostform_layout_ajax');
	ob_start();
	$snufel_free_guest_post_settings_options = get_option( 'snufel_free_guest_post_settings_option_name' ); 
	$minimumwords = $snufel_free_guest_post_settings_options['what_is_the_accepted_minimum_words_0']; 
	$instructions_to_guest = $snufel_free_guest_post_settings_options['instructions_to_guest_1'];
	$autopublish = $snufel_free_guest_post_settings_options['posts_will_be_2']; 
	$email_to_guest_when_published = $snufel_free_guest_post_settings_options['email_to_guest_when_published_3']; 
	$notify_emails = $snufel_free_guest_post_settings_options['enter_the_emails_that_will_be_notified_when_a_guest_post_is_submitted_separated_by_commas_4'];
	
	$form = '<!-- New Post Form -->
<div id="snufel_postbox">
<div class="snufel_instruction">'.$instructions_to_guest.'</div>
<div class="snufel_min_words">Minimum Words: '.$minimumwords.'</div>
<form id="new_post" name="new_post" method="post" action="">

<!-- post name -->
<p><label for="title">Title</label><br />
<input type="text" id="title" value="" tabindex="1" size="20" name="title" />
</p>


<!-- post Content -->
<p><label for="description">Content</label><br />
'. wp_editor( '', 'post_content' ).'
</p>

';
	$form .= ob_get_clean();


	$form .= '<!-- post Category -->
<p><label for="Category">Category:</label><br />';
	$form .='<select id="wp_category">';
	$categories = get_categories(array("hide_empty" => 0,
                    "type"      => "post",      
                    "orderby"   => "name",
                    "order"     => "ASC" ));
foreach($categories as $category) {
   $form .= '<option value="'.$category->term_id.'">' . $category->name . '</option>';
}
	$form .='</select>';
	
	$form .='
<p><label for="display_name">Your Name</label><br />
<input type="text" id="display_name" value="" />
</p>
<p><label for="user_email">Your Email</label><br />
<input type="text" id="user_email" value=""  />
</p>
<p><label for="keywords">Keywords</label><br />
<input type="text" id="keywords" value=""  />
</p>
<p align="right"><div id="snufel_free_submit" >Submit Guest Post</div></p>

<input type="hidden" name="action" value="new_post" />
'.wp_nonce_field( 'new-post' ).'
</form>
</div>
<div class="posting_status"></div>';
	return $form;
}




add_action('wp_ajax_nopriv_submit_free_guest_post', 'snufel_submit_free_guest_post');
add_action('wp_ajax_submit_free_guest_post', 'snufel_submit_free_guest_post');


function snufel_submit_free_guest_post(){
	$snufel_free_guest_post_settings_options = get_option( 'snufel_free_guest_post_settings_option_name' ); 
	$minimumwords = $snufel_free_guest_post_settings_options['what_is_the_accepted_minimum_words_0']; 
	$instructions_to_guest = $snufel_free_guest_post_settings_options['instructions_to_guest_1'];
	$autopublish = $snufel_free_guest_post_settings_options['posts_will_be_2']; 
	$email_to_guest_when_published = $snufel_free_guest_post_settings_options['email_to_guest_when_published_3']; 
	$notify_emails = $snufel_free_guest_post_settings_options['enter_the_emails_that_will_be_notified_when_a_guest_post_is_submitted_separated_by_commas_4'];
	if (isset ($_POST['post_title'])) {
        $title =  $_POST['post_title'];
    }
    if (isset ($_POST['post_content'])) {
        $description = $_POST['post_content'];
    } 

	if (str_word_count($description)>=$minimumwords){
	$author = $_POST['author_name'];
	$useremail = $_POST['user_email'];
	$keywords = $_POST['seo_keywords'];
    // Add the content of the form to $post as an array
    $new_post = array(
        'post_title'    => $title,
        'post_content'  => $description,
        'post_category' => array($_POST['post_category']),  // Usable for custom taxonomies too
        'post_status'   => $autopublish,           // Choose: publish, preview, future, draft, etc.
        //'post_type' => 'post'  //'post',page' or use a custom post type if you want to
    );
    //save the new post
    $pid = wp_insert_post($new_post); 
	update_post_meta($pid,'guest_author',$author); 
	update_post_meta($pid,'guest_email',$useremail);
	wp_set_post_tags($pid,$keywords);
		$return= array(
		'status'=>"success",
		'message'=>'Article with title "'.$title.'" has been published.'
		);
		if ($autopublish=="publish"){
		do_action('snufel_freepost_published',array('id'=>$pid,'title'=>$title));
		}else{
		do_action('snufel_freepost_pending',array('id'=>$pid,'title'=>$title));
		}
		echo json_encode($return);
		die();
	}else{
		$return= array(
		'status'=>"fail",
		'message'=>'Wordcount needs to be at least '.$minimumwords.' words'
		);
		do_action('snufel_freepost_postfail',$_POST);
		echo json_encode($return);
		die();
	}
}

add_action('snufel_freepost_published','snufel_notify_people');
function snufel_notify_people($post){
	$snufel_free_guest_post_settings_options = get_option( 'snufel_free_guest_post_settings_option_name' ); 
	$minimumwords = $snufel_free_guest_post_settings_options['what_is_the_accepted_minimum_words_0']; 
	$instructions_to_guest = $snufel_free_guest_post_settings_options['instructions_to_guest_1'];
	$autopublish = $snufel_free_guest_post_settings_options['posts_will_be_2']; 
	$email_to_guest_when_published = $snufel_free_guest_post_settings_options['email_to_guest_when_published_3']; 
	$notify_emails = $snufel_free_guest_post_settings_options['enter_the_emails_that_will_be_notified_when_a_guest_post_is_submitted_separated_by_commas_4'];
	wp_mail($notify_emails,'New Guest Post Has Been Published','Hello,
	
A new guest post has been published on your website. 
'.$post['title'].'
'.get_the_permalink($post['id']).'

Thank you for using our plugin.

Regards
www.Snufel.com
');
	if ($email_to_guest_when_published=="yes"){
		$gemail = get_post_meta($post['id'],'guest_email',true); 
		$gname = get_post_meta($post['id'],'guest_author',true);
		
	wp_mail($gemail,'Congratulations! Your guest post has been published','Hi '.$gname.',
	
Your guest post is lived on our website. 
'.$post['title'].'
'.get_the_permalink($post['id']).'

Thank you for guest posting on our website.

Regards,
'.get_bloginfo('name').'
'.get_bloginfo('url').'


');
		
	}
}






add_action('snufel_freepost_pending','snufel_notify_pending');
function snufel_notify_pending($post){
	$snufel_free_guest_post_settings_options = get_option( 'snufel_free_guest_post_settings_option_name' ); 
	$minimumwords = $snufel_free_guest_post_settings_options['what_is_the_accepted_minimum_words_0']; 
	$instructions_to_guest = $snufel_free_guest_post_settings_options['instructions_to_guest_1'];
	$autopublish = $snufel_free_guest_post_settings_options['posts_will_be_2']; 
	$email_to_guest_when_published = $snufel_free_guest_post_settings_options['email_to_guest_when_published_3']; 
	$notify_emails = $snufel_free_guest_post_settings_options['enter_the_emails_that_will_be_notified_when_a_guest_post_is_submitted_separated_by_commas_4'];
	$gemail = get_post_meta($post['id'],'guest_email',true); 
	$gname = get_post_meta($post['id'],'guest_author',true);
	wp_mail($notify_emails,'[Notification] Please Review New Guest Post On '.get_bloginfo('name'), 'Hello,
	
A new guest post has been submitted on your website and is awaiting for your review.
'.$post['title'].'
'.get_the_permalink($post['id']).'

The guest information is as follows:
Name: '.$gname.'
Email: '.$gemail.'

Please review the post and inform the guest.

Regards
www.Snufel.com
');
	if ($email_to_guest_when_published=="yes"){
		$gemail = get_post_meta($post['id'],'guest_email',true); 
		$gname = get_post_meta($post['id'],'guest_author',true);
		
	wp_mail($gemail,'Thank you! Your guest post is being reviewed','Hi '.$gname.',
	
Thank you for submitting a guest post. 
Your guest post currently being reviewed.
Post Name: '.$post['title'].'

Thank you for guest posting on our website.

Regards,
'.get_bloginfo('name').'
'.get_bloginfo('url').'


');
		
	}
}


function snufel_freeguestpostform_layout_ajax(){
?>

<script>
	jQuery('.snufel_preloader').hide();
	jQuery('div.posting_status').hide();
jQuery("#snufel_free_submit").click(function(e){
	
	var post_title = jQuery('#title').val();
	//var post_content = jQuery('.post_content').val();
	var post_content = tinymce.activeEditor.getContent();
	var display_name = jQuery('input#display_name').val();
	var user_email = jQuery('input#user_email').val();
	var post_category = jQuery('#wp_category').find(":selected").val();
	var post_keywords = jQuery('input#keywords').val();
	var thisbtn = jQuery(this);
	var poststate = jQuery('div.posting_status');
	poststate.show();
	thisbtn.hide();
	var preloader = jQuery('.snufel_preloader');
	preloader.show();
	poststate.css('background','#ffefb5');
	poststate.html("<center>Posting now...</center>");
	var data = { 'action':'submit_free_guest_post', 'post_title':post_title, 'post_content':post_content,'author_name':display_name, 'user_email':user_email,'post_category':post_category,'seo_keywords':post_keywords};
	
	console.log(post_category);
	//jQuery("#kh_generate_article_now").hide();
	
    jQuery.ajax({
		url : '<?php echo admin_url( 'admin-ajax.php' ); ?>',
		type: "POST",
	  	data,
		dataType: "json",
		success: function(response) {
			console.log(response);
			preloader.hide();
			
			if (response.status=="success"){
				jQuery('#snufel_postbox').hide();
				poststate.css('background','#c3ffb5');
				poststate.html('Success! ' +response.message);
			}else{
				poststate.html('Failed! '+response.message);
				poststate.css('background','#ffb5b5');
				thisbtn.show();
			}
		},
		error: function(jqXHR, textStatus, errorThrown) {
            preloader.hide();
			thisbtn.show();
			console.log(jqXHR);
			console.log(textStatus);
			console.log(errorThrown);
        	alert("There seem to be an error."); // this will be "timeout"
    	},
		timeout: 925000 
		
    });

});
	
</script>
<?php
}
