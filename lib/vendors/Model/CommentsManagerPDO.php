<?php
namespace Model;
 
use \Entity\Comment;
 
class CommentsManagerPDO extends CommentsManager
{
  protected function add(Comment $comment)
  {
    $q = $this->dao->prepare('INSERT INTO T_NEW_commentc SET NCC_fk_NNC = :news, NCC_fk_NMC = :auteur, NCC_content = :contenu, NCC_date = NOW()');
 
    $q->bindValue(':news', $comment->news(), \PDO::PARAM_INT);
    $q->bindValue(':auteur', $comment->auteur());
    $q->bindValue(':contenu', $comment->contenu());
 
    $q->execute();
 
    $comment->setId($this->dao->lastInsertId());
  }
 
  public function delete($id, $user)
  {
    $this->dao->exec('DELETE FROM T_NEW_commentc WHERE NCC_id = '.(int) $id);
  }
 
  public function deleteFromNews($news)
  {
    $this->dao->exec('DELETE FROM T_NEW_commentc WHERE NCC_fk_NNC = '.(int) $news);
  }
 
  public function getListOf($news)
  {
    if (!ctype_digit($news))
    {
      throw new \InvalidArgumentException('L\'identifiant de la news passé doit être un nombre entier valide');
    }
 
    $q = $this->dao->prepare('SELECT NCC_id AS id, NCC_fk_NNC AS news, NMC_pseudo AS auteur, NCC_content AS contenu, NCC_date AS date FROM T_NEW_commentc INNER JOIN T_NEW_memberc ON NCC_fk_NMC = NMC_id WHERE NCC_fk_NNC = :news');
    $q->bindValue(':news', $news, \PDO::PARAM_INT);
    $q->execute();
 
    $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');
 
    $comments = $q->fetchAll();
 
    foreach ($comments as $comment)
    {
      $comment->setDate(new \DateTime($comment->date()));
    }
 
    return $comments;
  }
 
  protected function modify(Comment $comment)
  {
    $q = $this->dao->prepare('UPDATE T_NEW_commentc SET NCC_fk_NMC = :auteur, NCC_content = :contenu WHERE NCC_id = :id');
 
    $q->bindValue(':auteur', $comment->auteur());
    $q->bindValue(':contenu', $comment->contenu());
    $q->bindValue(':id', $comment->id(), \PDO::PARAM_INT);
 
    $q->execute();
  }
 
  public function get($id)
  {
    $q = $this->dao->prepare('SELECT NCC_id AS id, NCC_fk_NNC AS news, NMC_pseudo AS auteur, NCC_content AS contenu FROM T_NEW_commentc INNER JOIN T_NEW_memberc ON NCC_fk_NMC = NMC_id WHERE NCC_id = :id');
    $q->bindValue(':id', (int) $id, \PDO::PARAM_INT);
    $q->execute();
 
    $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');
 
    return $q->fetch();
  }
}