

<?php

// enqeue stylesheet
wp_enqueue_style('settings-style',plugins_url('/cee-webscanner/includes/settings-style.css'));

?>


 <?php
	require_once __DIR__.'/ProgressiveWebApp.php';
	
   if(isset($_POST['submitbtn'])){
	 CEE_saveSettings();
	 
	 $pwaManager = new ProgressiveWebApp();
	 
	 $data = array(
					"short_name" => get_option('CEE-webscanner-pwa-short_name'),
					"description" => get_option('CEE-webscanner-pwa-description'),
					"icon_144" => get_option('CEE-webscanner-pwa-icon_144'),
					"icon_192" => get_option('CEE-webscanner-pwa-icon_192'),
					"icon_512" => get_option('CEE-webscanner-pwa-icon_512'),
					"background_color" => get_option('CEE-webscanner-backgroundcolor'),
					"theme_color" => get_option('CEE-webscanner-color'),
					"start_url" => get_option('CEE-webscanner-pwa-start_url')
					
				);
	 
	 $pwaManager -> makeProgressiveWebApp($data);
	 
	 //reload page
	 //echo '<script>window.location.reload();</script>';
	 
 } elseif (isset($_POST['defaultbtn'])){
	 CEE_setDefaultSettings();
 }

  ?>


<!-- media manager-->

<style>

td {
	font-size:15pt;
}





</style>


