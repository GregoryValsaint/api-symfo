<?php


namespace App\EventListener;


use App\Entity\Article;
use Symfony\Contracts\EventDispatcher\Event;

class ArticleCreatedEvent extends Event
{
    public const NAME = "article.created";

    /**
     * @var Article
     */
    protected $article;

    /**
     * ArticleCreatedEvent constructor
     * @param $article
     */
    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * @return mixed
     */
    public function getArticle()
    {
        return $this->article;
    }
}