<?php
namespace Touriends\Backend\AJAX;
use Touriends\Backend\Table;
use Touriends\Backend\User;
class Testsj extends Base {
    public static function init() {
        parent::registerAction('testsj', [__CLASS__, 'testsj']);
    }
    public static function testsj() {
       
        $a = "타이틀";
        $b = "https://search.pstatic.net/common/?src=http%3A%2F%2Fldb.phinf.naver.net%2F20150831_194%2F1440997727456lEljl_GIF%2F11571707_0.gif&type=l&size=3072x1512&quality=95&autoRotate=true";
        $c = "ad";
       
        
        	$rrr = array(array(
					'title'   => $a,
					'url'    => $b,
					'addr' => $c
		));
        	$rrr.push(array('title'   => "Gyeongbokgung",
				'url'    => "http://korean.visitseoul.net/comm/getImage?srvcId=MEDIA&parentSn=167&fileTy=MEDIA&fileNo=1&thumbTy=L",
				'addr' => "Jongno")
			 );
        die(json_encode([
            'success'  => true,
            'data' => $rrr
            ]));
        }
}
