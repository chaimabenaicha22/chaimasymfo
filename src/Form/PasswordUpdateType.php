<?php

namespace App\Form;

use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class PasswordUpdateType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('oldPassword', PasswordType::class, $this->getConfiguration("Ancien mot de passe", "donner votre mot de passe actuel..."))
            ->add('newPassword', PasswordType::class, $this->getConfiguration("Nouveau mot de passe", "taper votre nouveau mot de passe..."))
            ->add('confirmPassword', PasswordType::class, $this->getConfiguration("confirmaation du mot de passe ", "confirmer votre nouveau mot de passe "));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
