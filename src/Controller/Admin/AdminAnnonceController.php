<?php

namespace App\Controller\Admin;

use App\Entity\Annonce;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminAnnonceController extends AbstractController
{

    private $manager;
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager=$manager;
    }

    /**
     * @Route("/admin/annonce", name="admin_annonce")
     * @return Response
     */
    public function index(): Response
    {
        $annonce = $this->getDoctrine()
            ->getRepository(Annonce::class)
            ->findAll();
        return $this->render('admin/annonce/index.html.twig', [
            'controller_name' => 'AdminAnnonceController',
            'annonce' => $annonce,
        ]);
    }

    #[Route('/admin/annonce/edit/{slug}', name: 'admin_annonce_edit')]

    public function edit(Request $request,Annonce $annonce,EntityManager $manager) 
    {
        $form = $this->createForm(AnnonceType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $annonce = $form->getData();
            $manager->persist($annonce);
            $manager->flush();
            $this->addFlash('success', 'Annonce Created! Knowledge is power!');
            return $this->redirectToRoute('annonce_edit');
            return $this->render('admin/annonce/admi_edit.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }
    #[Route('/admin/annonce/delete/{slug}', name: 'admin_annonce_delete')]
    public function delete(Annonce $annonce, EntityManager $manager): Response
    {
        $this->manager = $manager;
        $this->manager->remove($annonce);
        $this->manager->flush();
        $this->addFlash('success', 'Annonce supprimer avec success');
        return $this->redirectToRoute('admin_annonce');
        
    }
}
