<?php

use Phinx\Migration\AbstractMigration;

/**
 * Class CreateAccounts
 */
class CreateAccounts extends AbstractMigration
{
    public function up()
    {
        $this
            ->table('accounts', [ 'id' => false, 'primary_key' => 'uuid' ])

            ->addColumn('uuid', 'string', [ 'length' => 36 ])

            ->addColumn('type', 'enum', ['values' => ['admin', 'moderator', 'user',], 'null' => true, 'default' => 'user'])

            ->addColumn('email', 'string', ['limit' => 128, 'null' => true])
            ->addColumn('password', 'text', ['null' => true])

            ->addColumn('verify', 'string', ['limit' => 64, 'null' => true])
            ->addColumn('forgot', 'string', ['limit' => 64, 'null' => true])

            ->addColumn('meta', 'json', ['null' => true])

            ->addColumn('expired_at', 'datetime', [ 'null' => true ])
            ->addColumn('created_at', 'datetime', [ 'default' => \Phinx\Util\Literal::from('now()') ])
            ->addColumn('updated_at', 'datetime', [ 'default' => \Phinx\Util\Literal::from('now()') ])
            ->addColumn('deleted_at', 'datetime', [ 'null' => true ])

            ->addIndex(['type'])
            ->addIndex(['email', 'verify'])
            ->addIndex(['email', 'forgot'])
            ->addIndex(['email'], ['unique' => true])
            ->addIndex(['expired_at'])
            ->addIndex(['created_at'])
            ->addIndex(['deleted_at'])

            ->create();
    }

    public function down()
    {
        $this->table('accounts')->drop()->save();
    }
}
