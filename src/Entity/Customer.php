<?php

namespace App\Entity;

use App\Repository\CustomerRepository;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Groups;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation\Since;

/**
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "app_customers_id",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups="getCustomers")
 * )
 * 
 * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "app_customers_delete",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 *      attributes = { "method" = "DELETE" },
 *      exclusion = @Hateoas\Exclusion(groups="getCustomers", excludeIf = "expr(not is_granted('ROLE_USER'))")
 * )
 *
 * @Hateoas\Relation(
 *      "update",
 *      href = @Hateoas\Route(
 *          "app_customers_update",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 *      attributes = { "method" = "PUT" },
 *      exclusion = @Hateoas\Exclusion(groups="getCustomers", excludeIf = "expr(not is_granted('ROLE_USER'))")
 * )
 *
 * 
 * @Hateoas\Relation(
 *      "delete",
 *      href = @Hateoas\Route(
 *          "app_customers_delete",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 *      attributes = { "method" = "DELETE" },
 *      exclusion = @Hateoas\Exclusion(groups="getCustomer", excludeIf = "expr(not is_granted('ROLE_USER'))")
 * )
 *
 * @Hateoas\Relation(
 *      "update",
 *      href = @Hateoas\Route(
 *          "app_customers_update",
 *          parameters = { "id" = "expr(object.getId())" }
 *      ),
 *      attributes = { "method" = "PUT" },
 *      exclusion = @Hateoas\Exclusion(groups="getCustomer", excludeIf = "expr(not is_granted('ROLE_USER'))")
 * )
 */
#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getCustomers"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getClient", "getCustomer", "getCustomers"])]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Groups(["getClient", "getCustomer", "getCustomers"])]
    #[Since("2.0")]
    private ?string $password = null;

    #[ORM\ManyToOne(inversedBy: 'customers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }
}
