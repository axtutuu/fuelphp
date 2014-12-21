<?php

class Controller_Layout extends Controller_template
{
    public function before()
    {
        parent::before();

        $this->current_user = 'Sawako';
    }

    public function action_index()
    {
        $this->template->set_global('title', 'レイアウト機能のサンプル');

        $data = array('user' => $this->current_user);

        $this->template->content = View::forge('layout/index', $data);
        $this->template->footer = View::forge('layout/footer');
    }
}
