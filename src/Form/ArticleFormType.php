<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\ArticleWordsFilter;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var ArticleWordsFilter
     */
    private $articleWordsFilter;

    public function __construct(UserRepository $userRepository, ArticleWordsFilter $articleWordsFilter)
    {
        $this->userRepository = $userRepository;
        $this->articleWordsFilter = $articleWordsFilter;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Название статьи',
                'help' => 'Не используйте слово "собака"',
                'required' => false
            ])
            ->add('body', TextareaType::class, [
                'label' => 'Содержимое статьи'
            ])
            ->add('publishedAt', null, [
                'label' => 'Дата публикации',
                'widget' => 'single_text'
            ])
            ->add('author', EntityType::class, [
                'class' => User::class,
                'choice_label' => function (User $user) {
                    return sprintf('%s (id: %d)', $user->getFirstName(), $user->getId());
                },
                'choices' => $this->userRepository->findAllSortedByName(),
                'placeholder' => 'Выберите автора статьи',
                'invalid_message' => 'Автор не существует.'
            ])
        ;

        $builder->get('title')
            ->addModelTransformer(new CallbackTransformer(
                function ($titleTransformFromDatabase) {
                    if (null === $titleTransformFromDatabase) {
                        return '';
                    }

                    return $titleTransformFromDatabase;
                },
                function ($titleFromInput) {
                    return $this->articleWordsFilter->filter($titleFromInput, ['стакан', 'налил']);
                }
            ));

        $builder->get('body')
            ->addModelTransformer(new CallbackTransformer(
                function ($bodyTransformFromDatabase) {
                    if (null === $bodyTransformFromDatabase) {
                        return '';
                    }

                    return $bodyTransformFromDatabase;
                },
                function ($bodyFromInput) {
                    return $this->articleWordsFilter->filter($bodyFromInput, ['стакан', 'налил']);
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
