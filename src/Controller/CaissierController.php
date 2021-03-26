<?php

namespace App\Controller;

use App\Main;
use App\Repository\CaissierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CaissierController extends AbstractController
{
    /**
     * @Route(
     *  "/api/admin/caissiers",
     *  name="show_caissier",
     *  methods = {"GET"}
     * )
     */
    public function showAllCaissier( CaissierRepository $repoCaissier, Main $method )
    {
           
        return ($method->getAllPart($repoCaissier));      

    }

    /**
     * @Route(
     * "/api/admin/caissier/{id}",
     *  name="delete_caissier",
     *  methods={"DELETE"},
     *  defaults={
     *      "_api_resource_class" = Caissier::class,
     *      "_api_item_operation_name" = "delete_caissier"
     *      }
     *    )
     */
    public function bloqueAgence(Request $request, EntityManagerInterface $manager)
    {
        $ref = $request->attributes->get('data');
        $ref->setStatus(true);
        $manager->persist($ref);
        $manager->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }
}
