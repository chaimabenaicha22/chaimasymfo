<?php

namespace App\Controller;

use App\Entity\PasswordUpdate;
use App\Entity\User;
use App\Form\AccountType;
use App\Form\PasswordUpdateType;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
//use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * permet d'afficher et de gerer le formulaire de connexion 
     * 
     * @Route("/login", name="account_login")
     * 
     * @return Response 
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }
    /**
     * permet de se deconnecter 
     * 
     * @Route("/logout" , name="account_logout")
     * 
     * @return void 
     */

    public function  logout()
    {
        //rien!
    }
    /**
     * permetd'afficher le formulaire d'inscription 
     * 
     * @param Request $request
     * @Route("/register" , name="account_register")
     * 
     * @return Response  
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                "votre compte a été bien crée ! vous pouvez maintenant connecter "
            );
        }

        return $this->render('account/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * permet d'afficher et de traiter le formulaire de profil
     * 
     * @Route("/account/profil" , name="account_profile")
     * 
     * @return Response
     */
    public function profile(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();
            $this->addFlash(
                'success',
                "les données du profil ont été enregistrée avec success"
            );
            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/profile.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * permet de modifier le mot de passe 
     * 
     * @Route("/account/password-update" , name="account_password")
     * 
     * @return void 
     */
    public function updatePassword(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $passwordUpdate = new PasswordUpdate();
        $user = $this->getUser();
        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // 1. verifier que le oldpassword de formulaire soit le mm que password user 
            if (!password_verify($passwordUpdate->getOldPassword(), $user->getHash())) {
                //gerer l'erreur 
                $form->get('oldPassword')->addError(new FormError("le mot de passe que vous avez taper n'est pas votre mot de passe actuel !"));
            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);
                $user->setHash($hash);
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($user);
                $manager->flush();
                $this->addFlash(
                    'success',
                    "votre mot de passe a été bien modifié!"
                );
                return $this->redirectToRoute('/home');
            }
        }
        return $this->render('account/password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * permet d'afficher le profil utilisateur connecté
     * 
     *@Route("/account", name="account_index")
     *
     * @return Response 
     */
    public function myAccount()
    {
        return $this->render('user/index.html.twig', [
            'user' => $this->getUser()
        ]);
    }
}
