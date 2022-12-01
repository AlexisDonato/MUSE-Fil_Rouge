<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Data\SearchData;
use App\Form\ContactType;
use App\Service\Cart\CartService;
use Symfony\Component\Mime\Address;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrderDetailsRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, ?UserInterface $user, CartService $cartService, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, ProductRepository $productRepository, OrderDetailsRepository $orderDetails, MailerInterface $mailer): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        $data = new SearchData();

        $name = $contact->getName();
        $refEmail = $contact->getEmail();
        $date = new \DateTime('@'.strtotime('now'));
        $contact->setEnquiryDate($date);

        $subjects = $contact->getSubject();
        foreach ($subjects as $subject) {
            echo $subject;
        }
        $message = $contact->getMessage();

        if ($user !== null) {
            $contact->setUser($user);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($contact);
            $entityManager->flush();

            $id = $contact->getId();
            $email = (new TemplatedEmail())
            ->from(new Address('info_noreply@muse.com', 'Muse MailBot'))
            ->to($contact->getEmail())
            ->cc('info@muse.com')
            ->subject('Votre demande a bien été envoyée')
            ->htmlTemplate('contact/contact_confirmation_email.html.twig')
            ->context([
                'name' => $name,
                'refEmail' => $refEmail,
                'id' => $id,
                'subject' => $subject,
                'message' => $message,
            ]);
            $mailer->send($email);

            $this->addFlash('info', 'Votre demande a bien été enregistrée, un mail de confirmation vous a été envoyé');
            return $this->redirectToRoute("app_home");
        }

        return $this->render('contact/index.html.twig', [
            'contact'   => $form->createView(),
            'items'     => $cartService->getFullCart($orderDetails),
            'count'     => $cartService->getItemCount($orderDetails),
            'total'     => $cartService->getTotal($orderDetails),
            'products'  => $productRepository->findSearch($data),
            'products2' => $productRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
            'discount'  => $productRepository->findDiscount($data),
            'discount2' => $productRepository->findProductsDiscount(),
        ]);
    }
}
