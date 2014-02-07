<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "hillock");
$wechatObj = new wechatCallbackapiTest();
$wechatObj->index();

//include 'SimpleImage.php';

class wechatCallbackapiTest
{

	public function index()
	{
		if ( $_SERVER['REQUEST_METHOD']=="POST") 
		{ 
			$this->responseMsg();
		} 
		else if ($_SERVER['REQUEST_METHOD'] == "GET") 
		{ 
			$this->valid();
		} 
		else 
		{ 
		// OTHER 
		}
	
	}
	
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
			$MsgType = $postObj->MsgType;
            $time = time();
			$contentStr = "对不起，我们只能够处理图片。";
			$textTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType><![CDATA[%s]]></MsgType>
						<Content><![CDATA[%s]]></Content>
						<FuncFlag>0</FuncFlag>
						</xml>";  
			if(!strcasecmp("image",$MsgType))
			{
				$url = trim($postObj->PicUrl);
				$filename = $fromUsername;
				$this->getImg($url,$filename);  
				$contentStr = "图片已经上墙\r请登录www.pper.com.cn找找你的图片在那块砖上。";
			}
			else
			{
				
			}
			//回复消息
			$msgType = "text";
			$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
			echo $resultStr;
        }else {
        	echo "";
        	exit;
        }
    }
		
	private function checkSignature()
	{
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];	
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
	
	//获得图片同时保存缩略图
	public function getImg($url,$filename)
	{
		if(is_dir(basename($filename))) {
			echo "The Dir was not exits";
			Return false;
		}
		//去除URL连接上面可能的引号
		$url = preg_replace( '/(?:^[\'"]+|[\'"\/]+$)/', '', $url );

		/*
		*filename:文件名
		*pathPhoto：图片目录
		*pathPhotoResize：缩略图路径
		*/
		$pathPhoto = './photo/';
		$pathPhotoResize = './photoResize/';
		
		list($msec,$sec) = explode ( " ", microtime () );  
		$seq = str_replace('0.','~',$msec);

		$filename .= '-'.date('YmdHis',$sec). $seq.'.jpg';
		
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

		$this->resizeImg($filename,$pathPhoto,$pathPhotoResize,120,160);
		
		//生成缩略图		
		Return true;
	}
		/*-----------等比例缩略图----------------------------
		*filename 图片名称
		*srcDir 源图片所在的文件夹
		*disDir 保存的文件夹
		*distWidth 目标宽度
		*distHeight 目标高度
		---------------------------------------------------*/
	public function resizeImg($filename,$srcDir,$disDir,$distWidth,$distHeight)
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

function logger($content)
{
	$debug = print_r($content,true);
	//$debug = var_export($content,true);
    file_put_contents("/home/wwwroot/hillock/log.html", date('Y-m-d H:i:s  ').$debug."<br>", FILE_APPEND);
}

?>