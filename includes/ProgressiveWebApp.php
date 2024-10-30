

<?php

class ProgressiveWebApp{

	private $filename_manifest;
	private $filename_serviceworker;
	

	function __construct(){
	
		$this -> filename_manifest = "cee_manifest.json";
		$this -> filename_serviceworker = "cee_serviceworker.js";
	}
		
	/*
	Only function needed to call.
	
	@param $data: contains all data for the manifest to include. 
	*/
	public function makeProgressiveWebApp($data){
		
		try {
			
			$this -> saveManifest($data);
			$this -> saveServiceWorker();
			
		} catch (Exception $e){
			return false;
		}
		
		return true;
	}
	
	private function generateManifestContent($data){
	
		$manifestContent = array(
						"name" => $data["description"],
						"short_name" => $data["short_name"],
						"description" => $data["description"],
						
						"icons" => array(
											array(
												"src" => $data["icon_144"],
												"sizes" => "144x144",
												"type"=> "image/png"
												),
											array(
												"src" => $data["icon_192"],
												"sizes" => "192x192",
												"type"=> "image/png"
												),
											array(
												"src" => $data["icon_512"],
												"sizes" => "512x512",
												"type"=> "image/png"
												)
											
										),
						
						"background_color" => $data["background_color"],
						"theme_color" => $data["theme_color"],
						"display" => "standalone",
						"orientation" => "portrait",
						"start_url" => $data["start_url"],
						"scope" => "/"
						
						);

		return $manifestContent;
	}
		
	private function generateServiceWorkerContent(){
		$serviceWorkerContent = "self.addEventListener('fetch', function(event) {});";
		
		return $serviceWorkerContent;
	}
	
	private function saveManifest($data){
		
		$manifestContent = $this -> generateManifestContent($data);
		
		try {
			$fp = fopen(ABSPATH.$this->filename_manifest, 'w');
		
			fwrite($fp, json_encode($manifestContent));
			fclose($fp);
			
			return true;
			
		} catch (Exception $e){
			return false;
		}
	}
		
	private function saveServiceWorker(){
		
		$serviceWorkerContent = $this -> generateServiceWorkerContent();
		
		try {
			$fp = fopen(ABSPATH.$this->filename_serviceworker, 'w');
		
			fwrite($fp, $serviceWorkerContent);
			
			fclose($fp);
			
			return true;
			
		} catch (Exception $e){
			return false;
		}
	}
		 
		
}


?>