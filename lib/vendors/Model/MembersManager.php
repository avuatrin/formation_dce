<?php
namespace Model;

use \OCFram\Manager;
use \Entity\Member;

abstract class MembersManager extends Manager
{
    /**
     * M�thode permettant d'ajouter un Membre.
     * @param $member Member Le membre � ajouter
     * @return void
     */
    abstract protected function add(Member $member);

    /**
     * M�thode permettant d'enregistrer un membre.
     * @param $member Member le membre � enregistrer
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
            throw new \RuntimeException('Le membre doit �tre valid� pour �tre enregistr�');
        }
    }

    /**
     * M�thode renvoyant le nombre de membres total.
     * @return int
     */
    abstract public function count();

    /**
     * M�thode permettant de supprimer un Membre.
     * @param $id int L'identifiant du membre � supprimer
     * @return void
     */
    abstract public function delete($id);

    /**
     * M�thode retournant une liste de membres demand�e.
     * @param $id id du membre au pseudo � selectionner, null pour tous les selectionner
     * @return array La liste des membres. Chaque entr�e est une instance de Membre.
     */
    abstract public function getList($id);

    /**
     * M�thode retournant un membre pr�cise.
     * @param $id int L'identifiant du membre � r�cup�rer
     * @return News Le membre demand�
     */
    abstract public function getUnique($id);

    /**
     * M�thode permettant de modifier un membre.
     * @param $member Member le membre � modifier
     * @return void
     */
    abstract protected function modify(Member $member);

    /**
     * Methode premettant de trouver si un pseudo est pris
     * @param $pseudo string le pseudo � v�rifier
     * @return int id du membre
     */
    abstract public function checkPseudoExist($pseudo);

    /**
     * M�thode permettant de v�rifier pseudo et mot de passe d'un compte
     * @param $pseudo string pseudo � v�rifier
     * @param $password string mot de passe
     * @return int id du membre
     */
    abstract public function connect($pseudo, $password);
}