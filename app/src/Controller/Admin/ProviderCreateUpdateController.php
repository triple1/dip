<?php declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Provider;
use App\Entity\Service;
use App\Form\ProviderType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProviderCreateUpdateController extends BaseController
{
    /**
     * @Route("/admin/provider/create", name="admin_provider_create", methods={"GET"})
     * @Template()
    */
    public function create()
    {
        $provider = new Provider();
        $form = $this->createForm(ProviderType::class, $provider);

        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/admin/provider/create", name="admin_provider_create_process", methods={"POST"})
     */
    public function createProcess(Request $request)
    {
        $form = $this->createForm(ProviderType::class, new Provider());
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            /** @var Provider $provider */
            $provider = $form->getData();
            $provider->setUserCreated($this->getUser());

            $files = $request->files->get('provider');
            $path = __DIR__ . '/../../../public/images/providers/';
            if (isset($files['logo'])) {
                /** @var UploadedFile $providerLogo */
                $providerLogo = $files['logo'];
                if ($providerLogo->isValid()) {
                    $newName = mt_rand() . '.' . $providerLogo->guessClientExtension();
                    try {
                        $providerLogo->move($path, $newName);
                        $provider->setImgIconName($newName);
                    } catch (FileException $ex) {
                        $this->addFlash('danger', 'Can not upload image: ' . $ex->getMessage());
                        return $this->redirectToRoute('admin_provider_create');
                    }
                }
            }

            try {
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($provider);
                $manager->flush();

                $this->addFlash('success', 'Provider created');
                return $this->redirectToRoute('admin_providers');
            } catch (UniqueConstraintViolationException $ex) {
                $this->addFlash('danger', 'Name of Provider should be unique');
                return $this->redirectToRoute('admin_provider_create');
            } catch (\Exception $ex) {
                $this->addFlash('danger', $ex->getMessage());
                return $this->redirectToRoute('admin_provider_create');
            } finally {
                $newName = $provider->getImgIconName();
                if (is_file($path . $newName)) {
                    @unlink($path . $newName);
                }
            }
        }

        $this->addFlash('danger', 'Please submit the form');
        return $this->redirectToRoute('admin_provider_create');
    }

    /**
     * @Route("/admin/provider/{idProvider}/assign/service/{idService}", name="admin_provider_assign_service", methods={"POST"})
     */
    public function assignService(string $idProvider, string $idService)
    {
        /** @var Provider $provider */
        $provider = $this->getRepository(Provider::class)->find($idProvider);
        if ($provider === null) return new JsonResponse(['error' => 'Provider not found']);

        /** @var Service $service */
        $service = $this->getRepository(Service::class)->find($idService);
        if ($service === null) return new JsonResponse(['error' => 'Service not found']);

        $provider->addService($service);
        $service->addProvider($provider);

        $this->getDoctrine()->getManager()->flush();

        return new JsonResponse(['success' => 'ok']);
    }
}
