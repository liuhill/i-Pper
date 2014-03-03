<?php
/*
*	照片处理
*/

class photo
{

	/*
	*获得指定文件夹下所有图片名称
	*参数：
	*	photoDir:图片文件夹路径
	*	max：获得图片最大数量
	*	photoDate:图片生成时间
	*返回值：
	*	返回数组。包括图片的名称、文件大小、最近修改日期。
	*/
	
	public function getPhotos($dirName,$max,$photoDate = null)
	{
//		logger("getPhotos------------1");	
		$photoDir = opendir('./'.$dirName); //当前目录
		$i = 0;
		$files = array();
		//$file
		while (false !== ($file = readdir($photoDir)) ){//&& $i < 108) { //遍历该php文件所在目录
			list($filesname,$kzm)=explode(".",$file);//获取扩展名
			if($kzm=="gif" or $kzm=="jpg" or $kzm=="JPG" or $kzm=="png" or $kzm=="PNG") { //文件过滤
			  if (!is_dir("./".$file)) { //文件夹过滤
				$files[$i]["name"] = $file;//获取文件名称
				$files[$i]["size"] = round((filesize($dirName .'/'.$file)/1024),2);//获取文件大小
				$files[$i]["time"] = date("Y-m-d H:i:s",filemtime($dirName .'/'.$file));//获取文件最近修改日期
				$i++;//记录图片总张数
			   }
			  }
		}
		closedir($photoDir);

		foreach($files as $k=>$v){
			$size[$k] = $v['size'];
			$time[$k] = $v['time'];
			$name[$k] = $v['name'];
		}

		array_multisort($time,SORT_DESC,SORT_STRING, $files);//按时间排序
		
		if(count($files) > $max)  
			$photos = array_slice($files,0,$max);
		else
			$photos = $files;
			
		return $photos;
	}
	
	
	//获得图片同时保存缩略图
	//返回文件名
	public function savePhoto($url,$fromUsername)
	{
		logger("savePhoto------------1");	

		if(is_dir(basename($fromUsername))) {
			echo "The Dir was not exits";
			Return false;
		}
		//去除URL连接上面可能的引号
		$url = preg_replace( '/(?:^[\'"]+|[\'"\/]+$)/', '', $url );
		logger("savePhoto------------2");	
		
		//*filename:文件名
		//*pathPhoto：图片目录
		//*pathPhotoResize：缩略图路径
		//
		$pathPhoto = './'.PHOTO_DN.'/';
		$pathPhotoResize = './'.PHOTORZ_DN .'/';
		logger('pathPhoto-------'.$pathPhoto.'-----');		
		list($msec,$sec) = explode ( " ", microtime () );  
		$seq = str_replace('0.','~',$msec);

		$filename = date('Ymd~His',$sec). $seq.'-'.$fromUsername.'.jpg';
		
		$hander = curl_init();
		$fp = fopen($pathPhoto.$filename,'wb');

		curl_setopt($hander,CURLOPT_URL,$url);
		curl_setopt($hander,CURLOPT_FILE,$fp);
		curl_setopt($hander,CURLOPT_HEADER,0);
		curl_setopt($hander,CURLOPT_FOLLOWLOCATION,1);
	  //curl_setopt($hander,CURLOPT_RETURNTRANSFER,false);//以数据流的方式返回数据,当为false是直接显示出来
		curl_setopt($hander,CURLOPT_TIMEOUT,60);

		curl_exec($hander);
		curl_close($hander);
		fclose($fp);

		$this->resizePhoto($filename,$pathPhoto,$pathPhotoResize,120,160);
		
		//生成缩略图		
		Return $filename;
	}
	
	/*-----------等比例缩略图----------------------------
	*filename 图片名称
	*srcDir 源图片所在的文件夹
	*disDir 保存的文件夹
	*distWidth 目标宽度
	*distHeight 目标高度
	*---------------------------------------------------*/
	public function resizePhoto($filename,$srcDir,$disDir,$distWidth,$distHeight)
	{
		// Content type
		//header('Content-type: image/jpeg');
		// Get new dimensions
		list($width, $height) = getimagesize($srcDir.$filename);
		$percent = 1;
		if($width /$height >= $distWidth/$distHeight)
			{
				
				if($width > $distWidth)
				{
					$percent = $distWidth/$width;
				}
			} 
			else
			{
				if($height > $distHeight)
				{
					$percent = $distHeight/$height;
				}
			}
		$new_width = $width * $percent;
		$new_height = $height * $percent;
		//创建新的图片此图片的标志为$image_p
		$image_p = imagecreatetruecolor($new_width, $new_height);
		$image = imagecreatefromjpeg($srcDir.$filename);
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
		
		// Output
		imagejpeg($image_p, $disDir.$filename, 100);//quality为图片输出的质量范围从 0（最差质量，文件更小）到 100（最佳质量，文件最大）。
	}

}

	$photoObj = new photo();


?>