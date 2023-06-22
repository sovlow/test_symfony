<?php

namespace App\Controller;

use App\Entity\Products;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
  private EntityManagerInterface $entityManager;

  public function __construct(EntityManagerInterface $entityManager)
  {
    $this->entityManager = $entityManager;
  }

  #[Route('/product', name:'index_product')]
  public function index(EntityManagerInterface $entityManager): Response
  {
    $dataRepository = $entityManager->getRepository(Products::class);
    $dataProducts = $dataRepository->findAll();
    return $this->render('product/index.html.twig', compact('dataProducts'));
  }

  #[Route('/api/product', name:'api_create_product', methods:['POST'])]
function createProduct(Request $request): Response
  {
  // $data = json_decode($request->getContent(), true);
  try {
    $product = new Products();
    $product->setName($request->request->get('name'));
    $product->setPrice($request->request->get('price'));
    $product->setStock($request->request->get('stock'));
    $product->setCode($request->request->get('code'));

    $this->entityManager->persist($product);
    $this->entityManager->flush();

    return new JsonResponse(['message' => 'create successfully'], Response::HTTP_ACCEPTED);
  } catch (\Exception $e) {
    $data = [
      'message' => 'create failed',
      'detail' => $e,
    ];
    $response = new JsonResponse($data, Response::HTTP_INTERNAL_SERVER_ERROR);
    return $response;
  }
}

#[Route('/api/product/{id}', name:'api_update_product', methods:['PUT'])]
function updateProduct(Request $request, int $id): Response
  {
  $data = json_decode($request->getContent(), true);

  try {
    $product = $this->entityManager->getRepository(Products::class)->find($id);

    if (!$product) {
      return new Response('Product not found', Response::HTTP_NOT_FOUND);
    }
    if (isset($data['name'])) {
      $product->setName($data['name']);
    }
    if (isset($data['price'])) {
      $product->setPrice($data['price']);
    }
    if (isset($data['stock'])) {
      $product->setStock($data['stock']);
    }
    if (isset($data['code'])) {
      $product->setCode($data['code']);
    }

    $this->entityManager->flush();

    return new JsonResponse(['message' => 'update successfuly'], Response::HTTP_ACCEPTED);
  } catch (\Exception $e) {
    $data = [
      'message' => 'update failed',
      'detail' => $e,
    ];
    return new JsonResponse($data, Response::HTTP_INTERNAL_SERVER_ERROR);
  }
}

#[Route('/api/product/{id}', name:'api_delete_product', methods:['DELETE'])]
function deleteProduct(int $id): Response
  {
  $product = $this->entityManager->getRepository(Products::class)->find($id);

  try {
    if (!$product) {
      return new Response('Product not found', Response::HTTP_NOT_FOUND);
    }

    $this->entityManager->remove($product);
    $this->entityManager->flush();

    return new JsonResponse(['message' => 'delete successfully'], Response::HTTP_ACCEPTED);
  } catch (\Exception $e) {
    $data = [
      'message' => 'delete failed',
      'detail' => $e,
    ];
    return new JsonResponse($data, Response::HTTP_INTERNAL_SERVER_ERROR);
  }
}
}
