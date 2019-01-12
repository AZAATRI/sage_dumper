<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CommercialRegistrationType;
use App\Form\RegistrationType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if($this->getUser()){
            return $this->redirectToRoute('success_login');
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout(AuthenticationUtils $authenticationUtils): Response
    {}

    /**
     * @Route("/success", name="success_login")
     */
    public function redirector(){
        $roles = $this->getUser()->getRoles();
        switch(reset($roles)){
            case 'ROLE_ADMIN':
                $route = 'commercials_list';
                break;
            case 'ROLE_COMMERCIAL':
                $route = 'clients_list';
                break;
            default:
                $route = 'login';
        }
        return $this->redirectToRoute($route);
    }

    /**
     * @Route("/user/resetpassword", name="reset_password")
     */
    public function resetPassword(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $user = $this->getUser();
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if($form->isSubmitted()){
            if(! $encoder->isPasswordValid($user,$request->get('reset_password')['oldPassword'])){
                $form->get('oldPassword')->addError(new FormError('Ancien mot de passe incorrect'));
            }
            if($form->isValid()){
                $newEncodedPassword = $encoder->encodePassword($user,reset($request->get('reset_password')['password']));
                $user->setPassword($newEncodedPassword);
                $em->persist($user);
                $em->flush();
                $this->addFlash('notice', 'Votre mot de passe à bien été changé !');
                return $this->redirectToRoute('success_login');
            }
        }

        return $this->render('security/resetpassword.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
