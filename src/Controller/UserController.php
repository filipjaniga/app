<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordChangeType;
use App\Repository\UserRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController.
 *
 * @Route(
 *     "/user"
 * )
 */
class UserController extends AbstractController
{
    /**
     * Change password action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\User                          $user       User entity
     * @param \App\Repository\UserRepository            $repository User repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws ORMException
     * @throws OptimisticLockException
     * @Route(
     *     "/{id}/password_change",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="user_password_change",
     * )

     */
    public function changePassword(Request $request, User $user, UserRepository $repository, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $form = $this->createForm(PasswordChangeType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        $pass = $user->getPassword();

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $pass 
                )
            );
            $repository->save($user);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render(
            'user/passwordchange.html.twig',
            [
                'form' => $form->createView(),
                'user' => $user,
            ]
        );
    }
}
