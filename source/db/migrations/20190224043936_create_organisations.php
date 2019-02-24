<?php


use Phinx\Migration\AbstractMigration;

class CreateOrganisations extends AbstractMigration
{
    public function up()
    {
        $this
            ->table('organisations', [ 'id' => false, 'primary_key' => 'uuid' ])

            ->addColumn('uuid', 'string', [ 'length' => 36 ])

            ->addColumn('label', 'string', ['limit' => 128, 'null' => false])

            ->addColumn('meta', 'json', ['null' => true])

            ->addColumn('created_at', 'datetime', [ 'default' => \Phinx\Util\Literal::from('now()') ])
            ->addColumn('updated_at', 'datetime', [ 'default' => \Phinx\Util\Literal::from('now()') ])
            ->addColumn('deleted_at', 'datetime', [ 'null' => true ])

            ->addIndex(['label'])
            ->addIndex(['created_at'])
            ->addIndex(['updated_at'])
            ->addIndex(['deleted_at'])

            ->create();
    }

    public function down()
    {
        $this->table('organisations')->drop()->save();
    }
}
