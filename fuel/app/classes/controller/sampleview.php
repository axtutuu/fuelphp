<?php

class Controller_SampleView extends Controller 
{
    public function action_index()
    {
        $data = array();

        $data['title'] = "viewサンプル";
        $data['username'] = 'Ritsu';

        return View::forge('sampleview', $data);
    }
}
