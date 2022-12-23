<?php

declare(strict_types=1);

namespace App\Modules\Writing\Article\Application\Command;

use App\Modules\Writing\Article\Domain\Repository\ArticleRepositoryInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 *	@todo	Figure out how to use translations in Console Commands.
 *			using `getenv('LANG')` gets an immediate result (en_US.UTF-8)
 *			but there is likely a better way to do this.
 */
#[AsCommand(
    name: 'app:article:find:all',
    description: 'Finds all Articles'
)]
final class FindAllArticlesConsoleCommand extends Command
{
    private ArticleRepositoryInterface $articleRepository;

    public function __construct(
        ArticleRepositoryInterface $articleRepository
    ) {
        $this->articleRepository = $articleRepository;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setHelp('This command lists all Articles.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        if ($io->confirm('List all Articles? This could take a lot of memory.', false)) {
            $io->title('All Articles');

            $articles = $this->articleRepository->findAll();

            $articleArray = [];
            foreach ($articles as $article) {
                $artId = (string) $article->getId();
                $artTitle = $article->getTitle();
                $articleArray[$artId] = $artTitle;
            }
            $articleId = $io->choice('Choose an Article:', $articleArray);

            return Command::SUCCESS;
        } else {
            $io->error('Article Listing aborted.');

            return Command::FAILURE;
        }
    }
}
