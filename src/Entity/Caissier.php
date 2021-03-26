<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CaissierRepository;
use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=CaissierRepository::class)
 * @ApiResource(
 *  collectionOperations = {
 *      "get"={
 *          "path"   = "/admin/caissiers",
 *          "method" = "get",
 *          "security" = "(is_granted('ROLE_ADMIN'))",
 *          "security_message" = "Accès refusé!"
 *     },
 *     "addcaissier" = {
 *          "method" = "post",
 *     "deserialize" = false,
 * }
 *  },
 *  itemOperations = {
 *     "get",
 *      "delete_caissier" = {
 *          "method" = "delete",
 *     "deserialize" = false,
 *        "security" = "(is_granted('ROLE_ADMIN')",
 *         "security_message" = "Accès refusé!"
 * }
 *  }
 * )
 */
class Caissier extends User
{
    
}
