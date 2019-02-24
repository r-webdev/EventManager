<?php


use Phinx\Migration\AbstractMigration;

class CreateAccountOrganisationPivot extends AbstractMigration
{
    public function up()
    {
        $this
            ->table('account_organisation', [ 'id' => false, 'primary_key' => ['account_uuid', 'organisation_uuid'] ])

            ->addColumn('account_uuid', 'string', [ 'length' => 36 ])
            ->addColumn('organisation_uuid', 'string', [ 'limit' => 36 ])

            ->addColumn('role', 'enum', ['values' => ['admin', 'moderator', 'member',], 'null' => false, 'default' => 'member'])

            ->addColumn('created_at', 'datetime', [ 'default' => \Phinx\Util\Literal::from('now()') ])
            ->addColumn('updated_at', 'datetime', [ 'default' => \Phinx\Util\Literal::from('now()') ])
            ->addColumn('deleted_at', 'datetime', [ 'null' => true ])

            ->addIndex(['account_uuid'])
            ->addIndex(['organisation_uuid'])

            ->addIndex(['role'])

            ->addIndex(['created_at'])
            ->addIndex(['updated_at'])
            ->addIndex(['deleted_at'])

            ->addForeignKey('account_uuid', 'accounts', 'uuid')
            ->addForeignKey('organisation_uuid', 'organisations', 'uuid')

            ->create();
    }

    public function down()
    {
        $this->table('account_organisation')->drop()->save();
    }
}
