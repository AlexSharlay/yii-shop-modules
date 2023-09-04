<?php

use common\modules\users\helpers\Security;
use yii\db\Migration;
use yii\db\Schema;

/**
 * CLass m140418_204054_create_module_tbl
 * @package common\modules\users\migrations
 *
 * Create module tables.
 *
 * Will be created 3 tables:
 * - {{%users}} - Users table.
 * - {{%profiles}} - User profiles table.
 * - {{%user_email}} - Users email table. This table is used to store temporary new user email address.
 *
 * By default will be added one administrator with login: admin and password: admin12345.
 */
class m140418_204054_create_module_tbl extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        // MySql table options
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        // Users table
        $this->createTable(
            '{{%users}}',
            [
                'id' => $this->primaryKey(),
                'username' => $this->string(30)->notNull(),
                'email' => $this->string(100)->notNull(),
                'password_hash' => $this->string()->notNull(),
                'auth_key' => $this->string(32)->notNull(),
                'token' => $this->string(53)->notNull(),
                'role' => $this->string(64)->notNull()->defaultValue('user'),
                'status_id' => $this->smallInteger()->defaultValue(0)->notNull(),
                'created_at' => $this->integer()->notNull(),
                'updated_at' => $this->integer()->notNull()
            ],
            $tableOptions
        );

        // Indexes
        $this->createIndex('idx-users-username', '{{%users}}', 'username', true);
        $this->createIndex('idx-users-email', '{{%users}}', 'email', true);
        $this->createIndex('idx-users-role', '{{%users}}', 'role');
        $this->createIndex('idx-users-status_id', '{{%users}}', 'status_id');
        $this->createIndex('idx-users-created_at', '{{%users}}', 'created_at');

        // Users table
        $this->createTable(
            '{{%profiles}}',
            [
                'user_id' => $this->primaryKey(),
                'name' => $this->string(50)->notNull(),
                'surname' => $this->string(50)->notNull(),
                'avatar_url' => $this->string(64)->notNull(),
            ],
            $tableOptions
        );

        // Foreign Keys
        $this->addForeignKey('fk-profiles-user_id', '{{%profiles}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');

        // Users emails table
        $this->createTable(
            '{{%user_email}}',
            [
                'user_id' => $this->integer()->notNull(),
                'email' => $this->string(100)->notNull(),
                'token' => $this->string(53)->notNull(),
                'PRIMARY KEY (user_id, token)'
            ],
            $tableOptions
        );

        // Foreign Keys
        $this->addForeignKey('fk-user_email-user_id', '{{%user_email}}', 'user_id', '{{%users}}', 'id', 'CASCADE', 'CASCADE');

        // Add super-administrator
        $this->execute($this->getUserSql());
        $this->execute($this->getProfileSql());
    }

    /**
     * @return string SQL to insert first user
     */
    private function getUserSql()
    {
        $time = time();
        $password_hash = Yii::$app->security->generatePasswordHash('admin12345');
        $auth_key = Yii::$app->security->generateRandomString();
        $token = Security::generateExpiringRandomString();
        return "INSERT INTO {{%users}} (username, email, password_hash, auth_key, token, role, status_id, created_at, updated_at) VALUES ('admin', 'admin@demo.com', '$password_hash', '$auth_key', '$token', 'admin', 1, $time, $time)";
    }

    /**
     * @return string SQL to insert first profile
     */
    private function getProfileSql()
    {
        return "INSERT INTO {{%profiles}} (user_id, name, surname, avatar_url) VALUES (1, 'Administration', 'Site', '')";
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_email}}');
        $this->dropTable('{{%profiles}}');
        $this->dropTable('{{%users}}');

        $this->dropTable('{{%client_manager}}');
    }
}
