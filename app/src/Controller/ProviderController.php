<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Provider;
use App\Form\ProviderCurrentEditType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProviderController extends AbstractController
{
    /**
     * @Route("/provider/uploadimage", name="upload_image_process", methods={"POST"})
    */
    public function uploadImageProcess(Request $request)
    {
        $provider = $this->getUser()->getProvider();
        if ($provider instanceof Provider) {
            /** @var UploadedFile $file */
            $file = $request->files->get('img_icon');
            $dir = __DIR__ . '/../../public/images/providers/';

            if (is_file($dir . $provider->getImgIconName())) {
                $filePath = $dir . $provider->getImgIconName();
                @unlink($filePath);
            }

            $newName = mt_rand() . '.' . $file->guessClientExtension();
            try {
                $file->move($dir, $newName);
            } catch (FileException $ex) {
                $this->addFlash('danger', 'Ошибка при загрузке файла. Проверьте наличие свободного места.');
                return $this->redirectToRoute('home');
            }

            $provider->setImgIconName($newName);
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'Логотип организации успешно загружен.');
            return $this->redirectToRoute('home');
        }
    }

    /**
     * @Route("/provider/edit", name="provider_edit", methods={"GET"})
     * @Template()
     */
    public function edit()
    {
        $provider = $this->getUser()->getProvider();
        $form = $this->createForm(ProviderCurrentEditType::class, $provider);
        return [
            'form' => $form->createView(),
            'provider' => $provider
        ];
    }
    /**
     * @Route("/provider/edit", name="provider_edit_process", methods={"POST"})
     */
    public function editProcess(Request $request)
    {
        $provider = $this->getUser()->getProvider();
        $form = $this->createForm(ProviderCurrentEditType::class, $provider);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $files = $request->files->get('provider');
            $path = __DIR__ . '/../../public/images/providers/';
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
                        return $this->redirectToRoute('provider_edit');
                    }
                }
            }

            try {
                $em->flush();
                $this->addFlash('success', 'Данные организации успешно сохранены');
            } catch (\Exception $ex) {
                $this->addFlash('danger', 'При сохранении данных организаци произошла ошибка');
                return $this->redirectToRoute('home');
            }
        } else {
            $errors = $form->getErrors();
            var_dump($errors);
            die();
        }

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/provider/remove/icon", methods={"POST"}, name="provider_remove_icon_process")
    */
    public function removeIcon()
    {
        /** @var Provider $provider */
        $provider = $this->getUser()->getProvider();
        $dir = __DIR__ . '/../../public/images/providers/';

        if (is_file($dir . $provider->getImgIconName())) {
            $filePath = $dir . $provider->getImgIconName();
            @unlink($filePath);
        }

        $provider->setImgIconName(null);
        $this->getDoctrine()->getManager()->flush();


        $this->addFlash('success', 'Лого организации успешно удалено');
        return $this->redirectToRoute('provider_edit');
    }
}
