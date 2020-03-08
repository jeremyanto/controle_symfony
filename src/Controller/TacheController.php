<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Tache;
use App\Form\TacheType;
use Symfony\Component\HttpFoundation\Request;

class TacheController extends AbstractController
{
    /**
     * @Route("/tache", name="tache")
     */
    public function index(Request $request){
        $pdo = $this->getDoctrine()->getManager();

        $tache = new Tache();

        $form = $this->createForm(TacheType::class, $tache);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $pdo->persist($tache);    
            $pdo->flush();              
        }
        $taches = $pdo->getRepository(Tache::class)->findAll();

        return $this->render('tache/index.html.twig', [
            'taches' => $taches,
            'form_ajout' => $form->createView(),
        ]);
    }

    /**
    * @Route("/tache/{id}", name="une_tache")
    */
    public function categorie(Tache $tache=null, Request $request){
        if($tache != null){

        $form = $this->createForm(TacheType::class, $tache);
               //Analyse la raquette HTTP
               $form->handleRequest($request);
               if($form->isSubmitted() && $form->isValid()){
                   $pdo = $this->getDoctrine()->getManager();
                   $pdo->persist($tache);    //prepare
                   $pdo->flush();              //execute
               }

        return $this->render('tache/tache.html.twig',[
            'tache' => $tache,
            'form_edit' => $form->createView(),
        ]);
        }
        else{
            return $this->redirectToRoute('tache');
        }
    }

    /**
     * @Route("/tache/delete/{id}", name="delete_tache")
     */
    public function delete(Tache $tache=null){
        if($tache != null){
            $pdo = $this->getDoctrine()->getManager();
            $pdo->remove($tache);
            $pdo->flush();

            $this->addFlash("success", "Tache supprimée");
        }
        else{
            $this->addFlash("danger", "Taceh introuvable");
        }

        return $this->redirectToRoute('tache');
    }
}
