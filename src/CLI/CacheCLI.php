<?php 
namespace IO\Framework\CLI;

class CacheCLI {
    public function clear($menu = null) {
        if(is_dir(BASE_PATH . 'cache') && is_dir(BASE_PATH . 'cache/view')) {
            foreach(scandir( BASE_PATH . 'cache/view' ) as $tmp) {
                $tmp_file = BASE_PATH . 'cache/view/' . $tmp;
                if(is_file( $tmp_file )) {
                    unlink($tmp_file);
                }
            }
            if($menu != null) {
                $menu->flash('Cache is clear!')->display();
            } else {
                echo str_replace('\r', '', 'Cache is clear!');
            }
        }
    }
}