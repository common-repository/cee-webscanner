<?php

/*
* Upload manager for uploading images to wordpress
*/




class UploadManager{
	
	/*
	* Upload manager for uploading images.
	* Example use:
	* First initiate new class:
	*	$uploadManager = new UploadManager();
	* Then call the function to upload the image from a $_FILES['form-input-name']
	*	$resultArray = $uploadManager -> uploadImage($_FILES['user_thumbnail']);
	*Returns an array with associative attributes 'success' (boolean), 'message' (error message if success is false), and 'filepath' (full
	*	filepath to the uploaded file)
	*/
	
	
	private $allowedFileExtensions;
	private $uploadDirectory;
	
	
	
	public function __construct($uploadDir){
		
		$this -> allowedFileExtensions = array('gif', 'png', 'jpg', 'jpeg');
		$this -> uploadDirectory = $uploadDir;
		
	}
	
	
	private function checkImageSize($uploadFile){
		/*
		checks whether the image has been received correctly. Mostly when tmp_name is empty, it is because the size is too big.
		returns boolean.
		
		Takes in the $_FILES['user_thumbnail'] argument, basically the array returned from $_FILES
		*/
		
		if ($uploadFile['tmp_name'] == ""){
			//echo '<p style="color:red">*image file is too big, please select an image smaller than 4mb</p>';
			return false;
		}
		
		return true;
	}
	
	
	private function checkImageFileType($uploadFile){
		
		$filename = strtolower($uploadFile['name']);
		$ext = pathinfo($filename)['extension'];
		
		if(!in_array($ext, $this -> allowedFileExtensions) ) {
			return false;
		}
		
		return true;
	}
	
	private function checkImageDimensions($uploadImage, $targetWidth, $targetHeight){
		
		$image_info = false;

		
		try {
			$image_info = getimagesize($uploadImage["tmp_name"]);
			$image_width = $image_info[0];
			$image_height = $image_info[1];
			
			
		} catch (Exception $e) {
			
			return false;
			
		}
		
		
		if($image_info != false){
			if($image_width == $targetWidth && $image_height == $targetHeight){
				return true;
			}
		}
		
		
	}

	public function uploadImage($uploadImage, $imageWidth, $imageHeight){
		
		/*
		* Takes in the $_FILES['inputformname'] structure array, and returns an array with associative attributes 'success', 'message', and 'filepath' of
		* uploaded image.
		*/
		
		$returnValue = array('success', 'message', 'filepath');
		
		if ($this -> checkImageSize($uploadImage) == false){
			$returnValue['success'] = false;
			$returnValue['message'] = "Image is too big.";
			
		} elseif ($this -> checkImageFileType($uploadImage) == false){
			$returnValue['success'] = false;
			$returnValue['message'] = "Image filetype is not accepted.";
		} elseif ($this -> checkImageDimensions($uploadImage,$imageWidth,$imageHeight) == false){
			
			$returnValue['success'] = false;
			$returnValue['message'] = "The image dimensions are not correct.";
			
		} else {
		
			$relativedir = $this -> uploadDirectory;
			$uploaddir = $relativedir;
			
			
			$file = $uploaddir . basename($uploadImage['name']); 
			$raw_file_name = $uploadImage['tmp_name'];
			
			if (move_uploaded_file($uploadImage['tmp_name'], $file)) { 
				//echo "success"; 
				//return $relativedir . basename($_FILES['user_thumbnail']['name']); //to work on local machine
				$returnValue['success'] = true;
				$returnValue['filepath'] = basename($uploadImage['name']);
				
			} else {
				//echo "error";
				$returnValue['success'] = false;
				$returnValue['message'] = "Something went wrong uploading the image.";
				
			}
		}
		
		return $returnValue;
	}
}



?>