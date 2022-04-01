<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountPasswordController extends AbstractController
{
    /**
     * @Route("/compte/modifier-mot-de-passe", name="app_account_password")
     */
    public function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $encoder): Response
    {
        $notification = '';
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer le mdp actuel saisi par l'utilisateur dans le formulaire
            $old_pwd = $form->get('old_password')->getData();
            // Le comparer avec celui stocké dans la bdd de l'entité User
            if ($encoder->isPasswordValid($user, $old_pwd)) {
                // Récupérer le nouveau mdp
                $new_pwd = $form->get('new_password')->getData();
                // Encoder le nouveau mdp
                $password = $encoder->hashPassword($user, $new_pwd);
                // Setter dans le User le nouveau mdp
                $user->setPassword($password);
                $em->flush();
                $notification = 'Votre mot de passe a été modifié';
            } else {
                $notification = "Votre mot de passe actuel n'est pas le bon";
            }
        }
        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
