<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Member;
use App\Form\MemberType;
use Symfony\Component\HttpFoundation\Response;
use App\Service\Traitcsv;
use App\Form\MycsvType;
use App\Entity\Myfile;

/**
 * class ProfilController
 * @package App\Controller
 * 
 * @Route("/profil")
 */ 
class ProfilController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(Request $request ,Traitcsv $traitcsv)
    {
		$myfile = new Myfile();
		$form = $this->createForm(MycsvType::class,$myfile);
		$form->handleRequest($request);
		
		if($form->isSubmitted()){
			if($form->isValid()){
				$file = $myfile->getMycsv();
				$newfile = uniqid().'.csv';
				$file->move($this->getParameter('upload_dir'),$newfile);
				$manager = $this->getDoctrine()->getManager();
				$traitcsv->import($newfile,$this->getUser(),$manager);
				unlink($this->getParameter('upload_dir').$newfile);
			}
		}
        return $this->render('profil/index.html.twig',['form'=>$form->createView()]);
    }
    
    /**
     * @Route("/updateuser")
     */
    public function updateme(Request $request , UserPasswordEncoderInterface $passwordEncoder){
		
		$user = $this->getUser();
		$form = $this->createForm(UserType::class,$user);
		$form->handleRequest($request);
		
		if($form->isSubmitted()){
			if($form->isValid()){
				$password = $passwordEncoder->encodePassword($user,$user->getRetape());
				$user->setPassword($password);
				
				$manager = $this->getDoctrine()->getManager();
				$manager->persist($user);
				$manager->flush();
				
				$this->addFlash('success','Votre compte est mis à jour');
			}else{
				$this->addFlash('error','Le formulaire contient des erreurs');
			}
		}	
		
		return $this->render('profil/updateuser.html.twig',["form"=>$form->createView()]);
	}
	
	/**
	 * @Route("/editmember/{id}",defaults={"id":null})
	 */ 
	public function editmember($id , Request $request ){
		$title = "";
		if(is_null($id)){
			$member = new Member();
			$title="Ajouter";
		}else{
			$member = $this->getDoctrine()->getRepository(Member::class)->findOneBy(['id'=>$id]);
			$title="Modifier";
		}
		
		$form = $this->createForm(MemberType::class,$member);
		$form->handleRequest($request);
		if($form->isSubmitted()){
			if($form->isValid()){
				$member->setContact($this->getUser());
				
				$manager = $this->getDoctrine()->getManager();
				$manager->persist($member);
				$manager->flush();
				
				$member = new Member();
				$form = $this->createForm(MemberType::class,$member);
			}
		}
		return $this->render('profil/editmember.html.twig',['title'=>$title,'form'=>$form->createView()]);
	}
	
	/**
	 * @Route("/deletemember/{id}")
	 */ 
	public function deletemember(Member $member){
		$manager = $this->getDoctrine()->getManager();
		$manager->remove($member);
		$manager->flush();
		return new Response("ok");
	}
	
	/**
	 * @Route("/list/{column}-{order}")
	 */ 
	public function list($column, $order){
		$repository = $this->getDoctrine()->getRepository(Member::class);
		$members = $repository->findBy(['contact'=>$this->getUser()],[$column=>$order]);
		return $this->render('profil/list.html.twig',['members'=>$members]);
	}
	
	/**
	 * @Route("/export")
	 */ 
	public function contactsExported(Traitcsv $traitcsv){
		$repository = $this->getDoctrine()->getRepository(Member::class);
		$members = $repository->findBy(['contact'=>$this->getUser()]);
		$traitcsv->export($members);
		return new Response("Veuillez récupérer votre fichiers de contacts dans src/myfiles, s'il vous plaît");
	}
	
}
