<?php

namespace App\DataFixtures;

use App\Entity\Products;
use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
  public function load(ObjectManager $manager): void
  {
    $data_products = [
      "name" => ['shampo', 'pencil'],
      "price" => [12500, 3200],
      "stock" => [8, 3],
      "code" => ['sh444p0', '2131bbd'],
    ];

    $data_users = [
      "name" => ['kumamba ardhi'],
      "password" => ['testpass'],
      "username" => ['kurdi_'],
    ];

    foreach ($data_products['name'] as $key => $value) {
      $products = new Products();
      $products->setName($value);
      $products->setPrice($data_products['price'][$key]);
      $products->setStock($data_products['stock'][$key]);
      $products->setCode($data_products['code'][$key]);
      $manager->persist($products);
    }

    foreach($data_users['name'] as $key => $value){
      $users = new Users();
      $users->setName($value);
      $users->setPassword($data_users['password'][$key]);
      $users->setUsername($data_users['username'][$key]);
      $manager->persist($users);
    }

    $manager->flush();
  }
}
