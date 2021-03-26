<?php

namespace App\Controller;

use App\Main;
use App\Entity\User;
use App\Entity\Depot;
use App\Entity\Agence;
use App\Entity\Compte;
use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CompteController extends AbstractController
{

    /**
     * @Route(
     *  "/api/admin/comptes",
     *  name="addcompte",
     *  methods = {"POST"},
     *  defaults={
     *      "_api_resource_class" = Compte::class,
     *      "_api_collection_operation_name" = "addcompte"
     *  }
     * )
     */
    public function createCompte(TokenStorageInterface $tokenStorage0, DenormalizerInterface $denormalizer, EntityManagerInterface $manager, Request $request)
    {
        $newCompte = json_decode($request->getContent(), true);
        $agence = $manager->getRepository(Agence::class)->findOneBy(['nomAgence' => $newCompte['agence']]);
        $userCreateur = $tokenStorage0->getToken()->getUser();
        if ($agence){

            if ($newCompte['solde']>=700000) {

                $compte = new Compte();
                $compte->setAgence($agence);
                $compte->setSolde($newCompte['solde']);
                $rand = rand(123567890, 999999999);
                $compte->setNumcompte($rand);
                $compte->setUser($userCreateur);

                $manager->persist($compte);
                $manager->flush();

                $data = [
                    'status' => 200,
                    'message' => 'Créer avec succés'
                ];
                return new JsonResponse($data, 200);

            } else {

                $data = [
                    'status' => 400,
                    'message' =>  " Veuillez saisi un montant supérieur à 700000"
                ];
                return new JsonResponse($data, 400);
            }
        }else{
            $data = [
                'status' => 500,
                'message' =>  " Cet nom d'agence n'existe pas"
            ];
            return new JsonResponse($data, 500);
        }

    }

    /**
     * @Route(
     *  "/api/admin/depot",
     *  name="add_depot",
     *  methods = {"POST"}
     * )
     */
    public function addDepot(TokenStorageInterface $tokenStorage0, EntityManagerInterface $manager, Request $request)
    {
        $values = json_decode($request->getContent(), true);
        
        
        if ($values["montantDepot"]>0) {
            $dateJours = new \DateTime();
            $depot = new Depot();
            
            $compteDepot = $manager->getRepository(Compte::class)->findOneBy(['numCompte' => $values["compte"]]);

            if ($compteDepot->getArchive() === false) {

                $userCreateur = $tokenStorage0->getToken()->getUser();

                    $depot->setDateDepot($dateJours);
                    $depot->setMontantDepot($values["montantDepot"]);
                    $depot->setUser($userCreateur);
                    $depot->setCompte($compteDepot);
                                            
                    $manager->persist($depot);
                    $manager->flush();

                    // update solde du compte Agence
                    $NewSolde = ($values["montantDepot"]+$compteDepot->getSolde());
                    $compteDepot->setSolde($NewSolde);

                    $manager->persist($compteDepot);
                    $manager->flush();
                                    
                    $data = [
                            'status' => 201,
                                'message' => 'Merci vous avez fait un depot de:'.$values["montantDepot"]
                            ];
                    return new JsonResponse($data, 201);

            } else {
                $data = [
                        'status' => 500,
                            'message' => "Ce numéro de compte n'existe pas"
                    ];
                return new JsonResponse($data, 500);
            }
        }
    
        $data = [
            'status' => 500,
                'message' => ' Veuillez saisi un montant valide'
        ];
        return new JsonResponse($data, 500);
    }
    
 

    /**
     * @Route(
     *  "/api/admin/annulation/{id}",
     *  name="cancel_warehouse",
     *  methods = {"DELETE"},
     *  defaults={
     *      "_api_resource_class" = Depot::class,
     *      "_api_item_operation_name" = "cancel_warehouse"
     *      }
     * )
     */
    public function cancelWarehouse( EntityManagerInterface $manager, Request $request )
    {
        $values = $request->attributes->get('data');
        $last_id = $manager->getRepository(Depot::class)->findOneBy([], ['id' => 'desc'], 1, 0);
        
        if ($last_id->getId() == $values->getId()) {
            
            $compteDepot = $manager->getRepository(Compte::class)->findOneBy(['id' => $values->getCompte()->getId() ]);
            if ($values->getMontantDepot() <= $compteDepot->getSolde()) {
            
                $NewSolde = ($compteDepot->getSolde() - $values->getMontantDepot());
                $compteDepot->setSolde($NewSolde);
                
                $compteDepot->removeDepot($values);
                $manager->persist($compteDepot);
                $manager->flush();

                  
                $data = [
                        'status' => 200,
                            'message' => 'Votre dépôt a été annulé avec succés'
                        ];
    
            } else {
    
                $data = [
                    'status' => 500,
                        'message' => ' L\'annulation ne peut pas se faire car le montant deposer n\'est plus disponible'
                ];
                return new JsonResponse($data, 500);
            }
            
        } else {

            $data = [
                'status' => 500,
                    'message' => ' L\'annulation ne peut pas se faire car le dernier dépôt est différent'
            ];
            return new JsonResponse($data, 500);
        }      
       
    }
}
