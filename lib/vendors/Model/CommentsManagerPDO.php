<?php
namespace Model;
 
use \Entity\Comment;
 
class CommentsManagerPDO extends CommentsManager
{
  protected function add(Comment $comment)
  {
  if ($comment->email() != null) {
    $q = $this->dao->prepare('INSERT INTO T_NEW_commentc SET NCC_fk_NNC = :news, NCC_auteur = :auteur, NCC_email = :email, NCC_content = :contenu, NCC_date = NOW()');
    $q->bindValue(':email', $comment->email());
  }else
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
 
  public function getListOf($news, $debut = -1, $limite = -1 )
  {
    if (!ctype_digit($news))
    {
      throw new \InvalidArgumentException('L\'identifiant de la news passé doit être un nombre entier valide');
    }
    $sql = 'SELECT NCC_id AS id, NCC_fk_NNC AS news, COALESCE(NMC_pseudo, NCC_auteur) AS auteur, NCC_email AS email, NCC_content AS contenu, NCC_date AS date
            FROM T_NEW_commentc
            LEFT OUTER JOIN T_NEW_memberc ON NCC_fk_NMC = NMC_id
            WHERE NCC_fk_NNC = :news
             ORDER BY NCC_date DESC';
    if ($debut != -1 || $limite != -1)
    {
      $sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
    }

    $q = $this->dao->prepare($sql);

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
    $q = $this->dao->prepare('SELECT NCC_id AS id, NCC_fk_NNC AS news, NCC_fk_NMC AS auteur, NCC_content AS contenu FROM T_NEW_commentc WHERE NCC_id = :id');
    $q->bindValue(':id', (int) $id, \PDO::PARAM_INT);
    $q->execute();
 
    $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');
 
    return $q->fetch();
  }


  public function countByMember($member){
    return $this->dao->query('SELECT COUNT(*) FROM T_NEW_commentc WHERE NCC_fk_NMC = '.(int) $member)->fetchColumn();
  }

  public function getOldComments($comment_old_id, $news_id, $numberCommentsToDisplay){
    return $this->getCommentsUsingNewsIdAndCommentId(
        '
          SELECT NCC_id AS id, NCC_fk_NNC AS news, COALESCE(NMC_pseudo, NCC_auteur) AS auteur, COALESCE( NMC_email, NCC_email) AS email, NCC_content AS contenu, NCC_date AS date
          FROM T_NEW_commentc
          LEFT OUTER JOIN T_NEW_memberc ON NCC_fk_NMC = NMC_id
          WHERE NCC_fk_NNC = :id_news
          AND NCC_id < :id_comment
          ORDER BY NCC_id DESC
          LIMIT '.(int) ($numberCommentsToDisplay + 1) .'
        ',
        $comment_old_id,
        $news_id
    );
  }

  public function getNewComments($comment_new_id, $news_id){
    return $this->getCommentsUsingNewsIdAndCommentId(
        '
          SELECT NCC_id AS id, NCC_fk_NNC AS news, COALESCE(NMC_pseudo, NCC_auteur) AS auteur, COALESCE( NMC_email, NCC_email) AS email, NCC_content AS contenu, NCC_date AS date
          FROM T_NEW_commentc
          LEFT OUTER JOIN T_NEW_memberc ON NCC_fk_NMC = NMC_id
          WHERE NCC_fk_NNC = :id_news
          AND NCC_id > :id_comment
          ORDER BY date DESC
        ',
        $comment_new_id,
        $news_id);
  }

  private function getCommentsUsingNewsIdAndCommentId($sql, $id_comment, $id_news){
    $requete = $this->dao->prepare($sql);
    $requete->bindValue(':id_comment', (int) $id_comment, \PDO::PARAM_INT);
    $requete->bindValue(':id_news', (int) $id_news, \PDO::PARAM_INT);
    $requete->execute();

    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');
    $comments = $requete->fetchAll();

    foreach ($comments as $comment) {
      $comment->setDate(new \DateTime($comment->date()));
    }

    return $comments;
  }
}