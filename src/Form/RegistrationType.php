<?php

namespace App\Form;


use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RegistrationType extends ApplicationType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('firstname', TextType::class, $this->getConfiguration("prenom", "votre prenom..."))
            ->add('lastname', TextType::class, $this->getConfiguration("nom", "votre nom de famille "))
            ->add('email', EmailType::class, $this->getConfiguration("Email", "votre adresse emil"))
            ->add('introduction', TextType::class, $this->getConfiguration("Introduction", "Donnez une description globale de l'annonce"))
            ->add('picture', UrlType::class, $this->getConfiguration('photo de profil', "url de votre avatar... "))
            ->add('hash', PasswordType::class, $this->getConfiguration("mot de passe", "choisissez un bon mot de passe ! "))
            ->add('description', TextareaType::class, $this->getConfiguration("description detaillé", "presentez en details"));
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
