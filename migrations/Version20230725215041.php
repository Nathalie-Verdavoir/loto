<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230725215041 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE result CHANGE boule1 boule1 INT DEFAULT NULL, CHANGE boule2 boule2 INT DEFAULT NULL, CHANGE boule3 boule3 INT DEFAULT NULL, CHANGE boule4 boule4 INT DEFAULT NULL, CHANGE boule5 boule5 INT DEFAULT NULL, CHANGE numero_chance numero_chance INT DEFAULT NULL, CHANGE boule1_second_tirage boule1_second_tirage INT DEFAULT NULL, CHANGE boule2_second_tirage boule2_second_tirage INT DEFAULT NULL, CHANGE boule3_second_tirage boule3_second_tirage INT DEFAULT NULL, CHANGE boule4_second_tirage boule4_second_tirage INT DEFAULT NULL, CHANGE boule5_second_tirage boule5_second_tirage INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE result CHANGE boule1 boule1 INT NOT NULL, CHANGE boule2 boule2 INT NOT NULL, CHANGE boule3 boule3 INT NOT NULL, CHANGE boule4 boule4 INT NOT NULL, CHANGE boule5 boule5 INT NOT NULL, CHANGE numero_chance numero_chance INT NOT NULL, CHANGE boule1_second_tirage boule1_second_tirage INT NOT NULL, CHANGE boule2_second_tirage boule2_second_tirage INT NOT NULL, CHANGE boule3_second_tirage boule3_second_tirage INT NOT NULL, CHANGE boule4_second_tirage boule4_second_tirage INT NOT NULL, CHANGE boule5_second_tirage boule5_second_tirage INT NOT NULL');
    }
}
