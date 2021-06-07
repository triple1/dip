<?php declare(strict_types=1);

namespace App\Controller;

use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class BaseController extends AbstractController
{
    protected function getRepository(string $entityName): ObjectRepository
    {
        return $this->getDoctrine()->getManager()->getRepository($entityName);
    }
}
