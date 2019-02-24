<?php


use Phinx\Migration\AbstractMigration;

class CreateTokens extends AbstractMigration
{
    public function up() {
        $this
            ->table('tokens', [ 'id' => false, 'primary_key' => 'uuid' ])

            ->addColumn('uuid', 'string', [ 'limit' => 36 ])

            ->addColumn('name', 'string', [ 'limit' => 64 ])

            ->addColumn('token', 'string', [ 'limit' => 64 ])
            ->addColumn('type', 'enum', ['values' => ['refresh','auth'], 'null' => false, 'default' => 'auth'])

            ->addColumn('meta', 'json', ['null' => true])

            ->addColumn('account_uuid', 'string', [ 'limit' => 36 ])
            ->addColumn('refresh_token_uuid', 'string', [ 'limit' => 36, 'null' => true ])

            ->addColumn('expired_at', 'datetime', [ 'null' => true ])
            ->addColumn('created_at', 'datetime', [ 'default' => \Phinx\Util\Literal::from('now()') ])
            ->addColumn('updated_at', 'datetime', [ 'default' => \Phinx\Util\Literal::from('now()') ])
            ->addColumn('deleted_at', 'datetime', [ 'null' => true ])

            ->addIndex(['name'])
            ->addIndex(['account_uuid'])
            ->addIndex(['token'], ['unique' => true])
            ->addIndex(['token', 'type'], ['unique' => true])
            ->addIndex(['expired_at'])
            ->addIndex(['created_at'])
            ->addIndex(['deleted_at'])

            ->addForeignKey('account_uuid', 'accounts', 'uuid')
            ->addForeignKey('refresh_token_uuid', 'tokens', 'uuid')

            ->create();
    }

    public function down() {
        $this->table('tokens')->drop()->save();
    }
}
