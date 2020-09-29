<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200929123717 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $anonymousUser = array(
            'username' => 'anonym',
            'password' => password_hash('anonym', PASSWORD_BCRYPT),
            'email' => 'anonym@todolist.fr',
            'role' => 'ROLE_USER'
        );

        //creation of the anonymous user
        $this->addSql('INSERT INTO user (username, password, email, role ) VALUES(:username, :password, :email, :role)', $anonymousUser);
        
        //bind the anonymous tasks
        $this->addSql('UPDATE task SET user_id = (
            SELECT id FROM user WHERE username = "anonym"
        ) WHERE user_id IS null');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE task SET user_id = null WHERE user_id = (
            SELECT id FROM user WHERE username = "anonym"
        )');
        $this->addSql('DELETE FROM user WHERE username= "anonym"');
    }
}
