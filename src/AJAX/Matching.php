<?php
namespace Touriends\Backend\AJAX;
class Matching extends Base {
    public static function init() {
        parent::registerAction('getMatching', [__CLASS__, 'getMatching']);
    }
    public static function getMatching() {
        global $wpdb;
        $user_id  = get_current_user_id();
        $user_language = get_user_meta($user_id,'user_language',true);
        $user_theme = get_user_meta($user_id,'user_theme',true);
        $user_fromDate =  get_user_meta($user_id,'user_fromDate',true);
        $user_toDate =  get_user_meta($user_id,'user_toDate',true);
        $cnt_lang = $wpdb->get_var("SELECT count(DISTINCT user_id) FROM $wpdb->usermeta WHERE (meta_value = '$user_language')");
        $cnt_theme =  $wpdb->get_var("SELECT count(DISTINCT user_id) FROM $wpdb->usermeta WHERE (meta_value = '$user_theme')");
        $ret_language = $wpdb->get_results("SELECT DISTINCT user_id FROM $wpdb->usermeta WHERE (meta_value = '$user_language')",ARRAY_N);
        $ret_theme = $wpdb->get_results("SELECT DISTINCT user_id FROM $wpdb->usermeta WHERE (meta_value = '$user_theme')",ARRAY_N);
        var_dump($ret_language);
        // result의 id를 보면서 from - to 까지 보이게 한다

        date_default_timezone_set('Asia/Seoul');

        #매칭을 한 사용자의 출발일 도착일
        $tour_id = $ret_language[0];
        $tour_from = get_user_meta($tour_id,'user_fromDate',true);
        $tour_to = get_user_meta($tour_id,'user_toDate',true);
        update_user_meta($tour_id, 'diff1', $user_fromDate);
        update_user_meta($tour_id, 'diff2', $tour_id);
        for($idx =0; $idx<$cnt_lang; $idx++){
          $tour_id = $ret_language[$idx];
          $tour_fromDate = get_user_meta($tour_id,'user_fromDate',true);
          $tour_toDate = get_user_meta($tour_id,'user_toDate',true);

          #검색될 내용
          $src1 = $user_fromDate;
          $dst1 = $user_toDate;
          $src2 = $tour_fromDate;
          $dst2 = $tour_toDate;
          $srcdate1 = date_create($src1);
          $dstdate1 = date_create($dst1);
          $srcdate2 = date_create($src2);
          $dstdate2 = date_create($dst2);
          update_user_meta($user_id, 'src1', $srcdate1);
          update_user_meta($user_id, 'date1', $dstdate1);
          update_user_meta($user_id, 'src2', $srcdate2);
          update_user_meta($user_id, 'date2', $dstdate2);
          if($srcdate1>$dstdate2 || $srcdate2 > $dstdate1){#안 겹치는 case
                  update_user_meta($user_id, 'matching_test', 0);
          }
          else if($srcdate1>$srcdate2 && $dstdate1>$dstdate2){#1번 case
                  update_user_meta($user_id, 'matching_test', date_diff($srcdate1,$dstdate2)->days+1);
          }
          else if($srcdate2>$srcdate1 && $dstdate2>$dstdate1){#2번 case
                  update_user_meta($user_id, 'matching_test', date_diff($srcdate2,$dstdate1)->days+1);
          }
          else if($srcdate1>$srcdate2 && $dstdate2>$dstdate1){#3번 case
                  update_user_meta($user_id, 'matching_test', date_diff($srcdate1,$dstdate1)->days+1);
          }
          else{#4번 case
                  update_user_meta($user_id, 'matching_test', date_diff($srcdate2,$dstdate2)->days+1);
          }

        }


    }
  }
