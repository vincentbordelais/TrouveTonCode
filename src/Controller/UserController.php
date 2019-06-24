<?php


namespace App\Controller;



use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Class MembreController
 * @package App\Controller
 */
class UserController extends AbstractController
{

    /**
     * Page d'Inscription
     * @Route("/inscription", name="user_inscription")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function inscription(Request $request,
                                UserPasswordEncoderInterface $passwordEncoder)
    {
        # Formulaire d'inscription

        # Création d'un User
        $user = new User();
        $user->setRoles(['ROLE_USER']);

        # 1. Création du Formulaire (FormBuilder)
        $form = $this->createFormBuilder($user)

            ->add('username', TextType::class, [
                'label' => 'Saisissez votre Nom',
                'attr' => [
                    'placeholder' => 'Saisissez votre Nom'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Saisissez votre Email',
                'attr' => [
                    'placeholder' => 'Saisissez votre Email'
                ]
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Saisissez votre mot de passe',
                'attr' => [
                    'placeholder' => 'Saisissez votre mot de passe'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "Je m'inscris !"
            ])
            ->getForm()
        ;

        # Traitement des données $_POST
        # Vérification des données grâce aux Asserts
        # Hydratation de notre objet User
        $form->handleRequest( $request );

        if($form->isSubmitted() && $form->isValid()) {

            # Encodage du mot de passe

            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
            # 3. Insertion dans la BDD (EntityManager $em)
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            # Notification
            $this->addFlash('notice',
                'Félicitation, vous pouvez vous connecter !');

            # Redirection
            return $this->redirectToRoute('user_connexion');

        }

        # Rendu de la vue
        return $this->render('user/inscription.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Page de connexion
     * @Route("/connexion", name="user_connexion")
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function connexion(AuthenticationUtils $authenticationUtils)
    {
        # Formulaire de connexion :
        $form = $this->createFormBuilder([
            'email' => $authenticationUtils->getLastUsername()
        ])
            ->add('email', EmailType::class, [
                'attr' => ['placeholder' => 'Email.']
            ])
            ->add('password', PasswordType::class, [
                'attr' => ['placeholder' => 'Mot de passe.']
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Connexion'
            ])
            ->getForm()
        ;

        return $this->render('user/connexion.html.twig', [
            'form' => $form->createView(),
            'error' => $authenticationUtils->getLastAuthenticationError()
        ]);

    }

    /**
     * Déconnexion d'un User
     * @Route("/deconnexion", name="user_deconnexion")
     */
    public function deconnexion()
    {
    }

}