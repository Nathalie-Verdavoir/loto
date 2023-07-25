<?php

namespace App\Entity;

use App\Repository\ResultRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedPath;

#[ORM\Entity(repositoryClass: ResultRepository::class)]
class Result
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['show_result'])]
    #[SerializedPath('[annee_numero_de_tirage]')]
    private ?string $anneeNumeroDeTirage = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['show_result'])]
    #[SerializedPath('[date_de_tirage]')]
    private ?string $dateDeTirage = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['show_result'])]
    #[SerializedPath('[boule_1]')]
    private ?int $boule1 = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['show_result'])]
    #[SerializedPath('[boule_2]')]
    private ?int $boule2 = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['show_result'])]
    #[SerializedPath('[boule_3]')]
    private ?int $boule3 = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['show_result'])]
    #[SerializedPath('[boule_4]')]
    private ?int $boule4 = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['show_result'])]
    #[SerializedPath('[boule_5]')]
    private ?int $boule5 = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['show_result'])]
    #[SerializedPath('[numero_chance]')]
    private ?int $numero_chance = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['show_result'])]
    #[SerializedPath('[boule_1_second_tirage]')]
    private ?int $boule1SecondTirage = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['show_result'])]
    #[SerializedPath('[boule_2_second_tirage]')]
    private ?int $boule2SecondTirage = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['show_result'])]
    #[SerializedPath('[boule_3_second_tirage]')]
    private ?int $boule3SecondTirage = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['show_result'])]
    #[SerializedPath('[boule_4_second_tirage]')]
    private ?int $boule4SecondTirage = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['show_result'])]
    #[SerializedPath('[boule_5_second_tirage]')]
    private ?int $boule5SecondTirage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnneeNumeroDeTirage(): ?string
    {
        return $this->anneeNumeroDeTirage;
    }

    public function setAnneeNumeroDeTirage(string $anneeNumeroDeTirage): static
    {
        $this->anneeNumeroDeTirage = $anneeNumeroDeTirage;

        return $this;
    }

    public function getDateDeTirage(): ?string
    {
        return $this->dateDeTirage;
    }

    public function setDateDeTirage(?string $dateDeTirage): static
    {
        $this->dateDeTirage = $dateDeTirage;

        return $this;
    }

    public function getBoule1(): ?int
    {
        return $this->boule1;
    }

    public function setBoule1(int $boule1): static
    {
        $this->boule1 = $boule1;

        return $this;
    }

    public function getBoule2(): ?int
    {
        return $this->boule2;
    }

    public function setBoule2(int $boule2): static
    {
        $this->boule2 = $boule2;

        return $this;
    }

    public function getBoule3(): ?int
    {
        return $this->boule3;
    }

    public function setBoule3(int $boule3): static
    {
        $this->boule3 = $boule3;

        return $this;
    }

    public function getBoule4(): ?int
    {
        return $this->boule4;
    }

    public function setBoule4(int $boule4): static
    {
        $this->boule4 = $boule4;

        return $this;
    }

    public function getBoule5(): ?int
    {
        return $this->boule5;
    }

    public function setBoule5(int $boule5): static
    {
        $this->boule5 = $boule5;

        return $this;
    }

    public function getNumeroChance(): ?int
    {
        return $this->numero_chance;
    }

    public function setNumeroChance(int $numero_chance): static
    {
        $this->numero_chance = $numero_chance;

        return $this;
    }

    public function getBoule1SecondTirage(): ?int
    {
        return $this->boule1SecondTirage;
    }

    public function setBoule1SecondTirage(int $boule1SecondTirage): static
    {
        $this->boule1SecondTirage = $boule1SecondTirage;

        return $this;
    }

    public function getBoule2SecondTirage(): ?int
    {
        return $this->boule2SecondTirage;
    }

    public function setBoule2SecondTirage(int $boule2SecondTirage): static
    {
        $this->boule2SecondTirage = $boule2SecondTirage;

        return $this;
    }

    public function getBoule3SecondTirage(): ?int
    {
        return $this->boule3SecondTirage;
    }

    public function setBoule3SecondTirage(int $boule3SecondTirage): static
    {
        $this->boule3SecondTirage = $boule3SecondTirage;

        return $this;
    }

    public function getBoule4SecondTirage(): ?int
    {
        return $this->boule4SecondTirage;
    }

    public function setBoule4SecondTirage(int $boule4SecondTirage): static
    {
        $this->boule4SecondTirage = $boule4SecondTirage;

        return $this;
    }

    public function getBoule5SecondTirage(): ?int
    {
        return $this->boule5SecondTirage;
    }

    public function setBoule5SecondTirage(int $boule5SecondTirage): static
    {
        $this->boule5SecondTirage = $boule5SecondTirage;

        return $this;
    }
}
