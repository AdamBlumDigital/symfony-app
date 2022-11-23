<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ArticleType extends AbstractType
{
	public function buildForm(FormBuilderInterface $formBuilder, array $options): void
	{
		$formBuilder
			->add('title', TextType::class)
			->add('description', TextareaType::class)
			->add('content', TextareaType::class)
			->add('submit', SubmitType::class)
		;
	}
}
