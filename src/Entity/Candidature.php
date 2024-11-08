<?php


namespace App\Entity;

use App\Repository\CandidatureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;

#[ORM\Entity(repositoryClass: CandidatureRepository::class)]
class Candidature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le CIN ne doit pas être vide.")]
    #[Assert\Type(type: "integer", message: "Le CIN doit être un nombre entier.")]
    #[Assert\Regex(
        pattern: "/^\d{8}$/",
        message: "Le CIN doit être exactement 8 chiffres."
    )]
    private ?int $cin = null;
    

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Le nom et prénom ne doivent pas être vides.")]
    #[Assert\Regex(
        pattern: "/^[A-Za-z\s]+$/",
        message: "Le nom et prénom doivent contenir uniquement des lettres et des espaces."
    )]
    private ?string $nomprenom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\Type(type: \DateTimeInterface::class, message: "Format de date invalide.")]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'adresse ne doit pas être vide.")]
    private ?string $adresse = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\Type(type: \DateTimeInterface::class, message: "Format de date invalide.")]
    private ?\DateTimeInterface $anne_maitrise = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "La moyenne ne doit pas être vide.")]
    #[Assert\Type(type: "float", message: "La moyenne doit être un nombre à virgule.")]
    #[Assert\Range(min: 0, max: 20, notInRangeMessage: "La moyenne doit être comprise entre 0 et 20.")]
    private ?float $moyenne = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le nombre d'années d'expérience ne doit pas être vide.")]
    #[Assert\Type(type: "integer", message: "Le nombre d'années d'expérience doit être un nombre entier.")]
    #[Assert\GreaterThanOrEqual(value: 0, message: "Le nombre d'années d'expérience doit être positif.")]
    private ?int $nbranneexper = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le diplôme ne doit pas être vide.")]
    #[Assert\Type(type: "string", message: "uniquement des letters ")]
    private ?string $diplome = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "La spécialité ne doit pas être vide.")]
    #[Assert\Type(type: "string", message: "uniquement des letters ")]
    private ?string $specialite = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "L'université ne doit pas être vide.")]
    private ?string $universite = null;

    #[ORM\Column(length: 255)]
   
    private ?string $fichier = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "L'email ne doit pas être vide.")]
    #[Assert\Email(message: "Adresse email invalide.")]
    private ?string $email = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Le numéro de téléphone ne doit pas être vide.")]
    #[Assert\Type(type: "integer", message: "Le numéro de téléphone doit être un nombre entier.")]
    #[Assert\Length(min: 8, max: 8, minMessage: "Le numéro de téléphone doit comporter 8 chiffres.", maxMessage: "Le numéro de téléphone ne peut pas dépasser 8 chiffres.")]
    private ?int $numtel = null;

    #[ORM\ManyToOne(inversedBy: 'candidatures')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: "string", length: 20)]
    private ?string $status = 'pending';  // Default status is 'pending'
    
   
    
    public function getId(): ?int
    {
        return $this->id;
    }

    // Add getter and setter for the User

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }


    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(int $cin): static
    {
        $this->cin = $cin;
        return $this;
    }

    public function getNomprenom(): ?string
    {
        return $this->nomprenom;
    }

    public function setNomprenom(string $nomprenom): static
    {
        $this->nomprenom = $nomprenom;
        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getAnneMaitrise(): ?\DateTimeInterface
    {
        return $this->anne_maitrise;
    }

    public function setAnneMaitrise(\DateTimeInterface $anne_maitrise): static
    {
        $this->anne_maitrise = $anne_maitrise;
        return $this;
    }

    public function getMoyenne(): ?float
    {
        return $this->moyenne;
    }

    public function setMoyenne(float $moyenne): static
    {
        $this->moyenne = $moyenne;
        return $this;
    }

    public function getNbranneexper(): ?int
    {
        return $this->nbranneexper;
    }

    public function setNbranneexper(int $nbranneexper): static
    {
        $this->nbranneexper = $nbranneexper;
        return $this;
    }

    public function getDiplome(): ?string
    {
        return $this->diplome;
    }

    public function setDiplome(string $diplome): static
    {
        $this->diplome = $diplome;
        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): static
    {
        $this->specialite = $specialite;
        return $this;
    }

    public function getUniversite(): ?string
    {
        return $this->universite;
    }

    public function setUniversite(string $universite): static
    {
        $this->universite = $universite;
        return $this;
    }

    public function getFichier(): ?string
    {
        return $this->fichier;
    }

    public function setFichier(string $fichier): static
    {
        $this->fichier = $fichier;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getNumtel(): ?int
    {
        return $this->numtel;
    }

    public function setNumtel(int $numtel): static
    {
        $this->numtel = $numtel;
        return $this;
    }

   public function getStatus(): ?string
{
    return $this->status;
}

public function setStatus(string $status): static
{
    $this->status = $status;
    return $this;
}




}
