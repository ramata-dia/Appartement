<?php
namespace App\Controller;
use App\Entity\Annonce;
use App\Entity\Comment;
use App\Form\AnnonceType;
use App\Form\CommentType;
use Doctrine\ORM\EntityManager;

use App\Repository\AnnonceRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class AnnonceController extends AbstractController
{
    private $repository;
    public function __construct(
        AnnonceRepository $repository,
        EntityManagerInterface $manager
    ) {
        $this->repository = $repository;
        $this->manager = $manager;
    }
    /**
     * @Route("/annonce", name="annonce")
     * @return Response
     */
    public function index(): Response
    {
        $annonces = $this->repository->findAll();
        return $this->render('/annonce/index.html.twig', [
            'annonces' => $annonces,
        ]);
    }

    /**
     * @Route("/annonce/new", name="annonce_new")
     */
    public function new(
        Request $request,
        EntityManagerInterface $manager
    ): Response {
        $annonce = new Annonce();

        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $annonce->setCreatedAt(new \Datetime());
            //recuperation de l'image depuis le formulaire
            $coverImage = $form->get('coverImage')->getData();
            if ($coverImage) {
                //creation d'un nom pour l'image l'execution recupere

                $imageName =
                    md5(uniqid()) . '.' . $coverImage->guessExtension();
                $coverImage->move(
                    $this->getParameter('coverImage_directory'),
                    $imageName
                );

                //on enregistre le nom de l'image dans la base de donnee
                $annonce->setCoverImage($imageName);
            }

            $manager->persist($annonce);
            $manager->flush();
            return $this->redirectToRoute('annonce_new');
        }
        return $this->render('annonce/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/annonce/{slug}", name="annonce_show")
     * @ParamConverter("annonce", class="App\Entity\Annonce")
     *
     */
    public function show(Annonce $annonce, Request $request): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedAt(new DateTime());
            $comment->setAnnonce($annonce);
            $this->manager->persist($comment);
            $this->manager->flush();
            $this->redirectToRoute('annonce_show',['slug' => $annonce->getSlug()]);
        }
        return $this->render('/annonce/show.html.twig', [
            'annonce' => $annonce,
            'commentForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/annonce/edit/{slug}", name="annonce_edit")
     * @ParamConverter("annonce", class="App\Entity\Annonce")
     */
    public function edit(Request $request, Annonce $annonce): Response
    {
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Récupération de l'image depuis le formulaire
            $coverImage = $form->get('coverImage')->getData();
            if ($coverImage) {
                //création d'un nom pour l'image avec l'extension récupérée
                $imageName =
                    md5(uniqid()) . '.' . $coverImage->guessExtension();

                //on déplace l'image dans le répertoire cover_image_directory avec le nom qu'on a crée
                $coverImage->move(
                    $this->getParameter('coverImage_directory'),
                    $imageName
                );

                // on enregistre le nom de l'image dans la base de données
                $annonce->setCoverImage($imageName);
            }
            $this->manager->persist($annonce);
            $this->manager->flush();
            $this->addFlash('success', 'Annonce modifier avec succès!');
            return $this->redirectToRoute('annonce_edit');
        }

        return $this->render('annonce/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/annonce/delete/{slug}", name="annonce_delete")
     * @ParamConverter("annonce", class="App\Entity\Annonce")
     */
    public function delete(Annonce $annonce, EntityManager $manager)
    {
        $this->manager = $manager;
        $this->manager->remove($annonce);
        $this->manager->flush();
        $this->addFlash('success', 'Annonce supprimer avec succès!');
        return $this->redirectToRoute('annonce_delete');
    }
}
