<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241203191234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE HelpyBénéficiaires (BénéficiaireNo INT AUTO_INCREMENT NOT NULL, BénéficiaireNomPrénom VARCHAR(50) DEFAULT \'\', BénéficiaireDateNaissance DATETIME DEFAULT \'0000-00-00 00:00:00\', BénéficiaireLien VARCHAR(50) DEFAULT \'\', BénéficiaireDateLunette DATETIME DEFAULT \'0000-00-00 00:00:00\', BénéficiairePersonnelNo INT DEFAULT 0, INDEX IDX_9AAED169642ACDC9 (BénéficiairePersonnelNo), PRIMARY KEY(BénéficiaireNo)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE HelpyInterventions (InterventionNo INT AUTO_INCREMENT NOT NULL, InterventionMontantPayé DOUBLE PRECISION DEFAULT \'0\', InterventionDateFacture DATETIME DEFAULT \'0000-00-00 00:00:00\', InterventionMontantRéalisé TINYINT(1) DEFAULT 0, InterventionDateRéalisé DATETIME DEFAULT \'0000-00-00 00:00:00\', InterventionDivers TEXT DEFAULT NULL, InterventionPieceNo VARCHAR(20) DEFAULT \'\', InterventionExtraitNo VARCHAR(20) DEFAULT \'\', InterventionPersonnelNo INT DEFAULT 0, InterventionType VARCHAR(50) DEFAULT \'\', InterventionBénéficiaireNo INT DEFAULT 0 NOT NULL, INDEX IDX_775BC24D6CEEE80C (InterventionPersonnelNo), INDEX IDX_775BC24DC9596CD9 (InterventionType), INDEX IDX_775BC24DA63B1F30 (InterventionBénéficiaireNo), PRIMARY KEY(InterventionNo)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE HelpyInterventionsTypes (InterventionType VARCHAR(50) DEFAULT \'\' NOT NULL, PRIMARY KEY(InterventionType)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE HelpyLPersonnel (LPersonnelNo INT AUTO_INCREMENT NOT NULL, LPersonnelNom VARCHAR(50) DEFAULT \'\', LPersonnelPrénom VARCHAR(50) DEFAULT \'\', LPersonnelFournisseur TINYINT(1) DEFAULT 0 NOT NULL, LPersonnelCompteBanque VARCHAR(20) DEFAULT \'0\', LPersonnelSoldeSMAP INT DEFAULT 0, INDEX NOMPRENOM (LPersonnelNom, LPersonnelPrénom), PRIMARY KEY(LPersonnelNo)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE helpy_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, UNIQUE INDEX uniq_8d93d649f85e0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE HelpyBénéficiaires ADD CONSTRAINT FK_9AAED169642ACDC9 FOREIGN KEY (BénéficiairePersonnelNo) REFERENCES HelpyLPersonnel (LPersonnelNo)');
        $this->addSql('ALTER TABLE HelpyInterventions ADD CONSTRAINT FK_775BC24D6CEEE80C FOREIGN KEY (InterventionPersonnelNo) REFERENCES HelpyLPersonnel (LPersonnelNo)');
        $this->addSql('ALTER TABLE HelpyInterventions ADD CONSTRAINT FK_775BC24DC9596CD9 FOREIGN KEY (InterventionType) REFERENCES HelpyInterventionsTypes (InterventionType)');
        $this->addSql('ALTER TABLE HelpyInterventions ADD CONSTRAINT FK_775BC24DA63B1F30 FOREIGN KEY (InterventionBénéficiaireNo) REFERENCES HelpyBénéficiaires (BénéficiaireNo)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE HelpyBénéficiaires DROP FOREIGN KEY FK_9AAED169642ACDC9');
        $this->addSql('ALTER TABLE HelpyInterventions DROP FOREIGN KEY FK_775BC24D6CEEE80C');
        $this->addSql('ALTER TABLE HelpyInterventions DROP FOREIGN KEY FK_775BC24DC9596CD9');
        $this->addSql('ALTER TABLE HelpyInterventions DROP FOREIGN KEY FK_775BC24DA63B1F30');
        $this->addSql('DROP TABLE HelpyBénéficiaires');
        $this->addSql('DROP TABLE HelpyInterventions');
        $this->addSql('DROP TABLE HelpyInterventionsTypes');
        $this->addSql('DROP TABLE HelpyLPersonnel');
        $this->addSql('DROP TABLE helpy_user');
    }
}
