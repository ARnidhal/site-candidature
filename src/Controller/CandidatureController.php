<?php

namespace App\Controller;

use App\Entity\Candidature;
use App\Form\CandidatureType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CandidatureRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Security;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
class CandidatureController extends AbstractController
{
    #[Route('/candidature', name: 'app_candidature')]
    public function index(): Response
    {
        return $this->render('candidature/home.html.twig', [
            'controller_name' => 'CandidatureController',
        ]);
    }

    #[Route('/addcandidature', name: 'addcandidature')]
    public function addformauthor(ManagerRegistry $managerRegistry, FlashBagInterface $flashBag, Request $req, Security $security): Response
    {
        $x = $managerRegistry->getManager();
        $user = $security->getUser(); // Get the currently logged-in user

        if (!$user) {
            $flashBag->add('error', 'Vous devez être connecté pour soumettre une candidature.');
            return $this->redirectToRoute('app_candidature');
        }

        // Check if the user already has a candidature
        $existingCandidature = $x->getRepository(Candidature::class)->findOneBy(['user' => $user]);

        if ($existingCandidature) {
            // If a candidature already exists, show the details and allow modification
            return $this->render('candidature/details.html.twig', [
                'candidature' => $existingCandidature,
                'form' => $this->createForm(CandidatureType::class, $existingCandidature)->createView(),
            ]);
        }

        $candidature = new Candidature();
        $form = $this->createForm(CandidatureType::class, $candidature);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            $photoFile = $form->get('fichier')->getData();

            if ($photoFile) {
                $newFilename = uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('file_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle the exception
                }

                $candidature->setFichier($newFilename);
            } else {
                // Code pour affecter une image par défaut si aucune image n'est téléchargée
                $defaultImage = 'default.jpg'; // Remplacez 'default_image.jpg' par le chemin de votre image par défaut
                
                $candidature ->setFichier($defaultImage);
            }

            $candidature->setUser($user); // Associate the user with the candidature

            $flashBag->add('success', 'La candidature a été ajoutée avec succès.');
            $x->persist($candidature);
            $x->flush();
            return $this->redirectToRoute('addcandidature');
        }

        return $this->renderForm('candidature/candidature.html.twig', [
            'f' => $form,
        ]);
    }

    #[Route('/editcandidature/{id}', name: 'editcandidature')]
    public function editeditcandit($id, CandidatureRepository $candidatureRepository, ManagerRegistry $managerRegistry,Request $req): Response
    {
       
       
        $x = $managerRegistry->getManager();
        $dataid=$candidatureRepository->find($id); 
        
        $form=$this->createForm(Candidaturetype::class,$dataid);
        $form->handleRequest($req);
        if($form->isSubmitted() and $form->isValid()){

            $photoFile = $form->get('fichier')->getData();

            if ($photoFile) {
                $newFilename = uniqid().'.'.$photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('file_directory'), // Specify the directory where photos should be uploaded
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload error
                }

                // Update the photo path in the service entity
                $dataid->setFichier($newFilename);
            }

            
        $x->persist($dataid);
        $x->flush();
        return $this->redirectToRoute('details_candidature', ['id' => $dataid->getId()]);


        }
        return $this->renderForm('candidature/editcandidature.html.twig', [
            'f' => $form 
        ]);
    }
    

    #[Route('/affichercandid', name: 'affichercandida')]
    public function affichercandidat(CandidatureRepository $candidatureRepository): Response
    {
        $can = $candidatureRepository->findAll();
        return $this->render('candidature/candidatureback.html.twig', [
            'can' => $can
        ]);
    }

    #[Route('/deletecand/{id}', name: 'deletecand')]
    public function deletecand($id, ManagerRegistry $managerRegistry, CandidatureRepository $candidatureRepository): Response
    {
        $em = $managerRegistry->getManager();
        $dataid = $candidatureRepository->find($id);
        if ($dataid) {
            $em->remove($dataid);
            $em->flush();
            $this->addFlash('success', 'Candidature supprimée avec succès.');
        } else {
            $this->addFlash('error', 'Candidature non trouvée.');
        }
        return $this->redirectToRoute('affichercandida');
    }


    #[Route('/candidature/details/{id}', name: 'details_candidature')]
    public function details($id, CandidatureRepository $candidatureRepository): Response
    {
        $candidature = $candidatureRepository->find($id);
    
        if (!$candidature) {
            throw $this->createNotFoundException('La candidature demandée n\'existe pas.');
        }
    
        return $this->render('candidature/details.html.twig', [
            'candidature' => $candidature,
        ]);
    }
    



    #[Route('/candidature/{id}/accept', name: 'accept_candidature')]
public function acceptCandidature(int $id, CandidatureRepository $candidatureRepository, EntityManagerInterface $entityManager): Response
{
    $candidature = $candidatureRepository->find($id);

    if (!$candidature) {
        throw $this->createNotFoundException('Candidature not found.');
    }

    $candidature->setStatus('accepted');
    $entityManager->flush(); // Save changes to the database

    return $this->redirectToRoute('affichercandida'); // Redirect after processing
}


#[Route('/candidature/{id}/reject', name: 'reject_candidature')]
public function rejectCandidature(int $id, CandidatureRepository $candidatureRepository, EntityManagerInterface $entityManager): Response
{
    $candidature = $candidatureRepository->find($id);

    if (!$candidature) {
        throw $this->createNotFoundException('Candidature not found.');
    }

    $candidature->setStatus('rejected');
    $entityManager->flush(); // Save changes to the database

    return $this->redirectToRoute('affichercandida'); // Redirect after processing
}

    
  

#[Route('/candidature/{id}/reponse', name: 'reponse_candidature')]
public function reponseCandidature(int $id, CandidatureRepository $candidatureRepository): Response
{
    // Fetch the candidature entity using the repository and the provided ID
    $candidature = $candidatureRepository->find($id);

    // If the candidature is not found, throw a 404 exception
    if (!$candidature) {
        throw $this->createNotFoundException('La candidature demandée n\'existe pas.');
    }

    // Render the response with the fetched candidature
    return $this->render('candidature/reponse.html.twig', [
        'candidature' => $candidature,
    ]);
}

    



}
