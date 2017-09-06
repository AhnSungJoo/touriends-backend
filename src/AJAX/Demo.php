<?php

namespace Touriends\Backend\AJAX;

class Demo extends Base {
    public static function init() {
        parent::registerAction('login', [__CLASS__, 'login']);
        parent::registerAction('register', [__CLASS__, 'register']);
        parent::registerAction('logout', [__CLASS__, 'logout']);
    }

    public static function login() {
        $login = $_REQUEST['login'];
        $pwd   = $_REQUEST['pwd'];

        if (is_user_logged_in()) {
            wp_logout();
        }
        $user = wp_signon([
            'user_login'    => $login,
            'user_password' => $pwd,
            'remember'      => true
        ], false);

        $success = is_wp_error($user) ? false : true;
        if (! $success) {
            die(json_encode([
                'success' => false,
                'message' => '로그인 실패!'
            ]));
        }

        die(json_encode([
            'success' => $success,
            'uid'     => $user->ID
        ]));
    }

    public static function register() {
        $login = $_REQUEST['login'];
        $pwd   = $_REQUEST['pwd'];

        if (get_user_by('login', $login)) {
            die(json_encode([
                'success' => false
            ]));
        }

        wp_insert_user([
            'user_pass'  => $pwd,
            'user_login' => $login
        ]);
        die(json_encode([
            'success' => true
        ]));
    }

    public static function logout() {
        wp_logout();
        die(json_encode([
            'success' => true
        ]));
    }
}