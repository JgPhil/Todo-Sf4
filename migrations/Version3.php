<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version3 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {   
        //add a role field 
        $this->addSql('ALTER TABLE user ADD role VARCHAR(255) NOT NULL');

        //parameters used in the next script
        $anonymousUser = array(
            'username' => 'anonym',
            'password' => password_hash('anonym', PASSWORD_BCRYPT),
            'email' => 'anonym@todolist.fr',
            'role' => 'ROLE_USER'
        );

        //creation of the anonymous user
        $this->addSql('INSERT INTO user (username, password, email, role ) VALUES(:username, :password, :email, :role)', $anonymousUser);
        
        // set the role on the actual user
        $this->addSql('UPDATE user SET role = "ROLE_USER"');

        //bind the anonymous tasks with the anonym user
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
