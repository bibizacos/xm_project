<?php
/**
 * Created by PhpStorm.
 * User: bibiz
 * Date: 01-May-20
 * Time: 7:18 PM
 */

namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;



class InputForm extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('company_symbol', TextType::class, [
                'attr' => array('class' => 'form-control')
            ])
            ->add('start_date', TextType::class, [
                'attr' => array('class' => 'form-control','id'=>'datepicker')
            ])
            ->add('end_date', TextType::class, [
                'attr' => array('class' => 'form-control')
            ])
            ->add('email', EmailType::class, [
                'attr' => array('class' => 'form-control'),
            ]);
    }
}