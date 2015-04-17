<?php 

/**
 * 分页类
 */

Class Page
{
    private $p; // 当前页码
    private $pageNum; // 显示多少页
    private $pageSize; // 每页显示多少条
    private $total; //总条数
    private $offset; // 起始位置


    private function __construct($total, $pageNum, $pageSize){
        $this->total = $total;
        $this->pageNum = $pageNum;
        $this->pageSize = $pageSize;
        $this->totalPage = ceil($total / $pageSize);
        $this->offset = ($this->p - 1) * $pageSize;
        $this->prePage = $this->prePage();
        $this->nextPage = $this->nextPage();
    }

    private function prePage(){
        if ($this->p <= 1) {
            return 1;
        } else {
            return $this->p - 1;
        }
    }

    private function nextPage(){
        if ($this->p >= $this->totalPage) {
            return $this->totalPage;
        } else {
            return $this->p + 1;
        }
        
    }

    private function total(){

    }

    private function show(){

    }
}