<?php


	class bannerGenerator
	{
		function start($db,$ts,$config,$ft,$cache)
		{
			$i = 0;
			foreach($cache['clientList'] as $clientList)
			{
				if($clientList['client_type'] == 0)
				{
					if(array_intersect($config['functions']['bannerGenerator']['admin_groups'], explode(',',$clientList['client_servergroups'])))
					{
						$i++;
					}
				}
			}

			$serverInfo = $ts->getElement('data',$ts->serverInfo());

			$image = imagecreatefrompng($config['functions']['bannerGenerator']['img_for_create']);
			imagealphablending($image, true);
			$white = imagecolorallocate($image,255,255,255);
			self::imagettftextCenter($image, $config['functions']['bannerGenerator']['banner']['time'][0], 0, $config['functions']['bannerGenerator']['banner']['time'][1], $config['functions']['bannerGenerator']['banner']['time'][2], $white , $config['functions']['bannerGenerator']['font_banner'], date('H:i'));
			self::imagettftextCenter($image, $config['functions']['bannerGenerator']['banner']['online'][0], 0, $config['functions']['bannerGenerator']['banner']['online'][1], $config['functions']['bannerGenerator']['banner']['online'][2], $white , $config['functions']['bannerGenerator']['font_banner'], $serverInfo['virtualserver_clientsonline']-$serverInfo['virtualserver_queryclientsonline']);
			self::imagettftextCenter($image, $config['functions']['bannerGenerator']['banner']['admins'][0], 0, $config['functions']['bannerGenerator']['banner']['admins'][1], $config['functions']['bannerGenerator']['banner']['admins'][2], $white , $config['functions']['bannerGenerator']['font_banner'], $i);

			imagepng($image,$config['functions']['bannerGenerator']['save_banner']);
			imagedestroy($image);
			unset($clientList,$i);
		}


		function imagettftextCenter(&$im, $size, $angle, $x, $y, $color, $fontfile, $text)
        {
            // retrieve boundingbox
            $bbox = imagettfbbox($size, $angle, $fontfile, $text);
            
            // calculate deviation
            $dx = ($bbox[2]-$bbox[0])/2.0 - ($bbox[2]-$bbox[4])/2.0;         // deviation left-right
            $dy = ($bbox[3]-$bbox[1])/2.0 + ($bbox[7]-$bbox[1])/2.0;        // deviation top-bottom
            
            // new pivotpoint
            $px = $x-$dx;
            $py = $y-$dy;
            
            return imagettftext($im, $size, $angle, $px, $py, $color, $fontfile, $text);
        }

	}