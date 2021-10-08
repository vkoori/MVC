<?php 
namespace kernel\plugins;

/**
 * write common functions or plugins
 */
class common {

	public function arabicToPersian($string) {
		$characters = [
			'ك' => 'ک',
			'دِ' => 'د',
			'بِ' => 'ب',
			'زِ' => 'ز',
			'ذِ' => 'ذ',
			'شِ' => 'ش',
			'سِ' => 'س',
			'ى' => 'ی',
			'ي' => 'ی',
			'١' => '۱',
			'٢' => '۲',
			'٣' => '۳',
			'٤' => '۴',
			'٥' => '۵',
			'٦' => '۶',
			'٧' => '۷',
			'٨' => '۸',
			'٩' => '۹',
			'٠' => '۰',
		];
		return str_replace(array_keys($characters), array_values($characters),$string);
	}

	public function convert_nums($string) {
		$persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
		$arabic = ['٩', '٨', '٧', '٦', '٥', '٤', '٣', '٢', '١','٠'];

		$num = range(0, 9);
		$convertedPersianNums = str_replace($persian, $num, $string);
		$englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

		return $englishNumbersOnly;
	}

	/*public function date_checker($date) {
		return preg_match("/^\d{4}\/(0[1-9]|1[0-2])\/(0[1-9]|[1-2][0-9]|3[0-1])$/", $date);
	}*/

	/*public function gregorian_to_jalali($gy,$gm,$gd,$mod=''){
		$g_d_m=array(0,31,59,90,120,151,181,212,243,273,304,334);
		if($gy>1600){
			$jy=979;
			$gy-=1600;
		}else{
			$jy=0;
			$gy-=621;
		}
		$gy2=($gm>2)?($gy+1):$gy;
		$days=(365*$gy) +((int)(($gy2+3)/4)) -((int)(($gy2+99)/100)) +((int)(($gy2+399)/400)) -80 +$gd +$g_d_m[$gm-1];
		$jy+=33*((int)($days/12053)); 
		$days%=12053;
		$jy+=4*((int)($days/1461));
		$days%=1461;
		if($days > 365){
			$jy+=(int)(($days-1)/365);
			$days=($days-1)%365;
		}
		$jm=($days < 186)?1+(int)($days/31):7+(int)(($days-186)/30);
		$jd=1+(($days < 186)?($days%31):(($days-186)%30));
		return($mod=='')?array($jy,$jm,$jd):$jy.$mod.$jm.$mod.$jd;
	}*/

	public function jalali_to_unix($date, $separator='/'){
		[$jy, $jm, $jd] = explode($separator, $date);
		$g_date = $this->jalali_to_gregorian($jy,$jm,$jd,'-');
		$dateTime = new \DateTime($g_date); 
		return $dateTime->format('U'); 
	}

	public function jalali_to_gregorian($jy,$jm,$jd,$mod=''){
		if($jy>979){
			$gy=1600;
			$jy-=979;
		}else{
			$gy=621;
		}
		$days=(365*$jy) +(((int)($jy/33))*8) +((int)((($jy%33)+3)/4)) +78 +$jd +(($jm<7)?($jm-1)*31:(($jm-7)*30)+186);
		$gy+=400*((int)($days/146097));
		$days%=146097;
		if($days > 36524){
			$gy+=100*((int)(--$days/36524));
			$days%=36524;
			if($days >= 365)$days++;
		}
		$gy+=4*((int)($days/1461));
		$days%=1461;
		if($days > 365){
			$gy+=(int)(($days-1)/365);
			$days=($days-1)%365;
		}
		$gd=$days+1;
		foreach(array(0,31,(($gy%4==0 and $gy%100!=0) or ($gy%400==0))?29:28 ,31,30,31,30,31,31,30,31,30,31) as $gm=>$v){
			if($gd<=$v)break;
			$gd-=$v;
		}
		return($mod=='')?array($gy,$gm,$gd):$gy.$mod.$gm.$mod.$gd; 
	}

	/*public function ConvertToJalali($time) {
		date_default_timezone_set("UTC");
		$dt = new \DateTime('@'.$time);
		// $dt->setTimeZone(new \DateTimeZone(TZ));
		$year = $dt->format('Y');
		$month = $dt->format('m');
		$day = $dt->format('d');
		$jalali = $this->gregorian_to_jalali($year,$month,$day);
		$date = "(".$dt->format('H:i').") ".$jalali[0]."/".$jalali[1]."/".$jalali[2];
		return $date;
	}*/

