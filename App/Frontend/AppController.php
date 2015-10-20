<?php
namespace App\Frontend;

use \OCFram\LayoutHandler;
use \OCFram\Link;
use \Entity\Member;

trait AppController {

    protected $customMenu = [];

    protected function run(){
        if(empty($this->customMenu)) {
            $this->generateBasicMenu();
        }else {
            $this->generateCustomMenu();
        }
        $this->generateJavaScriptIncludes();
        $this->page()->addVar('user', $this->app()->user());

    }

    protected function generateBasicMenu(){
        $layoutHandler = new LayoutHandler($this->app()->user()->isAuthenticated() ? $this->app()->user()->member()->type() : Member::NOT_CONNECTED);
        $layoutHandler->add(new Link([
            'name' => 'Accueil',
            'uri' => $this->app()->router()->getUrl('index', 'News'),
            'access' => [
                Member::TYPE_ADMINISTRATOR,
                Member::NOT_CONNECTED,
                Member::TYPE_AUTHOR,
            ],
        ]))->add(new Link([
            'name' => 'Mobile Detect',
            'uri' => $this->app()->router()->getUrl('index', 'MobileDetect'),
            'access' => [
                Member::TYPE_ADMINISTRATOR,
                Member::NOT_CONNECTED,
                Member::TYPE_AUTHOR,
            ],
        ]))->add(new Link([
            'name' => 'Ecrire Une News',
            'uri' => $this->app()->router()->getUrl('insert', 'News'),
            'access' => [
                Member::TYPE_ADMINISTRATOR,
                Member::TYPE_AUTHOR,
            ],
        ]))->add(new Link([
            'name' => 'Deconnexion',
            'uri' => $this->app()->router()->getUrl('deconnexion', 'Deconnexion'),
            'access' => [
                Member::TYPE_ADMINISTRATOR,
                Member::TYPE_AUTHOR,
            ],
        ]))->add(new Link([
            'name' => 'Admin',
            'uri' => '/admin/',
            'access' => [
                Member::TYPE_ADMINISTRATOR,
            ],
        ]))->add(new Link([
            'name' => 'Connexion',
            'uri' => $this->app()->router()->getUrl('index', 'connexion'),
            'access' => [
                Member::NOT_CONNECTED,
            ],
        ]))->add(new Link([
            'name' => 'Inscription',
            'uri' => $this->app()->router()->getUrl('index', 'inscription'),
            'access' => [
                Member::NOT_CONNECTED,
            ],
        ]));
        $this->page()->addVar('menu', $layoutHandler->createMenu() );
    }

    protected function generateCustomMenu(){
        $menu = [];
        foreach($this->customMenu as $link){
            array_push($menu,
                array(
                    'name' => $link->name(),
                    'uri' => $link->uri(),
                )
            );
        }
        $this->page()->addVar('menu', $menu );
    }

    protected function setCustomMenu($customMenu){
        foreach($customMenu as $link){
            if($link instanceof Link){
                array_push($this->customMenu, $link);
            }
        }
    }

    /** Ajoute à la page les fichiers javascript de base
     * @return void
     */
    protected function generateJavaScriptIncludes(){
        $scripts = [
            '/JS/scriptAffichageCommenter.js',
        ];
        $jsIncludes = '';
        foreach($scripts as $script){
            $jsIncludes .= '<script type="text/javascript" src="'.$script.'"></script>';
        }

        $this->page()->addVar('scripts', $jsIncludes);
    }
};