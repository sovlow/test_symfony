<?php

namespace App\Controller;

use App\Entity\Products;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
  #[Route('/product', name:'app_product')]
  public function index(EntityManagerInterface $entityManager): Response
  {
    $dataRepository = $entityManager->getRepository(Products::class);
    $dataProducts = $dataRepository->findAll();
    return $this->render('product/index.html.twig', compact('dataProducts'));
  }
}
