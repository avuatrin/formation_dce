<?php
namespace App;

use \OCFram\LayoutHandler;
use \OCFram\Link;
use \Entity\Member;

trait MenuGenerator {

    public function generateBasicMenu(){
        $layoutHandler = new LayoutHandler($this->app()->user()->isAuthenticated() ? $this->app()->user()->type() : Member::NOT_CONNECTED);
        $layoutHandler->add(new Link([
            'name' => 'Accueil',
            'uri' => '/',
            'access' => [
                Member::TYPE_ADMINISTRATOR,
                Member::TYPE_AUTHOR,
                Member::NOT_CONNECTED,
            ],
        ]))->add(new Link([
            'name' => 'Mobile Detect',
            'uri' => '/mobile-detect.html',
            'access' => [
                Member::TYPE_ADMINISTRATOR,
                Member::TYPE_AUTHOR,
                Member::NOT_CONNECTED,
            ],
        ]))->add(new Link([
            'name' => 'Ecrire Une News',
            'uri' => '/news-insert.html',
            'access' => [
                Member::TYPE_ADMINISTRATOR,
                Member::TYPE_AUTHOR,
            ],
        ]))->add(new Link([
            'name' => 'Deconnexion',
            'uri' => '/deconnexion.html',
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
            'uri' => '/connexion.html',
            'access' => [
                Member::NOT_CONNECTED,
            ],
        ]))->add(new Link([
            'name' => 'Inscription',
            'uri' => '/inscription.html',
            'access' => [
                Member::NOT_CONNECTED,
            ],
        ]));
        $this->page()->addVar('menu', $layoutHandler->createMenu() );
    }
};