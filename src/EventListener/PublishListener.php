<?php


namespace App\EventListener;


class PublishListener
{
    public function onArticleCreated(ArticleCreatedEvent $event)
    {
        if (!$event->getArticle()->getPublished()){
            $event->getArticle()->setPublished(true);
        }

        $event->getArticle()->setPublished(true);

    }
}