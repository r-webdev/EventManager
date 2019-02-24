<?php


use Phinx\Migration\AbstractMigration;

class CreateEvents extends AbstractMigration
{
    public function up()
    {
        $this
            ->table('events', [ 'id' => false, 'primary_key' => 'uuid' ])

            ->addColumn('uuid', 'string', [ 'length' => 36 ])
            ->addColumn('organisation_uuid', 'string', [ 'limit' => 36 ])
            ->addColumn('venue_uuid', 'string', [ 'limit' => 36 ])

            ->addColumn('label', 'string', ['limit' => 128, 'null' => false])

            ->addColumn('meta', 'json', ['null' => true])

            ->addColumn('start_at', 'datetime', [ 'default' => \Phinx\Util\Literal::from('now()') ])
            ->addColumn('start_tz', 'string', ['limit' => 128, 'null' => true])
            ->addColumn('end_at', 'datetime', [ 'default' => \Phinx\Util\Literal::from('now()') ])
            ->addColumn('end_tz', 'string', ['limit' => 128, 'null' => true])

            ->addColumn('created_at', 'datetime', [ 'default' => \Phinx\Util\Literal::from('now()') ])
            ->addColumn('updated_at', 'datetime', [ 'default' => \Phinx\Util\Literal::from('now()') ])
            ->addColumn('deleted_at', 'datetime', [ 'null' => true ])

            ->addIndex(['organisation_uuid'])
            ->addIndex(['venue_uuid'])
            ->addIndex(['label'])

            ->addIndex(['start_at'])
            ->addIndex(['end_at'])

            ->addIndex(['created_at'])
            ->addIndex(['updated_at'])
            ->addIndex(['deleted_at'])

            ->addForeignKey('organisation_uuid', 'organisations', 'uuid')
            ->addForeignKey('venue_uuid', 'venues', 'uuid')

            ->create();
    }

    public function down()
    {
        $this->table('events')->drop()->save();
    }
}
