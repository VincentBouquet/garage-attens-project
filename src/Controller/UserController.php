<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\InterventionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository, InterventionRepository $interventionRepository): Response
    {
        $employees = $userRepository->findAll();
        dump($employees);
        $workTimes = [];
        foreach ($employees as $employe) {
            $workTimes[$employe->getId()] = $interventionRepository->getTimeWorked($employe);
        }
        dump($workTimes);
        return $this->render('user/index.html.twig', [
            'users' => $employees,
            "workTimes" => $workTimes
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        /*
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
        */
        return $this->redirectToRoute('app_register');
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user, InterventionRepository $interventionRepository): Response
    {

        $futurIntervention = $interventionRepository->getFuturInterventionByUser($user);
        $pastIntervention = $interventionRepository->getPastInterventionByUser($user);
        $worked = $interventionRepository->getTimeWorked($user);
        if ($worked) {
            $goodWorked = $worked;
        } else {
            $goodWorked = "zéro";
        }
        dump($worked);
        dump($futurIntervention);
        dump($pastIntervention);
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'pastIntervention' => $pastIntervention,
            'futurIntervention' => $futurIntervention,
            'goodWorked' => $goodWorked,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        $this->denyAccessUnlessGranted("ROLE_ADMIN");
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
}
