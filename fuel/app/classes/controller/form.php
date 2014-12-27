<?php

class Controller_Form extends Controller_template
{
    public function before()
    {
        parent::before();
        $this->template->footer = View::forge('layout/footer');
    }

    public function action_index()
    {
        $this->template->title = 'contact form';
        $this->template->content = View::forge('form/index');
    }

    public function forge_validation()
    {
        $val = Validation::forge();

        $val->add('name', 'name')
            ->add_rule('trim')
            ->add_rule('required')
            ->add_rule('max_length', 50);

        $val->add('email', 'email')
            ->add_rule('trim')
            ->add_rule('required')
            ->add_rule('max_length', 100)
            ->add_rule('valid_email');

        $val->add('comment', 'comment')
            ->add_rule('required')
            ->add_rule('max_length', 400);

        return $val;
    }

    public function  action_confirm()
    {
        $val = $this->forge_validation();

        if($val->run())
        {
            $data['input'] = $val->validated();
            $this->template->title = 'contact form : confirm';
            $this->template->content = View::forge('form/confirm', $data);
        }
        else
        {
            $this->template->title = 'contact form : errors';
            $this->template->content = View::forge('form/index');
            $this->template->content->set_safe('html_error', $val->show_errors());
        }
    }

    public function action_send()
    {
        //CSRF対策
        if (! Security::check_token())
        {
            throw new HttpInvalidInputException('ページの遷移が正しくありません。');
        }

        $val = $this->forge_validation();

        if ( ! $val->run())
        {
            $this->template->title = 'コンタクトフォーム: エラー';
            $this->template->content = View::forge('form/index');
            $this->template->content->set_safe('html_error', $val->show_errors());
            return;
        }
        $post = $val->validated();
        $data = $this->build_mail($post);

        //メール送信処理
        try
        {
            $this->sendmail($data);
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

            $this->template->title = 'コンタクトフォーム: 送信エラー';
            $this->template->content = View::forge('form/index');
            $this->template->content->set_safe('form_error', $html_error);
        }
    }

    //メールの作成
    public function build_mail($post)
    {
        $data['from']       = $post['email'];
        $data['from_name']     = $post['name'];
        $data['to']     = 'kawasaki@rejob.co.jp';
        $data['to_name']     = '管理者';
        $data['subject']     = 'コンタクトフォーム';

        $ip    = Input::ip();
        $agent = Input::user_agent();

        $data['body'] = <<<  END

            名前: {$post['name']}
            メールアドレス: {$post['email']}
            IPアドレス: $ip
            ブラウザ: $agent

            コメント:
        {$post['comment']}
END;

        return $data;
    }

    public function sendmail($data)
    {
        Package::load('email');

        $email = Email::forge();
        $email->to($data['to'], $data['to_name']);
        $email->from($data['from'], $data['from_name']);
        $email->subject($data['subject']);
        $email->body($data['body']);

        $email->send();
    }
}
