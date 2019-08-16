<?php

namespace Drupal\drupal_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Drupal form class.
 */
class DrupalForm extends FormBase {

/**
 * {@inheritdoc}
 */
    public function getFormId(){
        return 'drupal_form';
    }

/**
 * {@inheritdoc}
 */    
    public function buildForm(array $form, FormStateInterface $form_state){

        $form['first_name'] = [
            '#type' => 'textfield',
            '#size' => '60',
            '#title' => $this->t('First Name'), 
            '#required' => TRUE,
        ];

        $form['last_name'] = [
            '#type' => 'textfield',
            '#size' => '60',
            '#title' => $this->t('Last Name'), 
            '#required' => TRUE,
        ];

        $form['subject'] = [
            '#type' => 'textfield',
            '#size' => '60',
            '#title' => $this->t('Subject'), 
            '#required' => TRUE,
        ];

        $form['message'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Message'),
            '#rows' => 3,
            '#cols' => 30,
            //'#resizable' => TRUE,
            '#required' => TRUE,
        ];

        $form['email'] = [
            '#type' => 'email',
            '#title' => $this ->t('Email'), 
            '#required' => TRUE,
        ];

        $form['submit'] = [
            '#type' => 'submit',
            '#value'=> $this->t('SEND'), 
        ];

        return $form;    

    }

    /**
    * {@inheritdoc}
    */

    public function validateForm(array &$form, FormStateInterface $form_state) {
        if (!filter_var(($form_state->getValue('email')), FILTER_VALIDATE_EMAIL)){
            $form_state->setErrorByName('email', $this->t('Email address is invalid.'));    
        }
    }

    /**
    * {@inheritdoc}
    */

    public function submitForm(array &$form, FormStateInterface $form_state){  
       //drupal_set_message('Your message is send'); 
       
       $send_mail = new \Drupal\Core\Mail\Plugin\Mail\PhpMail();
       $from = $this->config('system.site')->get('mail');
       $message = array();
       $message['headers'] = array(
             'reply-to' => $from,
             'from' => $form_state->getValue('email').'<'.$from.'>',
             'Return-Path' => $from,
       );
       $message['to'] = 'anamalia@tut.by';
       $message['subject'] =  $form_state->getValue('subject');
       $message['body'] = $form_state->getValue('message');
       $result_email = $send_mail->mail($message);

       if ($result_email){
         drupal_set_message('Your message is send!');
         \Drupal::logger('drupal_form')->notice('The mail - '.$form_state->getValue('email').' was send.');
       }
    }


}