<?php
 
 require_once __DIR__.'/UploadManager.php';
 
 function CEE_setDefaultSettings(){
	 //Set default options
	 update_option('CEE-webscanner-token','');
	 update_option('CEE-webscanner-color','#9acc13');
	 update_option('CEE-webscanner-starttext','Start Scanning');
	 update_option('CEE-webscanner-stoptext','Stop Scanning');
	 update_option('CEE-webscanner-noresulttext','No results found, point to an object');
	 update_option('CEE-webscanner-backgroundcolor','#333333');
	 update_option('CEE-webscanner-logo-file',plugins_url('cee-webscanner/cee-template/images/testlogo.png'));
	 
	 //set default options for PWA file
	 update_option('CEE-webscanner-pwa-short_name', 'CEE');
	 update_option('CEE-webscanner-pwa-description', 'CEE Webscanner');
	 update_option('CEE-webscanner-pwa-start_url', '/');
	 
	 update_option('CEE-webscanner-pwa-icon_144',plugins_url('cee-template/images/pwa_icon_144.png', CEE_PLUGIN_NAME));
	 update_option('CEE-webscanner-pwa-icon_192',plugins_url('cee-template/images/pwa_icon_192.png', CEE_PLUGIN_NAME));
	 update_option('CEE-webscanner-pwa-icon_512',plugins_url('cee-template/images/pwa_icon_512.png', CEE_PLUGIN_NAME));
	 
 }
 
 function getPageUrl($pageID){
	$url = "/";
		 
	$pages = get_pages();
	
	foreach($pages as $page){
		
		if ($page -> ID == $pageID){
			$url = $page -> guid;
		}
	}
	
	
	
	return str_replace("http://","https://",$url);
	
 }
 
 function CEE_saveSettings(){
	 require_once __DIR__.'/UploadManager.php';
	 
	 
	 	 
	 // check if nonce is valid, if not, it dies
	 check_admin_referer('cee-webscanner-update_options',"updateform");
	 
	 
	 
	 //sanitize input fields
	 $token_raw = $_POST['CEE-webscanner-token'];
	 $token_sanitized = sanitize_text_field($token_raw);
	 
	 $color_raw = $_POST['CEE-webscanner-color'];
	 $color_sanitized = sanitize_hex_color( $color_raw );
	 
	 $starttext_raw = $_POST['CEE-webscanner-starttext'];
	 $starttext_sanitized = sanitize_text_field( $starttext_raw );
	 
	 $stoptext_raw = $_POST['CEE-webscanner-stoptext'];
	 $stoptext_sanitized = sanitize_text_field( $stoptext_raw );
	 
	 $noresulttext_raw = $_POST['CEE-webscanner-noresulttext'];
	 $noresulttext_sanitized = sanitize_text_field( $noresulttext_raw );
	 
	 $backgroundcolor_raw = $_POST['CEE-webscanner-background'];
	 $backgroundcolor_sanitized = sanitize_hex_color( $backgroundcolor_raw );
	 
	 
	 $uploadManager = new UploadManager(dirname(__DIR__).'/cee-template/images/');
	 
	 // sanitize input fields for PWA application
	 
	 
	 $pwa_short_name_raw = $_POST['CEE-webscanner-pwa-short_name'];
	 $pwa_short_name_sanitized = sanitize_text_field($pwa_short_name_raw);
	 
	 $pwa_description_raw = $_POST['CEE-webscanner-pwa-description'];
	 $pwa_description_sanitized = sanitize_text_field($pwa_description_raw);
	 
	 	 
	 // start url
	 $pwa_start_url_raw = getPageUrl($_POST['CEE-webscanner-pwa-start_url']);
	 $pwa_start_url_sanitized = sanitize_text_field($pwa_start_url_raw);
	 
	 //also save start url id, to select the settings page in dropdown
	 $pwa_start_url_id_raw = $_POST['CEE-webscanner-pwa-start_url'];
	 $pwa_start_url_id_sanitized = sanitize_text_field($pwa_start_url_id_raw);
	 
	 
	 
	  
	 
	 //only update if the user has the right capabilities
	 if (current_user_can('edit_plugins')){
		 
		 update_option('CEE-webscanner-token',$token_sanitized);
		 update_option('CEE-webscanner-color',$color_sanitized);
		 update_option('CEE-webscanner-starttext',$starttext_sanitized);
		 update_option('CEE-webscanner-stoptext',$stoptext_sanitized);
		 update_option('CEE-webscanner-noresulttext',$noresulttext_sanitized);
		 update_option('CEE-webscanner-backgroundcolor',$backgroundcolor_sanitized);
		 
		 //PWA options
		 update_option('CEE-webscanner-pwa-short_name', $pwa_short_name_sanitized);
		 update_option('CEE-webscanner-pwa-description', $pwa_description_sanitized);
		 update_option('CEE-webscanner-pwa-start_url', $pwa_start_url_sanitized);
		 
		 update_option('CEE-webscanner-pwa-start_url_id', $pwa_start_url_id_sanitized);
		 
		 
		 $imageFilesForUpload = array();
			array_push($imageFilesForUpload, array("files_id"=> "CEE-webscanner-logo-file", "width"=> 210, "height" => 40, "error_box"=>"error_logo"));
			array_push($imageFilesForUpload, array("files_id"=> "CEE-webscanner-pwa-icon_144", "width"=> 144, "height" => 144, "error_box"=>"error_icon_144"));
			array_push($imageFilesForUpload, array("files_id"=> "CEE-webscanner-pwa-icon_192", "width"=> 192, "height" => 192, "error_box"=>"error_icon_192"));
			array_push($imageFilesForUpload, array("files_id"=> "CEE-webscanner-pwa-icon_512", "width"=> 512, "height" => 512, "error_box"=>"error_icon_512"));
			
		 
		 foreach($imageFilesForUpload as $image){
			 
			 // upload logo banner
			 if ($_FILES[$image['files_id']]['size'] != 0){
				 // image dimensions banner = 210x40
				 $uploadResult = $uploadManager -> uploadImage($_FILES[$image['files_id']],$image['width'],$image['height']);
				 
				 if ($uploadResult['success'] == true){
					 
					 
					 $relDir = 'cee-webscanner/cee-template/images/'.$uploadResult['filepath'];
					 $relDir = str_replace(" ","%20",$relDir);
					 update_option($image['files_id'],plugins_url($relDir));
					 
				 } else {
					 
					 echo '<script>document.getElementById("'.$image['error_box'].'").innerHTML ="';
					 echo 'Something went wrong during uploading your image: '.$uploadResult['message'];
					 echo '";</script>';
					 echo '<span class="error-box" style="color:red;font-size:20px">Something went wrong uploading your image: '.$uploadResult['message'].'</span>';
				 }
			}
		 }
		
		// upload
			
	 } else {
		 die();
	 }
	 
	
		 
 }
 
 


?>



