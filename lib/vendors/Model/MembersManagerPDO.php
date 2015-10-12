<?php
namespace Model;

use \Entity\Member;

class MembersManagerPDO extends MembersManager
{
    protected function add(Member $member)
    {
        $requete = $this->dao->prepare('INSERT INTO T_NEW_memberc SET NMC_pseudo = :pseudo, NMC_password = :password, NMC_philosophy = :philosophy, NMC_fk_NMY = :type ;');

        $requete->bindValue(':pseudo', $member->pseudo());
        $requete->bindValue(':password', $member->password());
        $requete->bindValue(':philosophy', $member->philosophy());
        $requete->bindValue(':type', $member->type(), \PDO::PARAM_INT);

        $requete->execute();
    }

    public function count()
    {
        return $this->dao->query('SELECT COUNT(*) FROM T_NEW_memberc')->fetchColumn();
    }

    public function delete($id)
    {
        $this->dao->exec('DELETE FROM T_NEW_memberc WHERE NMC_id = '.(int) $id);
    }

    public function getList($id)
    {
        $requete = $this->dao->prepare('SELECT NMC_fk_NMY FROM T_NEW_memberc WHERE NMC_id = :id');
        $requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $requete->execute();
        $typeMember = $requete->fetchColumn();

        if($typeMember == Member::TYPE_ADMINISTRATOR)
            $requete = $this->dao->prepare('SELECT NMC_id, NMC_pseudo FROM T_NEW_memberc');
        else {
            $requete = $this->dao->prepare('SELECT NMC_id, NMC_pseudo FROM T_NEW_memberc WHERE NMC_id = :id');
            $requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        }
        $requete->execute();
        $pseudos = [];
        while ($pseudo = $requete->fetch())
            $pseudos[$pseudo[0]] = $pseudo[1];  //  [id] => pseudo

        return $pseudos;
    }

    public function getUnique($id)
    {
        $requete = $this->dao->prepare('SELECT NMC_id AS id, NMC_password AS password , NMC_fk_NMY AS type, NMC_pseudo AS pseudo, NMC_philosophy AS philosophy FROM T_NEW_memberc WHERE NMC_id = :id');
        $requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $requete->execute();

        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Member');

        if ($member = $requete->fetch())
        {
            return $member;
        }

        return null;
    }

    protected function modify(Member $member)
    {
        $requete = $this->dao->prepare('UPDATE T_NEW_memberc SET NMC_pseudo = :pseudo, NMC_password = :password, NMC_philosophy = :philosophy, NMC_fk_NMY = :type WHERE NMC_id = :id');

        $requete->bindValue(':pseudo', $member->pseudo());
        $requete->bindValue(':password', $member->password());
        $requete->bindValue(':philosophy', $member->philosophy());
        $requete->bindValue(':type', $member->type(), \PDO::PARAM_INT);
        $requete->bindValue(':id', (int) $member->id(), \PDO::PARAM_INT);

        $requete->execute();
    }

    public function checkPseudoExist($pseudo){
        $requete = $this->dao->prepare('SELECT NMC_id FROM T_NEW_memberc WHERE NMC_pseudo = :pseudo');

        $requete->bindValue(':pseudo', $pseudo);

        $requete->execute();

        return $requete->fetchColumn();
    }

    public function connect($pseudo, $password){
        $requete = $this->dao->prepare('SELECT NMC_id FROM T_NEW_memberc WHERE NMC_pseudo = :pseudo AND NMC_password = :password');

        $requete->bindValue(':pseudo', $pseudo);
        $requete->bindValue(':password', $password);

        $requete-> execute();

        return $requete->fetchColumn();

    }

}