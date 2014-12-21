<?php

class Model_Status extends Model
{
    public static function find_body_by_username($username)
    {
        //本来はデータベースを検索して結果を返す

        $data = array(
            array(
                'date' => '2012/04/08 12:33',
                'body' => 'イースターなう',
            ),
            array(
                'date' => '2012/04/08 17:33',
                'body' => '花祭なう',
            ),
        );
        return $data;
    }
}
