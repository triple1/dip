<?php declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Participant;
use App\Entity\Provider;
use App\Entity\User;
use App\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserCreateController extends BaseController
{
    /**
     * @var UserPasswordHasherInterface
    */
    private $userPasswordEncoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->userPasswordEncoder = $encoder;
    }

    /**
     * @Route("/admin/user/create", name="admin_user_create", methods={"GET"})
     * @Template()
    */
    public function create()
    {
        $providers = $this->getRepository(Provider::class)->findAll();

        $user = new User(null);
        $form = $this->createForm(UserType::class, $user);

        return [
            'providers' => $providers,
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/admin/user/create", name="admin_user_create_process", methods={"POST"})
     */
    public function createProcess(Request $request)
    {
        $user = new User(null);
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            /** @var User $user */
            $user = $form->getData();
            $extraData = $request->get($form->getName());

            if (!isset($extraData['passwordPlain']['first'])) throw new \InvalidArgumentException();
            if (!isset($extraData['passwordPlain']['second'])) throw new \InvalidArgumentException();
            if (!isset($extraData['roleInProvider'])) throw new \InvalidArgumentException();
            if ($extraData['passwordPlain']['first'] !== $extraData['passwordPlain']['second']) {
                $this->addFlash('danger', 'Поле \'Пароль\' и \'Повторить пароль\' должны совподать!');
                return $this->redirectToRoute('admin_user_create');
            }

            $user->setRoles([$extraData['roleInProvider']]);

            $passwordHash = $this->userPasswordEncoder->hashPassword($user, (string)$extraData['passwordPlain']['first']);
            $user->setPassword($passwordHash);

            $idProvider = $request->get('id_provider', 0);
            /** @var Provider $provider */
            $provider = $this->getRepository(Provider::class)->find($idProvider);
            if (!$provider) throw new NotFoundHttpException('Provider not found');

            $provider->addUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Пользова успешно создан');
            return $this->redirectToRoute('admin_users');
        }

        $this->addFlash('danger', 'Введите данные!');
        return $this->redirectToRoute('admin_user_create');
    }

    /**
     * @Route("/admin/user/remove/{idUser}", name="admin_user_remove", methods={"GET"})
     * @Template()
     */
    public function remove(string $idUser)
    {
        return [];
    }

    /**
     * @Route("/admin/user/remove/{idUser}", name="admin_user_remove", methods={"POST"})
     */
    public function removeProcess(string $idUser)
    {
        return [];
    }
}
