<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Article>
 *
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    public function getArticlePictures($article_id): array
    {
        return $this->createQueryBuilder("a")
            ->setParameter('articleId', $article_id)
            ->select('articlePicture.picture_link')
            ->distinct()
            ->from('App\Entity\ArticlePicture', 'articlePicture')
            ->join('articlePicture.article', 'article')
            ->where('article.id= :articleId')
            ->getQuery()
            ->getResult();
    }

    public function getAvailableArticleSizes($article_id): array
    {
        return $this->createQueryBuilder('a')
            ->select('sizes.id, sizes.size_label')
            ->distinct(true)
            ->from('App\Entity\Stock', 'stock')
            ->innerJoin('stock.Article', 'article')  // Assuming 'article' is the property name for the Article association in Stock entity
            ->innerJoin('stock.size', 'sizes')       // Assuming 'sizes' is the property name for the Size association in Stock entity
            ->where('article.id = :articleId')
            ->andWhere('stock.amount > 0')
            ->setParameter('articleId', $article_id)
            ->getQuery()
            ->getResult();
    }

    public function getArticleSizesWithStock($article_id): array
    {
        return $this->createQueryBuilder('a')
            ->setParameter('articleId', $article_id)
            ->select('stock.amount, size.id, size.size_label')
            ->distinct(true)
            ->from("App\Entity\Stock", 'stock')
            ->join('stock.size', 'size')
            ->join('stock.Article', 'article')
            ->where('article.id = :articleId')
            ->getQuery()
            ->getResult();
    }

    public function getArticleColors($article_id): array
    {
        return $this->createQueryBuilder('a')
            ->setParameter('articleId', $article_id)
            ->select('color.id, color.color_label')
            ->distinct(true)
            ->from('App\Entity\Color', 'color')
            ->join('color.articlePictures', 'article_picture')
            ->join('article_picture.article', 'article')
            ->where('article.id= :articleId')

            ->getQuery()
            ->getResult();
    }
    public function getArticleCategory($article_id): array
    {
        return $this->createQueryBuilder('a')
            ->setParameter('articleId', $article_id)
            ->select('category.id, category.category_name')
            ->distinct(true)
            ->from('App\Entity\Article', 'article')
            ->join('article.category', 'category')
            ->where('article.id= :articleId')

            ->getQuery()
            ->getResult();
    }

    public function findArticlesByColor($color_id): array
    {
        return $this->createQueryBuilder('a')
            ->setParameter('colorId', $color_id)
            ->select('article.id, article.article_title, article.article_description, article.selling_price')
            ->distinct(true)
            ->from('App\Entity\Article', 'article')
            ->join('article.articlePictures', 'article_pictures')
            ->join('article_pictures.color', 'color')
            ->where('color.id= :colorId')
            ->getQuery()
            ->getResult();
    }

    public function findArticlesBySize($size_id):array
    {
        return $this->createQueryBuilder('a')
            ->setParameter('size_id', $size_id)
            ->select('articles.id, articles.article_title, articles.selling_price')
            ->distinct(true)
            ->from('App\Entity\Sizes', 'size')
            ->join('size.stocks', 'stocks')
            ->join('stocks.articles', 'articles')
            ->where('size.id= :size_id and stocks.amount > 0')
            ->getQuery()
            ->getResult();

    }

    public function findArticlesByCategory($category_id):array
    {
        return $this->createQueryBuilder('a')
            ->setParameter('category_id', $category_id)
            ->select('article.id, article.article_title, article.selling_price')
            ->distinct(true)
            ->from('App\Entity\Article', 'article')
            ->join('article.category', 'category')
            ->where('category.id= :category_id')
            ->getQuery()
            ->getResult();

    }



//    /**
//     * @return Article[] Returns an array of Article objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Article
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
