<?php

namespace Fuel\Migrations;

class Add_user_agent_to_forms
{
	public function up()
	{
		\DBUtil::add_fields('forms', array(
			'user_agent' => array('constraint' => 255, 'type' => 'varchar'),

		));
	}

	public function down()
	{
		\DBUtil::drop_fields('forms', array(
			'user_agent'

		));
	}
}