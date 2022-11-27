<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Modules\Writing\Category\Domain\Entity\Category;

class ArticleType extends AbstractType
{
	public function buildForm(FormBuilderInterface $formBuilder, array $options): void
	{
		$formBuilder
			->add('title', TextType::class)
			->add('category', EntityType::class, [
				'class'	=> Category::class,
				'choice_label' => 'title'
			])
			->add('description', TextareaType::class, [
				'attr' => [
					'maxlength' => 255
				]
			]
			)
			->add('content', TextareaType::class)
			->add('submit', SubmitType::class)
		;
	}
}
