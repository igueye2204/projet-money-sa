<?php

namespace App\Controller;

use App\Entity\Agence;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserAgenceController extends AbstractController
{
   
    /**
     * @Route(
     * "/api/adminagence/useragence/{id}",
     *  name="bloque_useragence",
     *  methods={"DELETE"},
     *  defaults={
     *      "_api_resource_class" = Agence::class,
     *      "_api_item_operation_name" = "bloque_useragence"
     *      }
     *    )
     */
    public function bloqueUserAgence(Request $request, EntityManagerInterface $manager)
    {
        $ref = $request->attributes->get('data');
        $ref->setArchive(true);
        $manager->persist($ref);
        $manager->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }
}
