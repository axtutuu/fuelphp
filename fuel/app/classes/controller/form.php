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
}
