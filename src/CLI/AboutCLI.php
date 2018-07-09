<?php 
namespace IO\Framework\CLI;

class AboutCLI {
    protected $credit;

    public function __construct() {
        $this->credit = "I/O FRAMEWORK : TEAM
=======================
1. Apinan Woratrakun [CEO]
2. Charin Aumponpison [Cross platform Engineer]
3. Preecha [Designner]
4. Supawadee Krutjaikla [Operation Manager]
5. Anucha [Developer]";
    }

    public function credit($menu) {
        echo $this->credit;
    }
}