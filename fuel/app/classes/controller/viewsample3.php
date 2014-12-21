<?php

class Controller_ViewSample3 extends Controller 
{
    public function action_index()
    {
        $view = View::forge('sampleview');

        $view->set('title', 'ビューサンプル3');
        $view->set_safe('username', '<del>Azuyama</del>Azusa');

        return $view;
    }
}
