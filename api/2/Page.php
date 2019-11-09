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

    //需要补全数据时  开始的位置
    public $needLastRow;


    //最后一页是否是完整的

    public $last;
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
            //能被整除 设置最后一页的数据是完整的
           $this->last=true;
       }else{
           $this->last=false;

           //最后一页的数据不完整 ,  获取填充数据  最后还剩 2 天数据

           //需要 补7条数据
           $need = $this->pageSize - ($this->count %  $this->pageSize);

           $this->needLastRow =($this->pageSize * floor($this->count / $this->pageSize)) - $need;//=18;


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

    public function getPage(){
        return  $this->page;
    }

    public function getNeedLastRow(){

        return $this->needLastRow;
    }

    public function getLast(){
        return $this->last;
    }






}