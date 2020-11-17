<?php

namespace App\Controller;

use App\Entity\Article;
use App\EventListener\ArticleCreatedEvent;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ArticleApiController extends AbstractController
{
    /**
     * @Route("/api/article", name="article_api", methods={"GET"})
     */
    public function index(ArticleRepository $repository): Response
    {
        $articles = $repository->findAll();

        return $this->json($articles, 200, [], ["groups" => ["article_list"]]);
    }

    /**
     * @Route("/api/article/{article}", name="article_read", methods={"GET"})
     * @param Article $article
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */

    public function read(Article $article)
    {
        return $this->json($article);
    }

    /**
     * @Route("/api/article", name="article_create", methods={"POST"})
     */

    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EventDispatcherInterface $dispatcher)
    {
        if(!$request->getContent()){
            return $this->json(["error" => "request content is required"], 400);
        }
    /**@var Article $article */
        $article = $serializer->deserialize($request->getContent(), Article::class, "json");
        $errors = $validator->validate($article);
        if (sizeof($errors) > 0){
            return $this->json($errors, 400);
        }
        $em = $this->getDoctrine()->getManager();
        $event = new ArticleCreatedEvent($article);
        $dispatcher->dispatch($event, ArticleCreatedEvent::NAME);
        $em->persist($article);
        $em->flush();
        return $this->json($article);
    }

    /**
     * @Route("/api/article/{article}", name="article_edit", methods={"PUT"})
     */
    public function edit(Article $article, Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        if(!$request->getContent()){
            return $this->json(["error" => "request content is required"], 400);
        }
        $serializer->deserialize($request->getContent(), Article::class, "json", ["object_to_populate" => $article]);
        $errors = $validator->validate($article);
        if (sizeof($errors) > 0){
            return $this->json($errors, 400);
        }
        $this->getDoctrine()->getManager()->flush();
        return $this->json($article, 201);
    }

    /**
     * @Route("/api/article/{article}", name="article_delete", methods={"DELETE"})
     */
    public function delete(Article $article)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($article);
        $em->flush();
        return $this->json(["message" => "article deleted"]);
    }
}
