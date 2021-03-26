<?php

namespace App\Controller;

use App\Entity\Agence;
use App\Main;
use App\Entity\User;
use App\Entity\Profil;
use App\Repository\CompteRepository;
use App\Repository\ProfilRepository;
use App\Service\MyService;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class UserController extends AbstractController
{
    /**
     * @Route(
     *  "/api/admin/users",
     *  name="post_user",
     *  methods = {"POST"},
     *  defaults={
     *      "_api_resource_class" = User::class,
     *      "_api_collection_operation_name" = "post_user"
     *  }
     * )    
     */
    public function addUser(MyService $serve, DenormalizerInterface $denormalizer, EntityManagerInterface $manager, ValidatorInterface $validator, Request $request, UserPasswordEncoderInterface $encoder)
    {
        //$newUser = json_decode($request->getContent(), true);
        $newUser = $request->request->all();
        $uploadedFile = $request->files->get('avatar');

        $newUser['avatar'] = $serve->upload($uploadedFile);
        $newUser['avatarType'] = $serve->type($uploadedFile);
        $profil = ucfirst(strtolower($newUser['profil']));
        $profilName = "App\\Entity\\$profil";
        
        if (class_exists($profilName)) {
            $agenceObject = $manager->getRepository(Agence::class)->findOneBy(['nomAgence' => $newUser['agence'] ]);
           
            if ($agenceObject) { 
                
                $profilObject = $manager->getRepository(Profil::class)->findOneBy(['libelle' => $profil ]);
                unset($newUser['profil'], $newUser['agence']);
                $user = $denormalizer->denormalize($newUser, User::class, $profilName);
                $user->setAgence($agenceObject);
                $user->setProfil($profilObject);
    
            } else {
    
                throw new HttpException('ce nom agence n\'existe pas !');
    
            }


            $user->setPhone(rand(770000000, 779999999));
            $user->setCNI(strval(rand(10000000, 279999999)));
            $user->setPassword($encoder->encodePassword($user, $newUser['password']));
            $errors = $validator->validate($newUser);

            if (count($errors) > 0) {
                $errorsString = $errors;

                return new Response($errorsString);

            }
            $manager->persist($user);
            $manager->flush();

            return new JsonResponse("success", Response::HTTP_CREATED, [], true);

        } else {

            return new BadRequestHttpException("Ce profil n'éxiste pas");
        }
        
    }

    /**
     * @Route(
     * "/api/admin/users/{id}",
     *  name="update_user",
     *  methods={"PUT"},
     *  defaults={
     *      "_api_resource_class" = User::class,
     *      "_api_item_operation_name" = "update_user"
     *      }
     *    )
     */
    public function updateUser(MyService $serve, Request $request, UserRepository $repo, EntityManagerInterface $manager){

        //Récuperation de l'objet dans la base de données
        $userData = $request->attributes->get('data');
        $userId = $request->attributes->get("id");

        $data = $serve->putData($request, 'avatar');
        $user = $repo->find($userId);
        if (!$user) {
            new Response("l'utilisateurs non trouvée avec l’id " . $userId);
        } else {

            foreach ($data as $k => $v) {
                $setter = 'set' . ucfirst($k);

                if (!method_exists($userData, $setter)) {
                    return new Response("La méthode $setter() n'éxiste pas dans l'entité User");

                }
                $userData->$setter($v);
            }
            $manager->persist($userData);
            $manager->flush();

            return new JsonResponse("success", Response::HTTP_CREATED, [], true);

        }
    }

    /**
     * @Route(
     * "/api/admin/users/{id}",
     *  name="delete_user",
     *  methods={"DELETE"},
     *  defaults={
     *      "_api_resource_class" = User::class,
     *      "_api_item_operation_name" = "delete_user"
     *      }
     *    )
     */
    public function delUser(Request $request, EntityManagerInterface $manager, TokenStorageInterface $tokenStorage)
    {
        $user  = $request->attributes->get('data');
        $Userconnecte = $tokenStorage->getToken()->getUser();
        
        if (!$user) {

            throw new HttpException("Cet utilisateur n'existe pas !");
        }
        if ($user==$Userconnecte) {

            throw new HttpException('Impossible de se bloquer soit même !');

        } elseif ($Userconnecte->getRoles()[0]=='ROLE_ADMINAGENCE') {

            throw new HttpException('Impossible de bloquer l\'admin de l\'agence !');
        }
        if ($Userconnecte->getRoles()[0]=='ROLE_Admin') {
            
            throw new HttpException('Impossible de bloquer l\' admin principal !');
        }
        
        
        $user->setArchive(true);
        $manager->persist($user);
        $manager->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route(
     * "/api/admin/users/desarchive/{id}",
     *  name="desarchive_user",
     *  methods={"DELETE"},
     *  defaults={
     *      "_api_resource_class" = User::class,
     *      "_api_item_operation_name" = "desarchive_user"
     *      }
     *    )
     */
    public function desarchiveUser(Request $request, EntityManagerInterface $manager)
    {
        $ref = $request->attributes->get('data');
        $ref->setArchive(false);
        $manager->persist($ref);
        $manager->flush();
        return new JsonResponse("success", Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route(
     * "/api/admin/users",
     *  name="get_users",
     *  methods={"GET"},
     *  defaults={
     *      "_api_resource_class" = User::class,
     *      "_api_collection_operation_nam" = "get_users"
     *      }
     *    )
     */
    public function getUsers(UserRepository $repo, Main $method)
    {

        
        return ($method->getAllUser($repo));

    }

   // /**
     //* @Route(
     //* "@Groups({"user:read"})",
    // *  name="get_user",
    // *  methods={"GET"},
     //*  defaults={
     //*      "_api_resource_class" = User::class,
     //*      "_api_item_operation_nam" = "get_user"
     //*      }
     //*    )
     //*/
   // public function getOneUsers(UserRepository $repo, Request $request, Main $method)
    //{

        //$userId = $request->attributes->get("id");

      //  return($method->getOneUser($repo, $userId));
    //}

    /**
     * @Route(
     * "/api/admin/users/solde/{id}",
     *  name="get_solde",
     *  methods={"GET"},
     *  defaults={
     *      "_api_resource_class" = User::class,
     *      "_api_item_operation_nam" = "get_solde"
     *      }
     *    )
     */
    public function getSolde(UserRepository $repo, Request $request, Main $method, CompteRepository $compte_repo)
    {

        $userId = $request->attributes->get("id");
        return ($method->getSolde($compte_repo, $userId));
    }

    /**
     * @Route(
     * "/api/admin/usersdeleted",
     *  name="get_users_deleted",
     *  methods={"GET"},
     *  defaults={
     *      "_api_resource_class" = User::class,
     *      "_api_collection_operation_nam" = "get_users_deleted"
     *      }
     *    )
     */
    public function getDeletedUsers(UserRepository $repo, Main $method){


        return ($method->getDeleted($repo));

    }

    /**
     * @Route(
     * "/api/admin/profils",
     *  name="get_profil",
     *  methods={"GET"},
     *  defaults={
     *      "_api_resource_class" = Profil::class,
     *      "_api_collection_operation_nam" = "get_profil"
     *      }
     *    )
     */
    public function getProfil(ProfilRepository $repo, Main $method){


        return ($method->getAllProfil($repo));

    }
}
