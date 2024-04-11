<?php

use think\migration\Migrator;

class AddColumnToSamples extends Migrator
{

    public function up(){
        parent::up();

        $this->table('samples')
            ->addColumn('nick_name','string',['after'=>'name','limit'=>50,'default'=>'','comment'=>'用户昵称'])
            ->update();

    }

    public function down(){
        parent::down();

        $this->table('samples')
            ->removeColumn('nick_name')
            ->save();
    }

}