	/*function redirect($url, $permanent = false) {
		header('Location: ' . $url, true, $permanent ? 301 : 302);
		exit();
	}*/

	/**
	 * upload file into $imageFolder directory
	 * check conditions for uploading
	 * @return array
	 */
	public function upload_single($temp,$imageFolder,$extension=array("gif", "jpg", "jpeg", "png"),$max_s=2097152,$is_image=false,$max_w=2048,$max_h=2048) {

		$result = array(
			"status" => 0,
			"body" => ""
		);

		/*********************************************
		* Check temp exist *
		*********************************************/
		if (is_uploaded_file($temp['tmp_name'])){

			/*
			  If you needs to receive cookies, set images_upload_credentials : true in
			  the configuration and enable the following two headers.
			*/
			// header('Access-Control-Allow-Credentials: true');
			// header('P3P: CP="There is no P3P policy."');

			// Sanitize input
			if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
				$result = array(
					"status" => 400,
					"body" => "invalid_filename"
				);
				return $result;
			}

			// Verify extension
			if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), $extension)) {
				$result = array(
					"status" => 400,
					"body" => "invalid_extension"
				);
				return $result;
			}

			// check file 
			if ($temp["size"] == 0) {
				$result = array(
					"status" => 400,
					"body" => "file_not_choose"
				);
				return $result;
			}

			// Check file max size
			if ($temp["size"] > $max_s) {
				$result = array(
					"status" => 400,
					"body" => "limit_size"
				);
				return $result;
			}

			// check iamge dimension
			if ($is_image) {
				$check = getimagesize($temp["tmp_name"]);
				if($check[0] > $max_w && $check[1] > $max_h){
					$result = array(
						"status" => 400,
						"body" => "limit_dimension"
					);
					return $result;
				}
			}

			// Check if file already exists
			$img_name = $temp['name'];
			$index = 1;
			while (file_exists($imageFolder.$img_name)) {
				$img_name = pathinfo($temp['name'], PATHINFO_FILENAME)."-$index.".pathinfo($temp['name'], PATHINFO_EXTENSION);
				$index ++;
			}

			// upload file
			move_uploaded_file($temp['tmp_name'], $imageFolder.$img_name);

			// Respond to the successful upload with JSON.
			// Use a location key to specify the path to the saved image resource.
			// { location : '/your/uploaded/image/file'}
			$loc = new \stdClass;
			$loc->location = $img_name;
			$result = array(
				"status" => 200,
				"body" => $loc
			);
			return $result;
		} else {
			// Notify editor that the upload failed
			$result = array(
				"status" => 500,
				"body" => "upload_failed"
			);
			return $result;
        }

    }


    /**
     * resize image and save it
     * @return bool
     */
    public function resizeimages ($file, $save , $size) {
        if (!file_exists(dirname($save))) {
            mkdir(dirname($save), 0777, true);
        }

        list($width, $height) = getimagesize($file) ;
        $modwidth = $size;
        $diff = $width / $modwidth;
        $modheight = $height / $diff;
        $tn = imagecreatetruecolor($modwidth, $modheight) ;
        imagealphablending($tn, false);
        imagesavealpha($tn, true);

        switch ( strtolower(pathinfo( $file, PATHINFO_EXTENSION )) ) {
            case 'jpeg':
            case 'jpg':
                $image = imagecreatefromjpeg($file) ;
                imagecopyresampled($tn, $image, 0, 0, 0, 0, $modwidth, $modheight, $width, $height) ;
                imagejpeg($tn, $save, 100) ;
            break;

            case 'png':
                $image = imagecreatefrompng($file);
                imagecopyresampled($tn, $image, 0, 0, 0, 0, $modwidth, $modheight, $width, $height) ;
                imagepng($tn, $save, 4) ;
            break;

            case 'gif':
                $image = imagecreatefromgif($file);
                imagecopyresampled($tn, $image, 0, 0, 0, 0, $modwidth, $modheight, $width, $height) ;
                imagegif($tn, $save) ;
            break;

            default:
                throw new Exception('File "'.$filename.'" is not valid jpg, png or gif image.');
            break;
        }
        return true;
    }

}