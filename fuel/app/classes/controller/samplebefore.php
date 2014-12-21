<?php

class Controller_SampleBefore extends Controller 
{
    public function before()
    {
        $this->current_user = 'Yui';
    }

    public function action_index()
    {
        $output = $this->current_user . 'さん' . __METHOD__ . 'が実行されました。<br>';
        return $output;
    }

    public function action_test()
    {
        $output = $this->current_user . 'さん' . __METHOD__ . 'が実行されました。';
        return $output;
    }
}

