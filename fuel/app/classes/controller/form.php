<?php

class Controller_Form extends Controller_Public
{
    public function before()
    {
        parent::before();
        $this->template->footer = View::forge('layout/footer');
    }

    public function action_index()
    {
        $form = $this->forge_form();

        //エラー処理などの場合値の保持
        if (Input::method() === 'POST')
        {
            $form->repopulate();
        }

        $this->template->title = 'contact form';
        $this->template->content = View::forge('form/index');
        $this->template->content->set_safe('html_form', $form->build('form/confirm'));
    }

    //フォームの定義
    public function forge_form()
    {
        $form = Fieldset::forge();

        $form->add('name', '名前')
            ->add_rule('trim')
            ->add_rule('required')
            ->add_rule('no_tab_and_newline')
            ->add_rule('max_length', 50);

        $form->add('email', 'メールアドレス')
            ->add_rule('trim')
            ->add_rule('required')
            ->add_rule('no_tab_and_newline')
            ->add_rule('max_length', 100)
            ->add_rule('valid_email');

        $form->add('comment', 'コメント',
            array('type' => 'textarea', 'cols' => 70, 'rows' => 6))
            ->add_rule('required')
            ->add_rule('max_length', 400);

        $form->add('submit', '', array('type' => 'submit', 'value' => '確認'));

        return $form;
    }

    //forge_formメソッドに代替
    //public function forge_validation()
    //{
    //    $val = Validation::forge();

    //    $val->add('name', 'name')
    //        ->add_rule('trim')
    //        ->add_rule('required')
    //        ->add_rule('no_tab_and_newline')
    //        ->add_rule('max_length', 50);

    //    $val->add('email', 'email')
    //        ->add_rule('trim')
    //        ->add_rule('required')
    //        ->add_rule('no_tab_and_newline')
    //        ->add_rule('max_length', 100)
    //        ->add_rule('valid_email');

    //    $val->add('comment', 'comment')
    //        ->add_rule('required')
    //        ->add_rule('max_length', 400);

    //    return $val;
    //}

    public function  action_confirm()
    {
        //$val = $this->forge_validation()->add_callable('MyValidationRules');
        $form = $this->forge_form();
        $val = $form->validation()->add_callable('MyValidationRules');

        if($val->run())
        {
            $data['input'] = $val->validated();
            $this->template->title = 'contact form : confirm';
            $this->template->content = View::forge('form/confirm', $data);
        }
        else
        {
            $form->repopulate();
            $this->template->title = 'contact form : errors';
            $this->template->content = View::forge('form/index');
            $this->template->content->set_safe('html_error', $val->show_errors());
            $this->template->content->set_safe('html_form', $form->build('form/confirm'));
        }
    }

    public function action_send()
    {
        //CSRF対策
        if (! Security::check_token())
        {
            throw new HttpInvalidInputException('ページの遷移が正しくありません。');
        }

        //$val = $this->forge_validation()->add_callable('MyValidationRules');
        $form = $this->forge_form();
        $val = $form->validation()->add_callable('MyValidationRules');

        if ( ! $val->run())
        {
            $form->repopulate();
            $this->template->title = 'コンタクトフォーム: エラー';
            $this->template->content = View::forge('form/index');
            $this->template->content->set_safe('html_error', $val->show_errors());
            $this->template->content->set_safe('html_form', $form->fuild('form/confirm'));
            return;
        }
        $post = $val->validated();
        //$data = $this->build_mail($post);

        $post['ip_address'] = Input::ip();
        $post['user_agent'] = Input::user_agent();
        unset($post['submit']);

        //データベースへ保存

        //ORMパッケージ変更の反映
        //$model_form = Model_Form::forge()->set($post);
        //list($id, $rows) = $model_form->save();
        $model_form = Model_Form::forge($post);
        $ret = $model_form->save();

        //if ($rows != 1)
        if ( ! $ret)
        {
            Log::error('データベース保存エラー', __METHOD__);

            $form->repopulate();
            $this->template->title = 'コンタクトフォーム: サーバーエラー';
            $this->template->content = View::forge('form/index');
            $html_error = '<p>サーバでエラーが発生しました。</p>';
            $this->template->content->set_safe('html_error', $html_error);
            $this->template->content->set_safe('html_form', $form->build('form/confirm'));
            return;
        }

        //メール送信処理
        try
        {
            //$this->sendmail($data);
            $mail = new Model_Mail();
            $mail->send($post);
            $this->template->title = 'コンタクトフォーム: 送信完了';
            $this->template->content = View::forge('form/send');
            return;
        }
        catch(EmailValidationFailedException $e)
        {
            Log::error(
                'メール検証エラー:' . $e->getMessage(), __METHOD__
            );
            $html_error = '<p>メールアドレスに誤りがあります。</p>';
        }
        catch(EmailSendingFailedException $e)
        {
            Log::error(
                'メール送信エラー:' . $e->getMessage(), __METHOD__
            );
            $html_error = '<p>メールを送信できませんでした。</p>';

            $form->repopulate();
            $this->template->title = 'コンタクトフォーム: 送信エラー';
            $this->template->content = View::forge('form/index');
            $this->template->content->set_safe('form_error', $html_error);
            $this->template->content->set_safe('html_form', $form->build('form/confirm'));
        }
    }

    //メールの作成
//    public function build_mail($post)
//    {
//        $data['from']       = $post['email'];
//        $data['from_name']     = $post['name'];
//        $data['to']     = 'kawasaki@rejob.co.jp';
//        $data['to_name']     = '管理者';
//        $data['subject']     = 'コンタクトフォーム';
//
//        $ip    = Input::ip();
//        $agent = Input::user_agent();
//
//        $data['body'] = <<<  END
//
//            名前: {$post['name']}
//            メールアドレス: {$post['email']}
//            IPアドレス: $ip
//            ブラウザ: $agent
//
//            コメント:
//        {$post['comment']}
//END;
//
//        return $data;
//    }

    //Modelに移行
    //public function sendmail($data)
    //{
    //    Package::load('email');

    //    $email = Email::forge();
    //    $email->to($data['to'], $data['to_name']);
    //    $email->from($data['from'], $data['from_name']);
    //    $email->subject($data['subject']);
    //    $email->body($data['body']);

    //    $email->send();
    //}
}
