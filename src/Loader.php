<?php
namespace IOFramework;

class Loader {

    protected $fnc;
    protected $cont;
    protected static $versioncontrol = 'alpha';

    function __construct() {
        /**
         * Load .env data to PHP Environment
         */
        if(file_exists(BASE_PATH . '.env')) {
            $dotenv = new \Dotenv\Dotenv(BASE_PATH);
            $dotenv->load();
        }

        /**
         * Check environment.
         */
        $this->environment();
        
        /**
         * Load constant and utility
         */
        require ( BASE_PATH . 'app/configs/Constants.php' );
        require ( 'Utility/StaticFunctions.php' );

        $this->fncs = $fnc;
        $this->cont = $const;
    }

    public static function system_version() {
        return self::version();
    }

    public static function version($int = false) {

        exec('git rev-list HEAD | wc -l', $version_number);
        
        $current_version = trim($version_number[0]);
        $div = (self::$versioncontrol == 'alpha' || self::$versioncontrol == 'beta') ? 1000 : 100; 

        if($int == false) {
            $new_version = substr($current_version/$div, 0, 4);
            $version['text'] = $new_version . ' ' . self::$versioncontrol;
            $version['number'] = $new_version;
            $version['type'] = self::$versioncontrol;
        } else {
            $version = substr($current_version/$div, 0, 4);
        }

        return $version;
    }
    
    /**
     * Check environment mode production or development
     * production: any error will disappear best for production.
     * development: any error will debug to user browser best for local develoment.
     */
    public function environment() {
        if( getenv('environment') == 'development' ) {
            /**
             * Show errors
             */
            error_reporting(E_ALL);
            ini_set('display_errors', 1);

            /**
             * Error handle
             */
            $whoops = new \Whoops\Run;
            $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
            $whoops->register();

        } else {
            ini_set('display_errors', 0);
            ini_set('display_startup_errors', 0);
        }
    }

    /**
     * Register function to alias.
     */
    public function registerFunction() {
        
        foreach ($this->fncs as $fn => $fv) {
            register_func_alias($fn, $fv);
        }
    }

    /**
     * Route initial
     */
    public function Route() {
        $app            = \System\App::instance();
        $app->request   = \System\Request::instance();
        $app->route     = \System\Route::instance($app->request);
        $route          = $app->route;

        /**
         * Load route config.
         */
        if(file_exists(BASE_PATH . 'routes/web.php')) {
            require (BASE_PATH . 'routes/web.php');
        }
        if(file_exists(BASE_PATH . 'routes/web.php')) {
            require (BASE_PATH . 'routes/api.php');
        }

        $route->end();
    }
}
