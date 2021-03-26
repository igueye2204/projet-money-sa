<?php

namespace App\Controller;

use App\Main;
use App\Entity\Agence;
use App\Repository\AgenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AgenceController extends AbstractController
{
    /**
     * @Route(
     * "/api/admin/agence/{id}",
     *  name="bloque_agence",
     *  methods={"DELETE"},
     *  defaults={
     *      "_api_resource_class" = Agence::class,
     *      "_api_item_operation_name" = "bloque_agence"
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

    /**
     * @Route(
     * "/api/admin/agences",
     *  name="get_agence",
     *  methods={"GET"},
     *  defaults={
     *      "_api_resource_class" = Agence::class,
     *      "_api_collection_operation_nam" = "get_agence"
     *      }
     *    )
     */
    public function getAgence(AgenceRepository $repo, Main $method)
    {

        
        return ($method->getAllAgence($repo));

    }

    /**
     * @Route(
     * "/api/admin/agences",
     *  name="create_agence",
     *  methods={"POST"},
     *  defaults={
     *      "_api_resource_class" = Agence::class,
     *      "_api_collection_operation_nam" = "create_agence"
     *      }
     *    )
     */
    public function createAgence(AgenceRepository $repo, EntityManagerInterface $manager, Request $request)
    {

        $newCompte = json_decode($request->getContent(), true);
        $agence = $manager->getRepository(Agence::class)->findOneBy(['nomAgence' => $newCompte['agence']]);
        if ($agence){
            $data = [
                'status' => 500,
                'message' => 'Désolé! ce nom d\'agence existe déja! '
            ];
            return new JsonResponse($data, 500);
        }else{

            $newAgence = new Agence();
            $newAgence->setNomAgence($newCompte['agence']);
            $newAgence->setAddressAgence($newCompte['addressAgence']);
            $manager->persist($newAgence);
            $manager->flush();
            $data = [
                'status' => 200,
                'message' => 'Créer avec succés! '
            ];
            return new JsonResponse($data, 200);
        }

    }
}
