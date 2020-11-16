<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

class ArticleApiController extends AbstractController
{
    /**
     * @Route("/api/article", name="article_api", methods={"GET"})
     */
    public function index(ArticleRepository $repository): Response
    {
        $articles = $repository->findAll();

        return $this->json($articles);
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

    public function create(Request $request, SerializerInterface $serializer)
    {
        if(!$request->getContent()){
            return $this->json(["error" => "request content is required"], 400);
        }
        $article = $serializer->deserialize($request->getContent(), Article::class, "json");
        $em = $this->getDoctrine()->getManager();
        $em->persist($article);
        $em->flush();
        return $this->json($article);
    }
}
