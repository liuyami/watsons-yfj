<?php


class Page
{
    //每次显示的数量
    public $pageSize = 9;
    //当前页数
    public $page;
    //总数
    public  $count;
    //总页数
    public $pagecount;
    //查询开始在第几条
    public $startRow;
    //默认设置总数 还有每页显示几条数据
    public function __construct($count,$pageSize,$page=1){
        $this->count=$count;
        $this->pageSize=$pageSize;
        $this->page=$page;
    }

    /*获取总页数*/
    public function getPagecount(){
       if($this->count %  $this->pageSize == 0){
            //
           $this->pagecount =$this->count / $this->pageSize;
       }else{

           $this->pagecount = $this->count / $this->pageSize + 1;
       }
       return floor($this->pagecount);
    }

    //获取查询开始在第几条
    public function getStartRow(){
        return $this->startRow=($this->page - 1) * $this->pageSize ?($this->page - 1) * $this->pageSize : 0;
    }

    public function setPage($pageindex){
        $this->page=$pageindex;
    }





}