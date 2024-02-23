<?php

namespace App\Command;

use App\Service\OmdbApi;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'imdb:search',
    description: 'Add a short description for your command',
)]
class ImdbSearchCommand extends Command
{
    public function __construct(private OmdbApi $omdbApi)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('keyword', InputArgument::REQUIRED, 'Type here your search')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $keyword = $input->getArgument('keyword');

        $movies = $this->omdbApi->requestAllBySearch($keyword);
        $io->success($movies['totalResults']. ' movies have been found.');
        //dump($movies);

        $renderedMovies = [];
        foreach ($movies['Search'] as $movie) {
            //$output->writeln($movie['Title']);
            $renderedMovies[] = [$movie['Title'], $movie['Year'], 'https://imdb.com/title/'.$movie['imdbID']];
        }

        $io->table(['TITLE', 'YEAR', 'IMDB PAGE'], $renderedMovies);
        /*
        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
        */
        return Command::SUCCESS;
    }
}
