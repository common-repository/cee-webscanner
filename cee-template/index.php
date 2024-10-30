
<link rel="manifest" href="<?php echo get_site_url().'/cee_manifest.json';?>">

<?php

//load styles the wordpress way by using correct hook. Here, wp_enqueue_style does not work, need to use admin print styles hook.
function load_cee_styles() {

        wp_enqueue_style('cee-style', plugins_url('cee-template/css/style.css',CEE_PLUGIN_NAME));
}

add_action('admin_print_styles', 'load_cee_styles');

do_action('admin_print_styles');


//load scripts the wordpress way
function load_cee_scripts() {

        wp_enqueue_script('cee-craftar', plugins_url('cee-template/js/craftar.min.js',CEE_PLUGIN_NAME));
		wp_enqueue_script('cee-main', plugins_url('cee-template/js/main.js',CEE_PLUGIN_NAME));
}

// add action but only call later on in the file.
add_action('admin_print_scripts', 'load_cee_scripts');

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Webscanner</title>
    <meta charset="UTF-8" />
    <link rel="shortcut icon" href="<?php echo plugins_url('cee-template/images/scan-icon-black.png');?>">
    <meta name="viewport" content="width=device-width, user-scalable=no">
	
	<script type="text/javascript">
		var cee_token = "<?php echo get_option('CEE-webscanner-token'); ?>";
		var cee_color = "<?php echo get_option('CEE-webscanner-color'); ?>";
		var cee_startScan = "<?php echo get_option('CEE-webscanner-starttext'); ?>";
		var cee_stopScan = "<?php echo get_option('CEE-webscanner-stoptext'); ?>";
		var cee_noResults = "<?php echo get_option('CEE-webscanner-noresulttext'); ?>";
		var cee_backgroundColor = "<?php echo get_option('CEE-webscanner-backgroundcolor'); ?>";
		
		function setCustomSettings(){
			//color
			document.getElementById("scan").style.backgroundColor = cee_color;
			document.getElementById("header").style.backgroundColor = cee_color;
			document.body.style.background = cee_backgroundColor;
			
			
			//starttext
			document.getElementById("scan").innerHTML = cee_startScan;
		}
		
	</script>
	
	<?php 
		//load scripts craftar and main js
		do_action('admin_print_scripts');
	?>
    
	
</head>

<body>


	
		<header id="header">
			<h1>
				<a href="http://cee-platform.com" target="blank">
					<img class="headerLogo centerImage" src="<?php echo get_option('CEE-webscanner-logo-file'); ?>" alt="Logo catchoom">
				</a>
			</h1>
		</header>
		
		<div class="container">
			<div id="videoCapture">

			</div>

			<div id="scan-button-container">
				<div class="spinner hidden" id="spinner"><img class="centerImage" src="<?php echo plugins_url('cee-webscanner/cee-template/images/spinner.gif');?>"></div>
				<div id="scan">Start scanning</div>
			</div>
		</div>
	
</body>

<script>setCustomSettings();

// Register the service worker if available.
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('<?php echo get_site_url().'/cee_serviceworker.js';?>').then(function(reg) {
        console.log('Successfully registered service worker', reg);
    }).catch(function(err) {
        console.warn('Error whilst registering service worker', err);
    });
}
</script>

</html>