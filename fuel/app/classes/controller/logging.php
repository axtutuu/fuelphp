<?php

class Controller_Logging extends Controller 
{
    public function action_index()
    {
        Log::info('ログのテスト', __METHOD__);
        return 'ログのテスト';
    }
}
