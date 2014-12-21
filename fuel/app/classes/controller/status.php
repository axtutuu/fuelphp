<?php

class Controller_Status extends Controller 
{
    public function action_index()
    {
        $results = Model_Status::find_body_by_username('foo');

        //$resultsのdump
        Debug::dump($results);
    }
}