<div class="wrap">


  <h1>CEE Webscanner Settings</h1>
  
  
  
  <div class="settingsForm">
  <form method="post" action="" enctype="multipart/form-data">
  
  <?php
  
	//worpdress nonce protection - https://markjaquith.wordpress.com/2006/06/02/wordpress-203-nonces/
	if ( function_exists('wp_nonce_field') ) 
		wp_nonce_field('cee-webscanner-update_options',"updateform"); 
	
	?>
  
  
  
  <?php settings_fields( 'CEE-webscanner-settings' ); ?>
  <?php do_settings_sections( 'CEE-webscanner-settings' ); ?>
  
	<table class="form-table">
		<tr>
			<th scope="row" colspan="2">
				<h2>Scanning page settings</h2>
			</th>
			
		</tr>
		<tr>
			<th scope="row">
			Your private Token 
				<div class="tooltip">
					<img src="<?php echo plugin_dir_url(__FILE__).'/questionmark.png';?>" width="20"></img>
				<span class="tooltiptext">Your private token is an alphanumeric code of 16 characters which refers to your pool of images in our image recognition database. Please contact CEE Platform if you have not received your private token.</span>
				</div>
				
			</th>
			<td>
				<input type="text" name="CEE-webscanner-token" value="<?php echo get_option('CEE-webscanner-token'); ?>" size="50"/>
				<p>Insert your private token here. Without a token the scanner will not work.</p>
			</td>
		</tr>
		<tr>
			<th scope="row">
				Your Logo (url)
			</th>
			<td>
				<input type="file" name="CEE-webscanner-logo-file" value="<?php echo get_option('CEE-webscanner-logo-file'); ?>" size="50"/>
				<p>Upload your logo, as a PNG file, to be shown in the banner. Use the following dimensions: width 210px, height 40px.</p>
				
			</td>
		</tr>
		<tr>
			<th scope="row">
				Your Logo (Preview)
			</th>
			<td>
				<span id="error_logo" class="error-box"></span>
				<img src="<?php echo get_option('CEE-webscanner-logo-file'); ?>" ></img>
			</td>
		</tr>
		<tr>
			<th scope="row">
				Your brand color
			</th>
			<td>
				<input type="text" name="CEE-webscanner-color" value="<?php echo get_option('CEE-webscanner-color'); ?>" size="50"/>
				<p>Insert your brand color (hex color) to be used in the banner and button. </p>
			</td>
		</tr> 
		<tr>
			<th scope="row">
				Background Color
			</th>
			<td>
				<input type="text" name="CEE-webscanner-background" value="<?php echo get_option('CEE-webscanner-backgroundcolor'); ?>" size="50"/>
				<p>Insert the background color as a hex color.</p>
			</td>
		</tr>
		<tr>
			<th  scope="row">
				Start scanning text
			</th>
			<td>
				<input type="text" name="CEE-webscanner-starttext" value="<?php echo get_option('CEE-webscanner-starttext'); ?>" size="50"/>
				<p>Insert your button text here.</p>
			</td>
		</tr>
		<tr>
			<th  scope="row">
				Stop scanning text
			</tdh>
			<td>
				<input type="text" name="CEE-webscanner-stoptext" value="<?php echo get_option('CEE-webscanner-stoptext'); ?>" size="50"/>
				<p>Insert your stop scanning text here which will be shown in the button after initiating scanning.</p>
			</td>
		</tr>
		<tr>
			<th scope="row">
				No Results text
			</th>
			<td>
				<input type="text" name="CEE-webscanner-noresulttext" value="<?php echo get_option('CEE-webscanner-noresulttext'); ?>" size="50"/>
				<p>If there is no scan result in 8 seconds a pop up will be shown to indicate there is has been no result. Insert your custom text here.</p>
			</td>
		</tr>
		
		<!-- Settings for progressive web app -->
		
		<tr>
			<th scope="row" colspan="2">
				<h2>Progressive Web App Settings</h2>
				<p>This allows users to add your scanning page as an 'App' to their phone home page. This allows users to access
				your page directly by pressing the icon, instead of having to type in the full URL.</p>
			</th>
			
		</tr>
		
		<tr>
			<th scope="row">
				Application short name
			</th>
			<td>
				<input type="text" name="CEE-webscanner-pwa-short_name" value="<?php echo get_option('CEE-webscanner-pwa-short_name'); ?>" size="50"/>
				<p>Used when there is insufficient space to display the full name of your website / app. 12 characters or less.</p>
			</td>
		</tr>
		<tr>
			<th scope="row">
				Description
			</th>
			<td>
				<input type="text" name="CEE-webscanner-pwa-description" value="<?php echo get_option('CEE-webscanner-pwa-description'); ?>" size="50"/>
				<p>A brief description of what your website / app is about.</p>
			</td>
		</tr>
		<tr>
			<th scope="row">
				Application Icon 144 x 144 px
				
				
			</th>
			<td>
				<input type="file" name="CEE-webscanner-pwa-icon_144" value="<?php echo get_option('CEE-webscanner-pwa-icon_144'); ?>" size="50"/>
				<p>This will be the icon of your app when installed on the phone. Must be a .png image, exactly 144x144 px in size.</p>
			</td>
		</tr>
		<tr>
			<th scope="row">
				Icon 144x144 (Preview)
			</th>
			<td>
				<span id="error_icon_144" class="error-box"></span>
				<img src="<?php echo get_option('CEE-webscanner-pwa-icon_144'); ?>" ></img>
			</td>
		</tr>
		<tr>
			<th scope="row">
				Application Icon 192 x 192 px
				
				
			</th>
			<td>
				<input type="file" name="CEE-webscanner-pwa-icon_192" value="<?php echo get_option('CEE-webscanner-pwa-icon_192'); ?>" size="50"/>
				<p>This will be the icon of your app when installed on the phone. Must be a .png image, exactly 192x192 px in size.</p>
			</td>
		</tr>
		<tr>
			<th scope="row">
				Icon 192x192 (Preview)
			</th>
			<td>
				<span id="error_icon_192" class="error-box"></span>
				<img src="<?php echo get_option('CEE-webscanner-pwa-icon_192'); ?>" ></img>
			</td>
		</tr>
		
		<tr>
			<th scope="row">
				Application Icon 512 x 512 px
				
				
			</th>
			<td>
				<input type="file" name="CEE-webscanner-pwa-icon_512" value="<?php echo get_option('CEE-webscanner-pwa-icon_512'); ?>" size="50"/>
				<p>This will be the icon of your app when installed on the phone. Must be a .png image, exactly 512x512 px in size.</p>
			</td>
		</tr>
		<tr>
			<th scope="row">
				Icon 512x512 (Preview)
			</th>
			<td>
				<span id="error_icon_512" class="error-box">.</span>
				<img src="<?php echo get_option('CEE-webscanner-pwa-icon_512'); ?>" ></img>
			</td>
		</tr>
		<tr>
			<th scope="row">
				Scanning page 
			</th>
			<td>
				
				<?php 
				
				wp_dropdown_pages(
					array(
						 'name' => 'CEE-webscanner-pwa-start_url',
						 'echo' => 1,
						 'show_option_none' => __( '&mdash; Select &mdash;' ),
						 'option_none_value' => '0',
						 'selected' => get_option('CEE-webscanner-pwa-start_url_id')
					)
				);?>
				
				<p>The page that contains your scanner.</p>
				
			</td>
		</tr>
		
		<tr>
			<td>
				<input type="submit" name="defaultbtn" value="Set Default Settings" class="button button-secondary button-large"/>
			</td>
			
			<td>
				<input type="submit" name="submitbtn" value="Save Settings" class="button button-primary button-large"/>
			</td>
		
		</tr>
		
	
	</table>
	
	</form>
  </div>
  
  
	<div class="settingsManual">
	
		<h1>How to add the web scanner to a page</h1>

		<p>
			To include the scanner, create a new page, and select the template ”Cee Scanner Page”. </br>
			After publishing the page, the scanner should be integrated in the specific page. </br>
			Please make sure the private token is correct and you are scanning with your smartphone.
		</p>
		
		<h1>NOTICE</h1>
		
		<p>
			<ul style="list-style-type:circle;padding-left:20px">
				<li> Your website must have an SSL certificate to make the web scanner work. </li>
				<li>Please make sure that your website starts with 'https://'.</li>
				<li>If you already have a SSL certificate but the page does not automatically switch to https:// then install the following plugin which will resolve this issue https://wordpress.org/plugins/really-simple-ssl/</li>
				<li>The web-based scanner works for Android and IOS smartphones with the latest operating system versions. We cannot guarantee that the code will work with older versions.</li>
			</ul>
		</p>
		


		<p>
		For questions please use the following email: info@cee-platform.com</p>
	</div>
	
		
		
	<!--test-->

 
  
  
  
</div>



