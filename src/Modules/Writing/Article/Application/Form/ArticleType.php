<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Modules\Writing\Category\Domain\Entity\Category;

class ArticleType extends AbstractType
{
	public function buildForm(FormBuilderInterface $formBuilder, array $options): void
	{
		$titleMinLength = 1;
		$titleMaxLength = 80;
		$descriptionMinLength = 50;
		$descriptionMaxLength = 160;
		/**
		 *	@todo	Abstract validation contraint values,
		 *			apply to HTML attributes as well when
		 *			applicable 
		 */
		$formBuilder
			->add('title', TextType::class, [
				'constraints' => [
					new NotBlank(),
					new Length([
						'min' => $titleMinLength,
						'max' => $titleMaxLength
					])
				],
				'attr' => [
					'minlength' => $titleMinLength,
					'maxlength' => $titleMaxLength
				]
			])
			->add('category', EntityType::class, [
				'class'	=> Category::class,
				'choice_label' => 'title'
			])
			->add('description', TextareaType::class, [
				'constraints' => [
					new NotBlank(),
					new Length([
						'min' => $descriptionMinLength,
						'max' => $descriptionMaxLength
					])
				],
				'attr' => [
					'minlength' => $descriptionMinLength,
					'maxlength' => $descriptionMaxLength
				],
				'help' => 'A summary of the article topic. This text is used in meta tags and directly follows the article title.'
			]
			)
			->add('content', TextareaType::class, [
				'attr' => [
					'rows' => 8
				]
			])
			->add('submit', SubmitType::class)
		;
	}
}
