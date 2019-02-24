<?php


use Phinx\Migration\AbstractMigration;

class CreateVenues extends AbstractMigration
{
    public function up()
    {
        $this
            ->table('venues', [ 'id' => false, 'primary_key' => 'uuid' ])

            ->addColumn('uuid', 'string', [ 'length' => 36 ])
            ->addColumn('organisation_uuid', 'string', [ 'limit' => 36 ])

            ->addColumn('label', 'string', ['limit' => 128, 'null' => false])

            ->addColumn('meta', 'json', ['null' => true])

            ->addColumn('created_at', 'datetime', [ 'default' => \Phinx\Util\Literal::from('now()') ])
            ->addColumn('updated_at', 'datetime', [ 'default' => \Phinx\Util\Literal::from('now()') ])
            ->addColumn('deleted_at', 'datetime', [ 'null' => true ])

            ->addIndex(['organisation_uuid'])
            ->addIndex(['label'])
            ->addIndex(['created_at'])
            ->addIndex(['updated_at'])
            ->addIndex(['deleted_at'])

            ->addForeignKey('organisation_uuid', 'organisations', 'uuid')

            ->create();
    }

    public function down()
    {
        $this->table('venues')->drop()->save();
    }
}
