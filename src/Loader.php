<?php
namespace IOFramework;

class Loader {

    protected $fnc;
    protected $cont;

    protected static $version = [
        "type" => "alpha",
        "number" => "0.2.1"
    ];

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

        if($int == false) {
            $vs['type'] = self::$version;
            $vs['text'] = self::$version['number'] . '-' . self::$version['type'];
        } else {
            $vs = self::$version['number'];
        }

        return $vs;
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
