<?php


namespace App\Service;

use Knp\Snappy\Pdf;
use Twig\Environment;
use App\Service\Cart\CartService;
use App\Repository\CartRepository;
use App\Repository\OrderDetailsRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class PdfTools
{

    private $pdf;
    private $cartRepository;
    private $orderDetails;
    private $cartService;
    private $templating;


    public function __construct(Pdf $pdf, CartRepository $cartRepository, CartService $cartService, OrderDetailsRepository $orderDetails, ?UserInterface $user, Environment $templating)
    {
        $this->pdf = $pdf;
        $this->cartRepository = $cartRepository;
        $this->orderDetails = $orderDetails;
        $this->cartService = $cartService;
        $this->user = $user;
        $this->templating = $templating;
    }

    public function generateInvoice($orderId)
    {

        $order = $this->cartRepository->find($orderId);

        $user = $this->cartService->getUser($orderId);

        $details = $this->orderDetails->findBy(['cart' => $orderId]);

        $html = $this->templating->render('email/invoice.html.twig', array(
            "order" => $order,
            'details' => $details,
            'user' => $user,
        ));

        $this->pdf->generateFromHtml($html, '../public/invoices/INVOICE-' . $order->getClientOrderId() . '.pdf', [], true);
    }
}
