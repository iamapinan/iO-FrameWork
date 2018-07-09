<?php
namespace IO\Framework\Database;
use Medoo\Medoo;

class MySQL {
    var $connect = '';
    public function __construct() {
        $this->connect = new Medoo([
            'database_type' => getconst('default_database'),
            'database_name' => getenv('mysql_database_name'),
            'server' => getenv('mysql_host'),
            'username' => getenv('mysql_user'),
            'password' => getenv('mysql_password'),
            'charset' => 'utf8'
        ]);
    }

    public function select($tb, $f = array(), $cond = array()) {
        return $this->connect->select($tb, $f, $cond);
    }

    public function selectOne($tb, $f = array(), $cond = array()) {
        $cond['LIMIT'] = [0,1];
        $res = $this->select($tb, $f, $cond);
        return $res[0];
    }

    public function insert($tb, $data) {
        return $this->connect->insert($tb, $data);
    }

    public function update($tb, $data, $cond = array()) {
        return $this->connect->update($tb, $data, $cond);
    }

    public function delete($tb, $cond) {
        return $this->connect->delete($tb, $cond);
    }

    public function exec() {
        return $this->connect;
    }
    
    public function PaginationMulti($tb, $join = array(), $f = array(), $cond = array(), $orderBy = array(),$paginationSize = 10) {
        $page = @(!empty(req('page'))) ? req('page') : 1;

        //========== Where ==========
        $condlimit = $cond;
        //========== Order by ==========
        $condlimit["ORDER"] = $orderBy;
        //========== Limit ==========
        if($page == 1){
            $condlimit["LIMIT"] = [0,$paginationSize];
        }else{
            $condlimit["LIMIT"] = [($page*$paginationSize)-$paginationSize,$paginationSize];
        }

        //========== Query data ==========
        $data = $this->connect->select($tb, $join, $f, $condlimit);
 
        //========== Query count data ==========
        $count = $this->connect->count($tb,$cond);
        $pageCount = ceil($count/$paginationSize);
    
        //==========
        $pageNext = $page+1;
        $pagePrev = $page-1;
        //==========
        $res = [
            "total" => $count,
            "per_page" => $paginationSize,
            "current_page" => $page,
            "last_page" => $pageCount,
            "first_page_url" => getenv("domain").this()->path."?page=1",
            "last_page_url" => getenv("domain").this()->path."?page=".$pageCount,
            "next_page_url" => $pageNext <= $pageCount ?getenv("domain").this()->path."?page=".$pageNext : null,
            "prev_page_url" => $pagePrev == 1 ? null :getenv("domain").this()->path."?page=".$pagePrev,
            "path" => getenv("domain"),
            "data" => $data
        ];
        return $res;
    }

    public function pagination($tb, $f = array(), $cond = array(), $orderBy = array(),$paginationSize = 10) {
        $page = @(!empty(req('page'))) ? req('page') : 1;

        //========== Where ==========
        $condlimit = $cond;
        //========== Order by ==========
        $condlimit["ORDER"] = $orderBy;
        //========== Limit ==========
        if($page == 1){
            $condlimit["LIMIT"] = [0,$paginationSize];
        }else{
            $condlimit["LIMIT"] = [($page*$paginationSize)-$paginationSize,$paginationSize];
        }

        //========== Query data ==========
        $data = $this->connect->select($tb, $f, $condlimit);
        //========== Query count data ==========
        $count = $this->connect->count($tb,$cond);
        $pageCount = ceil($count/$paginationSize);
    
        //==========
        $pageNext = $page+1;
        $pagePrev = $page-1;
        //==========
        $res = [
            "total" => $count,
            "per_page" => $paginationSize,
            "current_page" => $page,
            "last_page" => $pageCount,
            "first_page_url" => getenv("domain").this()->path."?page=1",
            "last_page_url" => getenv("domain").this()->path."?page=".$pageCount,
            "next_page_url" => $pageNext <= $pageCount ?getenv("domain").this()->path."?page=".$pageNext : null,
            "prev_page_url" => $pagePrev == 1 ? null :getenv("domain").this()->path."?page=".$pagePrev,
            "path" => getenv("domain"),
            "data" => $data
        ];
        return $res;
    }
}
