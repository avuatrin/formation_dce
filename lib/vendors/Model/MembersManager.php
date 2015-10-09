<?php
namespace Model;

use \OCFram\Manager;
use \Entity\Member;

abstract class MembersManager extends Manager
{
    /**
     * Méthode permettant d'ajouter un Membre.
     * @param $member Member Le membre à ajouter
     * @return void
     */
    abstract protected function add(Member $member);

    /**
     * Méthode permettant d'enregistrer un membre.
     * @param $member Member le membre à enregistrer
     * @see self::add()
     * @see self::modify()
     * @return void
     */
    public function save(Member $member)
    {
        if ($member->isValid())
        {
            $member->isNew() ? $this->add($member) : $this->modify($member);
        }
        else
        {
            throw new \RuntimeException('Le membre doit être validé pour être enregistré');
        }
    }

    /**
     * Méthode renvoyant le nombre de membres total.
     * @return int
     */
    abstract public function count();

    /**
     * Méthode permettant de supprimer un Membre.
     * @param $id int L'identifiant du membre à supprimer
     * @return void
     */
    abstract public function delete($id);

    /**
     * Méthode retournant une liste de membres demandée.
     * @param $id id du membre au pseudo à selectionner, null pour tous les selectionner
     * @return array La liste des membres. Chaque entrée est une instance de Membre.
     */
    abstract public function getList($id);

    /**
     * Méthode retournant un membre précise.
     * @param $id int L'identifiant du membre à récupérer
     * @return News Le membre demandé
     */
    abstract public function getUnique($id);

    /**
     * Méthode permettant de modifier un membre.
     * @param $member Member le membre à modifier
     * @return void
     */
    abstract protected function modify(Member $member);

    /**
     * Methode premettant de trouver si un pseudo est pris
     * @param $pseudo string le pseudo à vérifier
     * @return int id du membre
     */
    abstract public function checkPseudoExist($pseudo);

    /**
     * Méthode permettant de vérifier pseudo et mot de passe d'un compte
     * @param $pseudo string pseudo à vérifier
     * @param $password string mot de passe
     * @return int id du membre
     */
    abstract public function connect($pseudo, $password);
}