<?php
use Dwoo\Core;
use Jenssegers\Blade\Blade;
use IO\Framework\Loader;

function get_constant($n) {
    require ( BASE_PATH . 'app/configs/Constants.php' );
    return $const[$n];
}

function page_render($view, $params = []) {
    $template = new Blade(getconst('template_dir'), getconst('template_cache'));
    $params['framework_version'] = Loader::version();
    return $template->make($view, $params);
}

function get_system_version_number() {
    return Loader::version();
}

function get_system_version() {
    return Loader::version(true);
}

function all_request($req) {
    if(app('request')->method == 'POST'){
        return app('request')->body[$req];
    } else {
        return app('request')->query[$req];
    }
}

function request(){
    if(app('request')->method == 'GET'){
        return app('request')->query;
    } else {
        return app('request')->body;
    }
}

function app_request() {
    return app('request');
}

function register_func_alias($target, $original) {
    eval("function $target() { \$args = func_get_args(); return call_user_func_array('$original', \$args); }");
}

function to_json_respose($data = [], $return_code = 200) {
    header('Content-Type: application/json');
    http_response_code($return_code);
    return json_encode($data);
} 

function array_unset_recursive(&$array, $remove) {

    foreach ($array as $key => &$value) {
        if (in_array($value, $remove)) unset($array[$key]);
        else if (is_array($value)) {
            array_unset_recursive($value, $remove);
        }
    }

}

function redirect_me($uri = null) {
    $uri = ($uri == null) ? app('request')->path() : $uri;
    header("location: ". $uri);
}

function encap_data($data = null, $status = 'success', $message= ''){
    return [
        'status' => $status,
        'message' => $message,
        'data' => $data ,
    ];
}

function array_key_by($array = [], $key){
    $new_array = [];
    foreach ($array as $member) {
        $new_key = $member[$key];
        $new_array[$new_key] = $member ;
    }
    return $new_array ;
}