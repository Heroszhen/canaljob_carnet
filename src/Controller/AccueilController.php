<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\UserType;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AcceilController
 * @package App\Controller
 * 
 * @Route("/")
 */ 
class AccueilController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(Request $request , UserPasswordEncoderInterface $passwordEncoder){
		
		$user = new User();
		$form = $this->createForm(UserType::class,$user);
		$form->handleRequest($request);
		
		if($form->isSubmitted()){
			if($form->isValid()){
				$password = $passwordEncoder->encodePassword($user,$user->getRetape());
				$user->setPassword($password);
				
				$manager = $this->getDoctrine()->getManager();
				$manager->persist($user);
				$manager->flush();
				
				$this->addFlash('success','Votre compte est créé');
			}else{
				$this->addFlash('error','Le formulaire contient des erreurs');
			}
		}
		
        return $this->render('accueil/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
    
    /**
     * @Route("/connexion")
     */ 
    public function login(AuthenticationUtils $authenticationUtils){
		$error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        if (!empty($error)) {
            $this->addFlash('error', 'Identifiants incorrects');
        }
        return $this->render(
            'accueil/login.html.twig',
            [
                'last_username' => $lastUsername
            ]
        );
	}
	
	/**
	 * @Route("/deconnexion")
	 */ 
	public function logout(){}
}
