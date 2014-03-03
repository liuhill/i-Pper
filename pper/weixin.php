<?php
/**
  * wechat php test
  */

//define your token
//define("TOKEN", "hillock");
require_once 'config.php';
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
			$textTpl = "";
			$resultStr = "";

			if(!strcasecmp("image",$MsgType))
			{

				$textTpl = 	"<xml>
								<ToUserName><![CDATA[%s]]></ToUserName>
								<FromUserName><![CDATA[%s]]></FromUserName>
								<CreateTime>%s</CreateTime>
								<MsgType><![CDATA[news]]></MsgType>
								<ArticleCount>2</ArticleCount>
								<Articles>
									<item>
										<Title><![CDATA[%s]]></Title> 
										<Description><![CDATA[%s]]></Description>
										<PicUrl><![CDATA[%s]]></PicUrl>
										<Url><![CDATA[%s]]></Url>	
									</item>
									<item>
										<Title><![CDATA[%s]]></Title>
										<Description><![CDATA[%s]]></Description>
										<PicUrl><![CDATA[%s]]></PicUrl>
										<Url><![CDATA[%s]]></Url>
									</item>
								</Articles>
							</xml> ";
							

				$url = trim($postObj->PicUrl);
				try   
				{
					global $photoObj;
					$imgName = $photoObj->savePhoto($url,$fromUsername);  
				}
				catch(Exception $e)
				{
					logger($e->getMessage());
				}
				
				$titleStr1 = "照片已经上墙，手机用户请在wifi下浏览或者登陆www.pper.com.cn。";
				$Description1 = "查看3D效果请用chrome(谷歌)或者firefox(火狐)登录www.pper.com.cn。";
				$picUrl1 = "http://www.pper.com.cn/img/3dShow.jpg";  
				$webUrl1 = "http://www.pper.com.cn";
				
				$titleStr2 = "你刚刚在墙上粘贴的照片";
				$Description2 = "请登录www.pper.com.cn找找你的图片在那块砖上。";
				$picUrl2 = "http://www.pper.com.cn/photoResize/";		//说略图目录
				$picUrl2 .= $imgName;
//				$webUrl = "http://www.baidu.com/img/bdlogo.gif";
				$webUrl2	= "http://www.pper.com.cn/photo/".$imgName;
				
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time,$titleStr1,$Description1,$picUrl1,$webUrl1,$titleStr2,$Description2,$picUrl2,$webUrl2);

			}
			elseif(!strcasecmp("event",$MsgType))
			{
				$event =  $postObj->Event;
				if(!strcasecmp("subscribe",$event))
				{
					//订阅用户，发送欢饮消息
					$textTpl = 	"<xml>
									<ToUserName><![CDATA[%s]]></ToUserName>
									<FromUserName><![CDATA[%s]]></FromUserName>
									<CreateTime>%s</CreateTime>
									<MsgType><![CDATA[news]]></MsgType>
									<ArticleCount>1</ArticleCount>
									<Articles>
										<item>
											<Title><![CDATA[%s]]></Title> 
											<Description><![CDATA[%s]]></Description>
											<PicUrl><![CDATA[%s]]></PicUrl>
											<Url><![CDATA[%s]]></Url>	
										</item>
									</Articles>
								</xml> ";
								

					$titleStr1 = "欢迎加入拍拍客，拍点什么吧~~";
					$Description1 = "我们喜欢随手拍一拍,大多数照片没有特殊意义，偶尔也会看一看。我们是——————拍拍客！(www.pper.com.cn)";
					$picUrl1 = "http://www.pper.com.cn/img/3dShow.jpg";  
					$webUrl1 = "http://www.pper.com.cn";					
					$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time,$titleStr1,$Description1,$picUrl1,$webUrl1);
				}
			}			
			else
			{

				//只接收图片消息
				$textTpl = "<xml>
				<ToUserName><![CDATA[%s]]></ToUserName>
				<FromUserName><![CDATA[%s]]></FromUserName>
				<CreateTime>%s</CreateTime>
				<MsgType><![CDATA[%s]]></MsgType>
				<Content><![CDATA[%s]]></Content>
				<FuncFlag>0</FuncFlag>
				</xml>";  


				$contentStr = "对不起，我们只能够处理图片。";
				$msgType = "text";
				$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
			}
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
	

}


?>