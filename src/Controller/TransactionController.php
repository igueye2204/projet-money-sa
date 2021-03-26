<?php

namespace App\Controller;

use App\Entity\Infotransaction;
use App\Main;
use App\Entity\User;
use App\Entity\Client;
use App\Entity\Compte;
use App\Entity\Transaction;
use App\Repository\InfotransactionRepository;
use App\Service\CalculateurFrais;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TransactionRepository;
use App\Repository\TableauDesFraisRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class TransactionController extends AbstractController
{ 



   /**
    * @Route(
    *  "/api/useragence/transaction/send",
    *  name="make_shipment",
    *  methods = {"POST"},
    * defaults={
    *      "_api_resource_class" = Transaction::class,
    *      "_api_collection_operation_name" = "make_shipment"
    *      }
    * )
    */
   public function makeShipment( CalculateurFrais $calculatrice, EntityManagerInterface $manager, Request $request, TokenStorageInterface $tokenStorage, TableauDesFraisRepository $repo )
   {

         /*-------------Recupération des données fournies------------*/

            $values = json_decode($request->getContent());

         /*--------Recupération de toutes les informations du User connecté------*/
            
         $userCreateur = $tokenStorage->getToken()->getUser();

       foreach ($userCreateur->getComptes() as $valueCompte) {

           $ibouss[] = $valueCompte;

       }

       /*--------enregistrement des données du client qui fais l'envoi----------*/

        $client = new Client;
        
        $client->setNom($values[0]->nom);
        $client->setPrenom($values[0]->prenom);
        $client->setPhone($values[0]->telephone);
        $client->setCNI($values[0]->cni);
        $client->setAction('envoi');

        $manager->persist($client);
        $manager->flush();

        $client2 = new Client;
        $client2->setNom($values[1]->nom);
        $client2->setPrenom($values[1]->prenom);
        $client2->setPhone($values[1]->telephone);
        $client2->setAction('retrait');

        $manager->persist($client2);
        $manager->flush();
        
         /*----------------------Montant à envoyer----------------------*/

        $transaction = new Transaction; 
        $transaction->setMontant($values[0]->montant);
        
        /*----------------------Parts des commissions et Taxes-----------------*/

        $commission = $calculatrice->Calculatrice($values[0]->montant);
         //$tt_frais = $repo->findAll();

        $transaction->setFraisEtat($commission * 0.4);
        $transaction->setFraisSystem($commission * 0.3);
        $transaction->setFraisEnvoie($commission * 0.1);
        $transaction->setFraisretrait($commission * 0.2);

         $ttc = $commission+$values[0]->montant;
         $transaction->setTTC($ttc);

         /*----------------------date du depot----------------------*/

         $dateJours = new \DateTime();
         $transaction->setDateDepot($dateJours);

         /*--faire un envoi (il débit le compte de l'agence qui effectue l'envoi)--*/
         
         $transaction->setCompte($ibouss[0]);
         $transaction->setDeposer($userCreateur);
         $transaction->setEnvoyer($client);
         $transaction->setRecuperer($client2);

         /*---------------update solde du compte Agence envoyeur------------*/

         $NewSolde = ( $ibouss[0]->getSolde() - ($values[0]->montant+($commission * 0.9 )) );
         $ibouss[0]->setSolde($NewSolde);
         

         $manager->persist($ibouss[0]);
         $manager->flush();

         //=============== envoi des informations utiles a l'envoi ==============//

         //generation code de transfert
         $code = rand(000000001, 999999999);
         $transaction->setCodeTransaction($code);

          $manager->persist($transaction);

       /*--------enregistrement des données du transaction----------*/

       $info = new Infotransaction();

       $info->setPrenomClient($values[0]->prenom);
       $info->setNomclient($values[0]->nom);
       $info->setCompte($ibouss[0]->getId());
       $info->setMontant($values[0]->montant);
       $info->setType('envoi');
       $info->setUser($userCreateur->getId());
       $info->setFrais($commission * 0.1);
       $info->setCodeTransaction($code);
       $info->setDateTransaction($dateJours);

       $manager->persist($info);
          $manager->flush();

          $data = [
            'status' => 201,
                'message' =>  $code
            ];
            return new JsonResponse($data, 201);
    
    }

    /**
     * @Route(
     *  "/api/useragence/calculateur",
     *  name="calculator",
     *  methods = {"POST"},
     * defaults={
     *      "_api_resource_class" = Transaction::class,
     *      "_api_collection_operation_name" = "calculator"
     *      }
     * )
     */
    public function calculator( CalculateurFrais $calculatrice,Request $request, TableauDesFraisRepository $repo )
    {
        /*-------------Recupération des données fournies------------*/

        $values = json_decode($request->getContent());

        /*-------------Calculateur des frais------------*/

        $commission = $calculatrice->Calculatrice($values->montant);

        $data = [
            'Frais' =>   $commission
        ];
        return new JsonResponse($data, 201);
    }

    /**
     * @Route(
     *  "/api/useragence/transaction/checkcode",
     *  name="check_code",
     *  methods = {"POST"}
     * )
     */
     public function checkCode( TransactionRepository $repotransaction, Request $request, Main $method )
     {
        
         /*-------------Recupération des données fournies------------*/
            
          $values = json_decode($request->getContent());

        /*--Verifion si le code transaction existe sur la base de donnée--*/
            
        $checkCode = $repotransaction->findBy(['codeTransaction'=>$values->codeTransaction ]);
        

        if ($checkCode && $checkCode[0]->getArchive()==false ) {
            
            
            return $method->getTransactionByCode($repotransaction, $values->codeTransaction);
            
        } else {

            $data = [
               'status' => 500,
                   'message' => " Ce code de transaction n'est pas valide! merci d'essayer un notre code"
            ];

            return new JsonResponse($data, 500);

        }

     }


   /**
    * @Route(
    *  "/api/useragence/transaction/collection/{id}",
    *  name="make_withdrawal",
    *  methods = {"PUT"},
    *   defaults={
    *      "_api_resource_class" = Transaction::class,
    *      "_api_item_operation_name" = "make_withdrawal"
    *      }
    *    )
    * )
    */
    public function withdraw( ClientRepository $repoClient, CalculateurFrais $calculatrice,TransactionRepository $repotransaction, EntityManagerInterface $manager, Request $request, TokenStorageInterface $tokenStorage0 )
    {
         /*-------------Recupération des données fournies------------*/
            
         $values = json_decode($request->getContent());
         $IdTransation = $request->attributes->get("id");
         $transaction = $request->attributes->get('data');

         $user = $repotransaction->find($IdTransation);

        if (!$user) {

            new Response("il n'existe pas de transaction avec l’id " . $IdTransation);

        } elseif ($transaction->getArchive() == false) {

             /*--------Recupération de toutes les informations du User connecté------*/
            
            $userCreateur = $tokenStorage0->getToken()->getUser();
            

            foreach ($userCreateur->getComptes() as $value) {
                    
                $valueCompte[] = $value;

            }   
               
            /*------Comparaison des données du client qui doit recevoir--------*/

            $ClientSaved = $repoClient->findBy(['id' => $transaction->getRecuperer()->getId() ]);
            if ($ClientSaved[0]->getNom() == $values->nom && $ClientSaved[0]->getPrenom() == $values->prenom  && $ClientSaved[0]->getPhone() == $values->phone ) {



                /*----------------------date du retrait----------------------*/

                $dateJours = new \DateTime();
                $transaction->setDateRetrait($dateJours);

                $ClientSaved[0]->setCNI($values->cni);
                $ClientSaved[0]->setMontant($values->montant);
                $ClientSaved[0]->setDate($dateJours);
                $manager->persist($ClientSaved[0]);
                $manager->flush();
                
                /*faire un retrait(il crédit le compte de l'agence qui effectue l'envoi)*/
            
                $transaction->setCompte($valueCompte[0]);
                $transaction->setRetrait($userCreateur);
                $transaction->setArchive(true);


                $manager->persist($transaction);
                $manager->flush();

                /*-----------------Parts de commission pour le retrait---------------*/
                
                $commission = $calculatrice->Calculatrice($values->montant);
                //$tt_frais = $repo->findAll();
    
                /*---------------update solde du compte Agence envoyeur------------*/

                $NewSolde = ( $valueCompte[0]->getSolde() + ($values->montant+($commission * 0.2)) );
                $valueCompte[0]->setSolde($NewSolde);

                $info = new Infotransaction();

                $info->setNomclient($ClientSaved[0]->getNom());
                $info->setPrenomClient($ClientSaved[0]->getPrenom());
                $info->setCompte($valueCompte[0]->getId());
                $info->setMontant($values->montant);
                $info->setType('retrait');
                $info->setUser($userCreateur->getId());
                $info->setFrais($commission * 0.2);
                $info->setCodeTransaction($user->getCodeTransaction());
                $info->setDateTransaction($dateJours);

                $manager->persist($info);

                $manager->persist($valueCompte[0]);
                $manager->flush();
    
                $data = [
                'status' => 201,
                    'message' =>  " Vous avez effectuer un retrait de $values->montant avec succés"
                ];
                return new JsonResponse($data, 201);

            } else {

                $data = [
                    'status' => 500,
                        'message' =>  " Cette transaction n'existe plus!"
                    ];
                    return new JsonResponse($data, 500);

            }


        } else {


            $data = [
                'status' => 400,
                'message' =>  " Les données saisies ne sont pas valide!"
            ];
            return new JsonResponse($data, 400);
        }

    }

   /**
    * @Route(
    *  "/api/admin/transaction/parts",
    *  name="show_parts",
    *  methods = {"GET"}
    * )
    */
    public function showPart( TransactionRepository $repotransaction, Request $request, Main $method )
    {      
        return ($method->getAllPart($repotransaction));
    }

    /**
     * @Route(
     *  "/api/useragence/transaction/cancel",
     *  name="cancel_transaction",
     *  methods = {"POST"},
     *   defaults={
     *      "_api_resource_class" = Transaction::class,
     *      "_api_collection_operation_name" = "cancel_transaction"
     *      }
     *    )
     * )
     */
    public function cancelTransaction( ClientRepository $repoClient, CalculateurFrais $calculatrice,TransactionRepository $repotransaction, EntityManagerInterface $manager, Request $request, TokenStorageInterface $tokenStorage0 )
    {
         /*-------------Recupération des données fournies------------*/
        
         $newUser = json_decode($request->getContent(), true);
         $values = $repotransaction->findOneBy(['codeTransaction' => $newUser['codeTransaction' ]]);
         $userCreateur = $tokenStorage0->getToken()->getUser();
         $compteDepot = $manager->getRepository(Compte::class)->findOneBy(['id' => $values->getCompte()->getId() ]);

        if ($values->getRetrait() != null) {
            
            $data = [
                'status' => 500,
                    'message' =>  " l'anulation ne peut pas se faire car le retrait a été déja éffectué! "
                ];
                return new JsonResponse($data, 500);

        } elseif ($values->getDeposer()->getId() == $userCreateur->getId()) {

            $commission = $calculatrice->Calculatrice($values->getMontant());

            $NewSolde = ($compteDepot->getSolde() - $values->getMontant() + ($commission * 0.2) );
            $compteDepot->setSolde($NewSolde);
            $manager->persist($compteDepot);
           
            $dateJours = new \DateTime();
            $values->setDateAnnulation($dateJours);   
            $values->setArchive(true);     
            $values->setFraisretrait(0);
            $manager->persist($values);

            $manager->flush();

            $data = [
                'status' => 200,
                    'message' => 'Votre dépôt a été annulé avec succés'
                ];
                return new JsonResponse($data, 200);
            
        } else {

            $data = [
                'status' => 500,
                    'message' => 'Désolé! mais vous ne pouvez pas faire l\'annulation '
                ];
                return new JsonResponse($data, 500);
        }
    }

    /**
     * @Route(
     *  "/api/admin/infotransaction_user",
     *  name="show_transaction",
     *  methods = {"GET"},
     *     defaults={
     *      "_api_resource_class" = Transaction::class,
     *      "_api_collection_operation_name" = "show_transaction"
     *      }
     * )
     */
    public function showTransaction( TokenStorageInterface $tokenStorage, InfotransactionRepository $repository, Request $request, Main $method )
    {
        $userConnecter = $tokenStorage->getToken()->getUser();
        return ($method->getTransactionById($repository, $userConnecter->getId()));
    }
}
