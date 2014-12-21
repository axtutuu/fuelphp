<?php
class Controller_Hello2 extends Controller
{
    public function action_index()
    {
        return View::forge('hello');
    }
}

