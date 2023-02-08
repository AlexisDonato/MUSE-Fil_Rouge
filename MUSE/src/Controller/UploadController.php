<?php

namespace App\Controller;

use App\Entity\Product;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UploadController extends AbstractController
{
    #[Route('/api/upload/{product}/{image_number}', name: 'app_upload', methods: ['post'])]
    public function index(Product $product, $image_number, Request $request, EntityManagerInterface $entityManager): Response
    {
        try {
            $file = $request->files->get('image');
            $existingImage = null;
            switch ($image_number) {
                case 0:
                    $existingImage = $product->getImage();
                    if ($existingImage) {
                        unlink($this->getParameter('images_directory') . '/' . $existingImage);
                        $product->setImage(null);
                    }
                    break;
                case 1:
                    $existingImage = $product->getImage1();
                    if ($existingImage) {
                        unlink($this->getParameter('images_directory') . '/' . $existingImage);
                        $product->setImage1(null);
                    }
                    break;
                case 2:
                    $existingImage = $product->getImage2();
                    if ($existingImage) {
                        unlink($this->getParameter('images_directory') . '/' . $existingImage);
                        $product->setImage2(null);
                    }
                    break;
                default:
                    throw new Exception('Image number not recognized');
            }
            
            $fileName = $product->getName() . (($image_number > 0) ? '-' . $image_number : ('')) . '.' . $file->guessExtension();
            $file->move($this->getParameter('images_directory'), $fileName);
    
            switch ($image_number) {
                case 0:
                    $product->setImage($fileName);
                    break;
                case 1:
                    $product->setImage1($fileName);
                    break;
                case 2:
                    $product->setImage2($fileName);
                    break;
            }
    
            $entityManager->persist($product);
            $entityManager->flush();
        
    
            return $this->json([
                'message' => 'Image successfully updated',
            ]);
        } catch (Exception $err) {
            return $this->json([
                'message' => 'Error updating image',
                'error' => $err
            ], 500);
        }
    }
    
    
}
