<?php

namespace App\Controller\Admin;

use App\Entity\Cart;
use App\Entity\User;
use App\Entity\Address;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\Supplier;
use App\Entity\OrderDetails;
use App\Repository\CartRepository;
use App\Repository\UserRepository;
use App\Repository\AddressRepository;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\SupplierRepository;
use App\Repository\OrderDetailsRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    private UserRepository $userRepository;
    private AddressRepository $addressRepository;
    private SupplierRepository $supplierRepository;
    private CategoryRepository $categoryRepository;
    private ProductRepository $productRepository;
    private OrderDetailsRepository $orderDetails;
    private CartRepository $cartRepository;


    public function __construct(UserRepository $userRepository, AddressRepository $addressRepository, SupplierRepository $supplierRepository, CategoryRepository $categoryRepository, ProductRepository $productRepository, OrderDetailsRepository $orderDetails, CartRepository $cartRepository)
    {
        $this->userRepository = $userRepository;
        $this->addressRepository = $addressRepository;
        $this->supplierRepository = $supplierRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
        $this->orderDetails = $orderDetails;
        $this->cartRepository = $cartRepository;

    }


    // #[IsGranted('ROLE_SHIP')]
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
         // return parent::index();

        if (!$this->IsGranted('ROLE_SHIP')) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('login');  
        }

        $users = $this->userRepository->findAll();

        $addresses = $this->addressRepository->findAll();

        $suppliers = $this->supplierRepository->findAll();

        $categories = $this->categoryRepository->findAll();

        $products = $this->productRepository->findAll();

        $orderDetails = $this->orderDetails->findAll();

        $carts = $this->cartRepository->findAll();

        $ordersByDate = $this->cartRepository->findOrdersByDate();

        $ordersByYear = $this->cartRepository->findOrdersByYear();

        $numbersByDate = $this->cartRepository->findNumbersByDate();

        $usersByDate = $this->cartRepository->findUsersByDate();

        $salesBySupplier = $this->cartRepository->findSalesBySupplier();

        $salesByProduct = $this->cartRepository->findSalesByProduct();

        $salesByUser = $this->cartRepository->findSalesByUser();

        $ordersByUser = $this->cartRepository->findOrdersByUser();

        $orderedProducts = $this->cartRepository->findOrderedProducts();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('dashboard/dashboard.html.twig', [
            'users' => $users,
            'addresses' => $addresses,
            'suppliers' => $suppliers,
            'categories' => $categories,
            'products' => $products,
            'orderDetails' => $orderDetails,
            'carts' => $carts,
            'ordersByDate' => $ordersByDate,
            'ordersByYear' => $ordersByYear,
            'salesBySupplier' => $salesBySupplier,
            'salesByProduct' => $salesByProduct,
            'salesByUser' => $salesByUser,
            'ordersByUser' => $ordersByUser,
            'orderedProducts' => $orderedProducts,
            'numbersByDate' => $numbersByDate,
            'usersByDate' => $usersByDate,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MUSE')
            ->renderContentMaximized();
            
        
    }

public function configureUserMenu(UserInterface $user): UserMenu
{
    if (!$user instanceof User) {
        throw new \Exception('Wrong User');
    }
    return parent::configureUserMenu($user);
        // ->setAvatarUrl($user->getAvatarUri());
}

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-dashboard');
        yield MenuItem::linkToCrud('Users', 'fas fa-users', User::class);
        yield MenuItem::linkToCrud('Addresses', 'fa fa-location-dot', Address::class);
        yield MenuItem::linkToCrud('Suppliers', 'fa fa-warehouse', Supplier::class);
        yield MenuItem::linkToCrud('Categories', 'fa fa-list-ul', Category::class);
        yield MenuItem::linkToCrud('Products', 'fa fa-guitar', Product::class);
        yield MenuItem::linkToCrud('Orders Details', 'fa fa-folder-tree', OrderDetails::class);
        yield MenuItem::linkToCrud('Carts', 'fa fa-cart-shopping', Cart::class);
        yield MenuItem::linkToUrl("Page d'accueil", 'fas fa-home', $this->generateUrl('app_home'));
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }


    public function configureActions(): Actions 
    {
        return parent::configureActions()
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
        
    }

    public function configureAssets(): Assets 
    {
        // return $assets
        //         ->addCssFile('css/admin.css');
        return parent::configureAssets();

        
    }


    // Chart.js doesn't work...
}
