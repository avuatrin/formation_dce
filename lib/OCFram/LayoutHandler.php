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
        $menu = [];
        foreach($this->links as $link){
            if(in_array($this->userStatus, $link->access())) {
                array_push($menu,
                    array(
                        'name' => $link->name(),
                        'uri' => $link->uri(),
                    )
                );
            }
        }
        return $menu;
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