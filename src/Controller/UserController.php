<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry; 
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserType;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityManagerInterface;
class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }



    #[Route('/addadmin', name: 'addadmin')]
public function addformuser(Request $req, SessionInterface $session, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
{
    $user = new User();

    // Create the form
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($req);

    // Handle form submission
    if ($form->isSubmitted() && $form->isValid()) {
        // Encode the password
        $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);

        // Set roles to admin
        $user->setRoles(['ROLE_ADMIN']);

        // Persist the user entity
        $em->persist($user);
        $em->flush();

        // Set the user in session (if needed)
        $session->set('user', $user);

        // Redirect to the showdbuser route
        return $this->redirectToRoute('admin_list');
    }

    // Render the form
    return $this->renderForm('user/addadmin.html.twig', [
        'form' => $form,
    ]);
}

#[Route('/list', name: 'admin_list')]
public function listAdmin(UserRepository $userRepository): Response
{
    // Utilisation d'une méthode custom dans le repository pour filtrer par rôle
    $admins = $userRepository->findByRole('ROLE_ADMIN');
    
    return $this->render('user/admin_list.html.twig', [
        'admins' => $admins,
    ]);
}
#[Route('/listuser', name: 'user_list')]
public function listUser(UserRepository $userRepository): Response
{
    // Utilisation d'une méthode custom dans le repository pour filtrer par rôle
    $users = $userRepository->findByRole('ROLE_USER');
    
    return $this->render('user/user_list.html.twig', [
        'users' => $users,
    ]);
}
#[Route('/deleteuserr/{id}', name: 'delete_user')]
public function deleteUserr($id, ManagerRegistry $managerRegistry, UserRepository $userRepository): Response
{
    $em = $managerRegistry->getManager();
    $user = $userRepository->find($id);

    if ($user) {
        $em->remove($user);
        $em->flush();
        $this->addFlash('success', 'user supprimé avec succès.');
    } else {
        $this->addFlash('error', 'use non trouvé.');
    }

    return $this->redirectToRoute('user_list'); // Redirige vers la liste des utilisateurs
}

#[Route('/edit/{id}', name: 'edit_admin')]
public function editAdmin(int $id, UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager): Response
{
    $user = $userRepository->find($id);
    if (!$user) {
        throw $this->createNotFoundException('User not found');
    }

    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();
        return $this->redirectToRoute('admin_list');
    }

    return $this->render('user/edit_admin.html.twig', [
        'form' => $form->createView(),
    ]);
}



#[Route('/deleteuser/{id}', name: 'delete_admin')]
public function deleteUser($id, ManagerRegistry $managerRegistry, UserRepository $userRepository): Response
{
    $em = $managerRegistry->getManager();
    $user = $userRepository->find($id);

    if ($user) {
        $em->remove($user);
        $em->flush();
        $this->addFlash('success', 'Admin supprimé avec succès.');
    } else {
        $this->addFlash('error', 'Admin non trouvé.');
    }

    return $this->redirectToRoute('admin_list'); 
}











}
