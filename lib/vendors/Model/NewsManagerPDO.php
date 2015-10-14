<?php
namespace Model;
 
use \Entity\News;
 
class NewsManagerPDO extends NewsManager
{
  protected function add(News $news)
  {
    $requete = $this->dao->prepare('INSERT INTO T_NEW_newsc SET NNC_fk_NMC = :auteur, NNC_title = :titre, NNC_content = :contenu, NNC_dateAdd = NOW(), NNC_dateModif = NOW()');
 
    $requete->bindValue(':titre', $news->titre());
    $requete->bindValue(':auteur', $news->auteur());
    $requete->bindValue(':contenu', $news->contenu());

    $requete->execute();

    $news->setId($this->dao->lastInsertId());
  }
 
  public function count()
  {
    return $this->dao->query('SELECT COUNT(*) FROM T_NEW_newsc')->fetchColumn();
  }
 
  public function delete($id)
  {
    $this->dao->exec('DELETE FROM T_NEW_newsc WHERE NNC_id = '.(int) $id);
    $this->deleteTags($id);
  }
 
  public function getList($debut = -1, $limite = -1)
  {
    $sql = 'SELECT NNC_id AS id, NNC_fk_NMC AS Auteur, NNC_title AS titre, NNC_content AS contenu, NNC_dateAdd AS dateAjout, NNC_dateModif AS dateModif FROM T_NEW_newsc ORDER BY NNC_id DESC';
 
    if ($debut != -1 || $limite != -1)
    {
      $sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
    }

    $requete = $this->dao->query($sql);
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');
 
    $listeNews = $requete->fetchAll();
 
    foreach ($listeNews as $news)
    {
      $news->setDateAjout(new \DateTime($news->dateAjout()));
      $news->setDateModif(new \DateTime($news->dateModif()));
    }
 
    $requete->closeCursor();
 
    return $listeNews;
  }
 
  public function getUnique($id, $bddMode=false)
  {
    if($bddMode)
      $requete = $this->dao->prepare('SELECT NNC_id AS id, NNC_fk_NMC AS auteur, NNC_title AS titre, NNC_content AS contenu, NNC_dateAdd AS dateAjout, NNC_dateModif AS dateModif FROM T_NEW_newsc WHERE NNC_id = :id');
    else
      $requete = $this->dao->prepare('SELECT NNC_id AS id, NMC_pseudo AS auteur, NNC_title AS titre, NNC_content AS contenu, NNC_dateAdd AS dateAjout, NNC_dateModif AS dateModif FROM T_NEW_newsc INNER JOIN T_NEW_memberc ON NNC_fk_NMC = NMC_id WHERE NNC_id = :id');
    $requete->bindValue(':id', (int) $id, \PDO::PARAM_INT);
    $requete->execute();
 
    $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');
 
    if ($news = $requete->fetch())
    {
      $news->setDateAjout(new \DateTime($news->dateAjout()));
      $news->setDateModif(new \DateTime($news->dateModif()));
      //Recupération des tags
      $this->getTags($news);

      return $news;
    }
 
    return null;
  }
 
  protected function modify(News $news)
  {
    $requete = $this->dao->prepare('UPDATE T_NEW_newsc SET NNC_fk_NMC = :auteur, NNC_title = :titre, NNC_content = :contenu, NNC_dateModif = NOW() WHERE NNC_id = :id');
 
    $requete->bindValue(':titre', $news->titre());
    $requete->bindValue(':auteur', $news->auteur());
    $requete->bindValue(':contenu', $news->contenu());
    $requete->bindValue(':id', $news->id(), \PDO::PARAM_INT);
 
    $requete->execute();
  }

  public function countByMember($member){
    return $this->dao->query('SELECT COUNT(*) FROM T_NEW_newsc WHERE NNC_fk_NMC = '.(int) $member)->fetchColumn();
  }

  public function getTags(News $news){
    $requeteTag = $this->dao->prepare('SELECT NTC_name AS tagName  FROM T_NEW_tagc INNER JOIN T_NEW_tagd ON NTC_id = NTD_fk_NTC WHERE NTD_fk_NNC = '.(int) $news->id().' ORDER BY NTC_name' );
    $requeteTag->execute();

    while($tag = $requeteTag->fetch()) {
        $news->addTag($tag['tagName']);
    }
  }

  public function saveTags(News $news){
      $this->deleteTags($news->id());
      if(str_replace(' ', '',$news->tags()) == '' )
          return ;
      $query = 'INSERT INTO T_NEW_tagc (NTC_name) SELECT name FROM (';
      foreach($news->tagsArray() as $key=>$tag){
          $query .= (($key===0)?(''):(' UNION ')).'SELECT '.$this->dao->quote($tag).' AS name';
      }
      $query .= ') AS A LEFT OUTER JOIN T_NEW_tagc AS B ON A.name = B.NTC_name WHERE B.NTC_id IS NULL';
      //echo $query;
//      var_dump($query);
      $this->dao->exec($query);
      $requete = 'INSERT INTO t_new_tagd (NTD_fk_NNC, NTD_fk_NTC) SELECT '.(int) $news->id().', NTC_id FROM T_NEW_tagc WHERE NTC_name IN(';
      foreach($news->tagsArray() as $key=>$tag){
          $requete .= (($key===0)?(''):(',')).$this->dao->quote($tag);
      }
      $this->dao->exec($requete.');');
  }

    protected function deleteTags($id){
      $this->dao->exec('DELETE FROM T_NEW_tagd WHERE NTD_fk_NNC = '.(int) $id);
  }

    public function getListByTag($tag, $debut = -1, $limite = -1){
        $sql = 'SELECT NNC_id AS id, NNC_fk_NMC AS Auteur, NNC_title AS titre, NNC_content AS contenu, NNC_dateAdd AS dateAjout, NNC_dateModif AS dateModif FROM T_NEW_newsc INNER JOIN T_NEW_tagd ON NTD_fk_NNC = NNC_id INNER JOIN T_NEW_tagc ON NTC_id = NTD_fk_NTC WHERE NTC_name=:tag ORDER BY NNC_dateAdd DESC';

        if ($debut != -1 || $limite != -1)
        {
            $sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
        }
        $requete = $this->dao->prepare($sql);
        $requete->bindValue(':tag', $tag);
        $requete->execute();
        $requete->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\News');

        $listeNews = $requete->fetchAll();

        foreach ($listeNews as $news)
        {
            $news->setDateAjout(new \DateTime($news->dateAjout()));
            $news->setDateModif(new \DateTime($news->dateModif()));
        }

        $requete->closeCursor();

        return $listeNews;
    }
}