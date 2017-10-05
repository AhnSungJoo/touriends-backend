<?php
namespace Touriends\Backend\AJAX;
use Touriends\Backend\User;
class Like extends Base
{
    public static function init()
    {
        parent::registerAction('bookMark', [__CLASS__, 'bookMark']);
        parent::registerAction('getBookMark', [__CLASS__, 'getBookMark']);
        parent::registerAction('search', [__CLASS__, 'search']);
    }
    /*
      like 좋아요 기능 구현 -> 즐겨찾기로 생각
    */
    public static function bookMark () {
        $like_id = $_POST['like']; //like변수에 like를 누른 user의 아이디를 받아온다(email 형식 ex_ "test@test.com")
        $user_id = User\Utility::getCurrentUser()->ID; //현재 user_id
        add_user_meta($user_id, 'user_like', $like_id ); //db에 추가한다.
        die(json_encode([
          'success'  => true
        ]));
    }
    public static function getBookMark () {
        $user_id = User\Utility::getCurrentUser()->ID;
        $ret_like = get_user_meta($user_id,'user_like'); //현재 user_id에 like 목록을 받아온다.
        die(json_encode([
          'success'  => true,
          'like' => $ret_like
        ]));
    }
    public static function search () {
        global $wpdb; //필요한가 ?
        $content = $_POST['content']; //검색어
        $user_id = User\Utility::getCurrentUser()->ID; //현재 user_id
        $result = []; //검색어가 포함되어 있는 결과를 보내줄 배열
        $ret = get_user_meta($user_id,'user_like'); //현재의 아이디가 가지고 있는 좋아요 ID(email형식)을 가져온다
        foreach($ret as $comp){ 
          if(strpos($comp, $content) !== false) { //strpos는 문자열을 검색해주는 기능이다 ex) 검색어 "lik" 이면 "like"가 존재하면 true 반환
            $tour_login = $comp;
            $tour_info = get_user_by('login',$tour_login); //email형식으로 받아온 id 정보를 uid(숫자)로 바꾸기 위한 몸부림
            $tour_id = $tour_info -> ID;//email형식으로 받아온 id 정보를 uid(숫자)로 바꾸기 위한 몸부림
            $theme = get_user_meta($tour_id, 'user_theme', true);
            $languages = get_user_meta($tour_id, 'user_language');
            $image = User\Utility::getUserImageUrl($tour_id);
            $intro = get_user_meta($tour_id, 'user_intro', true);
            $birth = get_user_meta($tour_id, 'user_birth',true);
            $result[] = [
              'uid'       => $tour_id,
              'age'       => $birth,
              'theme'     => $theme,
              'languages' => $languages,
              'image'     => $image,
              'intro'     => $intro
            ];
          }
        }
        die(json_encode([
          'success'  => true,
          'search' => $result
        ]));
    }
}
