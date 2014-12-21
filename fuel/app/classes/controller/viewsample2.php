<?php

class Controller_ViewSample2 extends Controller 
{
    public function action_index()
    {
        $view = View::forge('sampleview');

        $view->set('title', 'ビューのサンプル2');
        $view->set('username', 'Mugi');

        return $view;


    }
}
