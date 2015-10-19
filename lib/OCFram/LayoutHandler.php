<?php
namespace OCFram;

use \OCFram\Link;

class LayoutHandler
{
    protected $userStatus,
               $links = [];

    public function __construct($userStatus){
        $this->setUserStatus($userStatus);
        $this->links = [];
    }

    public function createMenu(){
        $menu = "<ul>";
        foreach($this->links as $link){
            if(in_array($this->userStatus, $link->access())) {
                $menu .= "<li> <a href='" . $link->uri() . "'>" . $link->name() . "</a></li>";
            }
        }
        return $menu ."</ul>";
    }

    public function add(Link $link){
        $this->links[] = $link;
        return $this;
    }

    public function setUserStatus($userStatus){
        if(is_integer($userStatus))
            $this->userStatus = $userStatus;
    }

    public function userStatus(){
        return $this->userStatus;
    }
}