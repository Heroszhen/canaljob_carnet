<?php
namespace App\Service;

use App\Entity\Member;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class Traitcsv{
	private $params;
	
	public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;
    }
	
	public function export($members){
		$fp = fopen($this->params->get('upload_dir').'mycontacts.csv','w');
		fputcsv($fp, ["nom","prénom","email","adresse"]," ");
		$array=[];
		foreach($members as $member){
			$array = [$member->getLastname(),$member->getFirstname(),$member->getEmail(),$member->getAddress()];
			fputcsv($fp,$array," ");
		}
		fclose($fp);
	}
	
	public function import($file,$user,$manager){
		$fp = fopen($this->params->get('upload_dir').''.$file,'r'); 
		while(($data = fgetcsv($fp))!==FALSE){
			if(!preg_match('/^nom;.*/', $data[0], $matches, PREG_OFFSET_CAPTURE)
			){
				$array = explode( ";", $data[0] );
				$member = new Member();
				$member->setLastname($array[0]);
				$member->setFirstname($array[1]);
				$member->setEmail($array[2]);
				$member->setAddress($array[3]);
				$member->setContact($user);
				$manager->persist($member);
				$manager->flush();
				//unlink($this->params->get('upload_dir').$file);
			}
		}
		fclose($fp);
	}
}

?>
