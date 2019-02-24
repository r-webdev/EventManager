<?php


use Phinx\Migration\AbstractMigration;

class CreateAccountEventPivot extends AbstractMigration
{
    public function up()
    {
        $this
            ->table('account_event', [ 'id' => false, 'primary_key' => ['account_uuid', 'event_uuid'] ])

            ->addColumn('account_uuid', 'string', [ 'length' => 36 ])
            ->addColumn('event_uuid', 'string', [ 'limit' => 36 ])

            ->addColumn('attending', 'enum', ['values' => ['yes', 'not', 'maybe', 'pending',], 'null' => false, 'default' => 'pending'])

            ->addColumn('created_at', 'datetime', [ 'default' => \Phinx\Util\Literal::from('now()') ])
            ->addColumn('updated_at', 'datetime', [ 'default' => \Phinx\Util\Literal::from('now()') ])
            ->addColumn('deleted_at', 'datetime', [ 'null' => true ])

            ->addIndex(['account_uuid'])
            ->addIndex(['event_uuid'])

            ->addIndex(['attending'])

            ->addIndex(['created_at'])
            ->addIndex(['updated_at'])
            ->addIndex(['deleted_at'])

            ->addForeignKey('account_uuid', 'accounts', 'uuid')
            ->addForeignKey('event_uuid', 'events', 'uuid')

            ->create();
    }

    public function down()
    {
        $this->table('account_event')->drop()->save();
    }
}
