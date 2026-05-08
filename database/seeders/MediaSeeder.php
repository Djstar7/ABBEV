<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Media;
use App\Models\Category;
use Illuminate\Support\Str;

class MediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get categories
        $actionCat = Category::where('slug', 'action')->first();
        $comedyCat = Category::where('slug', 'comedie')->first();
        $dramaCat = Category::where('slug', 'drame')->first();
        $sciFiCat = Category::where('slug', 'science-fiction')->first();
        $thrillerCat = Category::where('slug', 'thriller')->first();
        $romanceCat = Category::where('slug', 'romance')->first();
        $horrorCat = Category::where('slug', 'horreur')->first();
        $animationCat = Category::where('slug', 'animation')->first();
        $animeCat = Category::where('slug', 'anime')->first();

        // Films avec images
        $films = [
            [
                'title' => 'The Dark Knight',
                'type' => 'movie',
                'description' => 'Batman affronte le Joker, un criminel anarchiste qui veut plonger Gotham City dans le chaos.',
                'category_id' => $actionCat?->id,
                'duration' => 9120, // 2h32
                'views_count' => rand(10000, 100000),
                'is_featured' => true,
                'release_year' => 2008,
                'thumbnail_path' => 'https://image.tmdb.org/t/p/w500/qJ2tW6WMUDux911r6m7haRef0WH.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w500/qJ2tW6WMUDux911r6m7haRef0WH.jpg',
                'banner_path' => 'https://image.tmdb.org/t/p/original/hkBaDkMWbLaf8B1lsWsKX7Ew3Xq.jpg',
            ],
            [
                'title' => 'Inception',
                'type' => 'movie',
                'description' => 'Un voleur qui s\'infiltre dans les rêves des gens pour voler leurs secrets se voit confier la mission inverse : implanter une idée.',
                'category_id' => $sciFiCat?->id,
                'duration' => 8880, // 2h28
                'views_count' => rand(10000, 100000),
                'is_featured' => true,
                'release_year' => 2010,
                'thumbnail_path' => 'https://image.tmdb.org/t/p/w500/oYuLEt3zVCKq57qu2F8dT7NIa6f.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w500/oYuLEt3zVCKq57qu2F8dT7NIa6f.jpg',
                'banner_path' => 'https://image.tmdb.org/t/p/original/s3TBrRGB1iav7gFOCNx3H31MoES.jpg',
            ],
            [
                'title' => 'Interstellar',
                'type' => 'movie',
                'description' => 'Un groupe d\'explorateurs utilise un trou de ver pour voyager au-delà des limites du voyage spatial humain.',
                'category_id' => $sciFiCat?->id,
                'duration' => 10140, // 2h49
                'views_count' => rand(10000, 100000),
                'is_featured' => true,
                'release_year' => 2014,
                'thumbnail_path' => 'https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w500/gEU2QniE6E77NI6lCU6MxlNBvIx.jpg',
                'banner_path' => 'https://image.tmdb.org/t/p/original/xu9zaAevzQ5nnrsXN6JcahLnG4i.jpg',
            ],
            [
                'title' => 'Avengers: Endgame',
                'type' => 'movie',
                'description' => 'Après les événements dévastateurs, les Avengers s\'assemblent une fois de plus pour inverser les actions de Thanos.',
                'category_id' => $actionCat?->id,
                'duration' => 10860, // 3h01
                'views_count' => rand(10000, 100000),
                'is_featured' => true,
                'release_year' => 2019,
                'thumbnail_path' => 'https://image.tmdb.org/t/p/w500/or06FN3Dka5tukK1e9sl16pB3iy.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w500/or06FN3Dka5tukK1e9sl16pB3iy.jpg',
                'banner_path' => 'https://image.tmdb.org/t/p/original/7RyHsO4yDXtBv1zUU3mTpHeQ0d5.jpg',
            ],
            [
                'title' => 'Parasite',
                'type' => 'movie',
                'description' => 'Une famille pauvre s\'infiltre dans la vie d\'une famille riche avec des conséquences inattendues.',
                'category_id' => $dramaCat?->id,
                'duration' => 7920, // 2h12
                'views_count' => rand(10000, 100000),
                'is_featured' => true,
                'release_year' => 2019,
                'thumbnail_path' => 'https://image.tmdb.org/t/p/w500/7IiTTgloJzvGI1TAYymCfbfl3vT.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w500/7IiTTgloJzvGI1TAYymCfbfl3vT.jpg',
                'banner_path' => 'https://image.tmdb.org/t/p/original/TU9NIjwzjoKPwQHoHshkFcQUCG.jpg',
            ],
            [
                'title' => 'Joker',
                'type' => 'movie',
                'description' => 'Dans les années 1980 à Gotham City, un comédien raté sombre dans la folie et devient un tueur psychopathe.',
                'category_id' => $dramaCat?->id,
                'duration' => 7320, // 2h02
                'views_count' => rand(10000, 100000),
                'is_featured' => true,
                'release_year' => 2019,
                'thumbnail_path' => 'https://image.tmdb.org/t/p/w500/udDclJoHjfjb8Ekgsd4FDteOkCU.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w500/udDclJoHjfjb8Ekgsd4FDteOkCU.jpg',
                'banner_path' => 'https://image.tmdb.org/t/p/original/n6bUvigpRFqSwmPp1m2YADdbRBc.jpg',
            ],
            [
                'title' => 'Spider-Man: No Way Home',
                'type' => 'movie',
                'description' => 'Peter Parker demande l\'aide du docteur Strange, ce qui ouvre le multiverse et amène des visiteurs d\'autres dimensions.',
                'category_id' => $actionCat?->id,
                'duration' => 9000, // 2h30
                'views_count' => rand(10000, 100000),
                'is_featured' => false,
                'release_year' => 2021,
                'thumbnail_path' => 'https://image.tmdb.org/t/p/w500/1g0dhYtq4irTY1GPXvft6k4YLjm.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w500/1g0dhYtq4irTY1GPXvft6k4YLjm.jpg',
                'banner_path' => 'https://image.tmdb.org/t/p/original/iQFcwSGbZXMkeyKrxbPnwnRo5fl.jpg',
            ],
            [
                'title' => 'The Shawshank Redemption',
                'type' => 'movie',
                'description' => 'Deux hommes emprisonnés nouent une amitié sur plusieurs années, trouvant réconfort et rédemption à travers des actes de décence commune.',
                'category_id' => $dramaCat?->id,
                'duration' => 8520, // 2h22
                'views_count' => rand(10000, 100000),
                'is_featured' => true,
                'release_year' => 1994,
                'thumbnail_path' => 'https://image.tmdb.org/t/p/w500/q6y0Go1tsGEsmtFryDOJo3dEmqu.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w500/q6y0Go1tsGEsmtFryDOJo3dEmqu.jpg',
                'banner_path' => 'https://image.tmdb.org/t/p/original/kXfqcdQKsToO0OUXHcrrNCHDBzO.jpg',
            ],
            [
                'title' => 'Pulp Fiction',
                'type' => 'movie',
                'description' => 'Les vies de deux tueurs à gages, un boxeur, un gangster et sa femme s\'entrecroisent dans quatre histoires de violence et de rédemption.',
                'category_id' => $thrillerCat?->id,
                'duration' => 9240, // 2h34
                'views_count' => rand(10000, 100000),
                'is_featured' => false,
                'release_year' => 1994,
                'thumbnail_path' => 'https://image.tmdb.org/t/p/w500/d5iIlFn5s0ImszYzBPb8JPIfbXD.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w500/d5iIlFn5s0ImszYzBPb8JPIfbXD.jpg',
                'banner_path' => 'https://image.tmdb.org/t/p/original/suaEOtk1N1sgg2MTM7oZd2cfVp3.jpg',
            ],
            [
                'title' => 'Le Parrain',
                'type' => 'movie',
                'description' => 'Le patriarche vieillissant d\'une dynastie du crime organisé transfère le contrôle de son empire clandestin à son fils réticent.',
                'category_id' => $dramaCat?->id,
                'duration' => 10500, // 2h55
                'views_count' => rand(10000, 100000),
                'is_featured' => true,
                'release_year' => 1972,
                'thumbnail_path' => 'https://image.tmdb.org/t/p/w500/3bhkrj58Vtu7enYsRolD1fZdja1.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w500/3bhkrj58Vtu7enYsRolD1fZdja1.jpg',
                'banner_path' => 'https://image.tmdb.org/t/p/original/tmU7GeKVybMWFButWEGl2M4GeiP.jpg',
            ],
            [
                'title' => 'Le Roi des Lions',
                'type' => 'movie',
                'description' => 'Un jeune lion prince fuit son royaume après la mort tragique de son père, pour finalement découvrir sa véritable destinée et récupérer son trône.',
                'category_id' => $animationCat?->id,
                'duration' => 5280, // 1h28
                'views_count' => rand(10000, 100000),
                'is_featured' => false,
                'release_year' => 2019,
                'thumbnail_path' => 'https://image.tmdb.org/t/p/w500/dzBtMocZuJbjLOXvrl4zGYigDzh.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w500/dzBtMocZuJbjLOXvrl4zGYigDzh.jpg',
                'banner_path' => 'https://image.tmdb.org/t/p/original/1TUg5pO1VZ4B0Q1amk3OlXvlpXV.jpg',
            ],
            [
                'title' => 'La La Land',
                'type' => 'movie',
                'description' => 'Une pianiste de jazz et une actrice aspirante tombent amoureux à Los Angeles tout en poursuivant leurs rêves.',
                'category_id' => $romanceCat?->id,
                'duration' => 7680, // 2h08
                'views_count' => rand(10000, 100000),
                'is_featured' => false,
                'release_year' => 2016,
                'thumbnail_path' => 'https://image.tmdb.org/t/p/w500/uDO8zWDhfWwoFdKS4fzkUJt0Rf0.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w500/uDO8zWDhfWwoFdKS4fzkUJt0Rf0.jpg',
                'banner_path' => 'https://image.tmdb.org/t/p/original/fp6X6yhgcxzxCpmM0EZNkJVmSLk.jpg',
            ],
        ];

        // Séries avec images
        $series = [
            [
                'title' => 'Breaking Bad',
                'type' => 'series',
                'description' => 'Un professeur de chimie au lycée diagnostiqué d\'un cancer en phase terminale s\'associe à un ancien élève pour fabriquer et vendre de la méthamphétamine.',
                'category_id' => $dramaCat?->id,
                'duration' => 2880, // 48min par épisode
                'views_count' => rand(50000, 200000),
                'is_featured' => true,
                'release_year' => 2008,
                'seasons' => 5,
                'thumbnail_path' => 'https://image.tmdb.org/t/p/w500/ggFHVNu6YYI5L9pCfOacjizRGt.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w500/ggFHVNu6YYI5L9pCfOacjizRGt.jpg',
                'banner_path' => 'https://image.tmdb.org/t/p/original/tsRy63Mu5cu8etL1X7ZLyf7UP1M.jpg',
            ],
            [
                'title' => 'Stranger Things',
                'type' => 'series',
                'description' => 'Quand un jeune garçon disparaît, sa mère, un chef de police et ses amis doivent affronter des forces terrifiantes pour le récupérer.',
                'category_id' => $sciFiCat?->id,
                'duration' => 3000, // 50min par épisode
                'views_count' => rand(50000, 200000),
                'is_featured' => true,
                'release_year' => 2016,
                'seasons' => 4,
                'thumbnail_path' => 'https://image.tmdb.org/t/p/w500/49WJfeN0moxb9IPfGn8AIqMGskD.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w500/49WJfeN0moxb9IPfGn8AIqMGskD.jpg',
                'banner_path' => 'https://image.tmdb.org/t/p/original/56v2KjBlU4XaOv9rVYEQypROD7P.jpg',
            ],
            [
                'title' => 'Game of Thrones',
                'type' => 'series',
                'description' => 'Neuf familles nobles se battent pour le contrôle des terres de Westeros, tandis qu\'un ancien ennemi revient après des millénaires de sommeil.',
                'category_id' => $dramaCat?->id,
                'duration' => 3600, // 60min par épisode
                'views_count' => rand(50000, 200000),
                'is_featured' => true,
                'release_year' => 2011,
                'seasons' => 8,
                'thumbnail_path' => 'https://image.tmdb.org/t/p/w500/1XS1oqL89opfnbLl8WnZY1O1uJx.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w500/1XS1oqL89opfnbLl8WnZY1O1uJx.jpg',
                'banner_path' => 'https://image.tmdb.org/t/p/original/suopoADq0k8YZr4dQXcU6pToj6s.jpg',
            ],
            [
                'title' => 'Money Heist (La Casa de Papel)',
                'type' => 'series',
                'description' => 'Un groupe de criminels se rassemble sous la direction d\'un cerveau pour perpétuer le plus grand braquage de l\'histoire de l\'Espagne.',
                'category_id' => $thrillerCat?->id,
                'duration' => 4200, // 70min par épisode
                'views_count' => rand(50000, 200000),
                'is_featured' => true,
                'release_year' => 2017,
                'seasons' => 5,
                'thumbnail_path' => 'https://image.tmdb.org/t/p/w500/MoEKaPFHABtA1xKoOteirGaHl1.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w500/MoEKaPFHABtA1xKoOteirGaHl1.jpg',
                'banner_path' => 'https://image.tmdb.org/t/p/original/piuRhGiQBYWgW668eLTn2hl1Fwm.jpg',
            ],
            [
                'title' => 'Friends',
                'type' => 'series',
                'description' => 'Suit la vie personnelle et professionnelle de six amis de 20 et 30 ans vivant à Manhattan.',
                'category_id' => $comedyCat?->id,
                'duration' => 1320, // 22min par épisode
                'views_count' => rand(50000, 200000),
                'is_featured' => true,
                'release_year' => 1994,
                'seasons' => 10,
                'thumbnail_path' => 'https://image.tmdb.org/t/p/w500/f496cm9enuEsZkSPzCwnTESEK5s.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w500/f496cm9enuEsZkSPzCwnTESEK5s.jpg',
                'banner_path' => 'https://image.tmdb.org/t/p/original/l0qVZIpXtIo7km9u5Yqh0nKPOr5.jpg',
            ],
            [
                'title' => 'The Walking Dead',
                'type' => 'series',
                'description' => 'Un shérif adjoint se réveille d\'un coma pour découvrir un monde apocalyptique ravagé par des zombies.',
                'category_id' => $horrorCat?->id,
                'duration' => 2640, // 44min par épisode
                'views_count' => rand(50000, 200000),
                'is_featured' => false,
                'release_year' => 2010,
                'seasons' => 11,
                'thumbnail_path' => 'https://image.tmdb.org/t/p/w500/xf9wuDcqlUPWABZNeDKPbZUjWx0.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w500/xf9wuDcqlUPWABZNeDKPbZUjWx0.jpg',
                'banner_path' => 'https://image.tmdb.org/t/p/original/wWJTAKVBHz7NAWbj7hopUW7rLKX.jpg',
            ],
            [
                'title' => 'The Office',
                'type' => 'series',
                'description' => 'Un documentaire filmé sur la vie quotidienne des employés d\'un bureau de vente de papier dans une petite ville américaine.',
                'category_id' => $comedyCat?->id,
                'duration' => 1320, // 22min par épisode
                'views_count' => rand(50000, 200000),
                'is_featured' => false,
                'release_year' => 2005,
                'seasons' => 9,
                'thumbnail_path' => 'https://image.tmdb.org/t/p/w500/7DJKHzAi83BmQrWLrYYOqcoKfhR.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w500/7DJKHzAi83BmQrWLrYYOqcoKfhR.jpg',
                'banner_path' => 'https://image.tmdb.org/t/p/original/dEMJgHKRc1SaUjnX8PFmAmIBggj.jpg',
            ],
            [
                'title' => 'The Crown',
                'type' => 'series',
                'description' => 'Suit la vie politique de la reine Elizabeth II et les événements qui ont façonné la seconde moitié du 20ème siècle.',
                'category_id' => $dramaCat?->id,
                'duration' => 3600, // 60min par épisode
                'views_count' => rand(50000, 200000),
                'is_featured' => false,
                'release_year' => 2016,
                'seasons' => 6,
                'thumbnail_path' => 'https://image.tmdb.org/t/p/w500/1M876KPjulVwppEpldhdc8V4o68.jpg',
                'cover_path' => 'https://image.tmdb.org/t/p/w500/1M876KPjulVwppEpldhdc8V4o68.jpg',
                'banner_path' => 'https://image.tmdb.org/t/p/original/wHJbVsIZuoZnJOCYI2T1J1xcgyC.jpg',
            ],
        ];

        // Anime/Manga
        $anime = [
            [
                'title' => 'Attack on Titan',
                'type' => 'series',
                'description' => 'Dans un monde où l\'humanité vit enfermée derrière d\'énormes murs pour se protéger de titans mangeurs d\'hommes, un jeune garçon jure de les exterminer tous.',
                'category_id' => $animeCat?->id,
                'duration' => 1440, // 24min par épisode
                'views_count' => rand(50000, 200000),
                'is_featured' => true,
                'release_year' => 2013,
                'seasons' => 4,
                'thumbnail_path' => 'https://cdn.myanimelist.net/images/anime/10/47347.jpg',
                'cover_path' => 'https://cdn.myanimelist.net/images/anime/10/47347.jpg',
                'banner_path' => 'https://cdn.myanimelist.net/images/anime/1988/141762.jpg',
            ],
            [
                'title' => 'One Piece',
                'type' => 'series',
                'description' => 'Monkey D. Luffy et son équipage de pirates recherchent le trésor ultime connu sous le nom de "One Piece" afin que Luffy devienne le roi des pirates.',
                'category_id' => $animeCat?->id,
                'duration' => 1440, // 24min par épisode
                'views_count' => rand(50000, 200000),
                'is_featured' => true,
                'release_year' => 1999,
                'seasons' => 21,
                'thumbnail_path' => 'https://cdn.myanimelist.net/images/anime/6/73245.jpg',
                'cover_path' => 'https://cdn.myanimelist.net/images/anime/6/73245.jpg',
                'banner_path' => 'https://cdn.myanimelist.net/images/anime/1244/138851.jpg',
            ],
            [
                'title' => 'Demon Slayer',
                'type' => 'series',
                'description' => 'Un jeune garçon devient un tueur de démons après que sa famille a été massacrée et sa petite sœur transformée en démon.',
                'category_id' => $animeCat?->id,
                'duration' => 1440, // 24min par épisode
                'views_count' => rand(50000, 200000),
                'is_featured' => true,
                'release_year' => 2019,
                'seasons' => 3,
                'thumbnail_path' => 'https://cdn.myanimelist.net/images/anime/1286/99889.jpg',
                'cover_path' => 'https://cdn.myanimelist.net/images/anime/1286/99889.jpg',
                'banner_path' => 'https://cdn.myanimelist.net/images/anime/1764/106555.jpg',
            ],
            [
                'title' => 'Naruto',
                'type' => 'series',
                'description' => 'Naruto Uzumaki, un jeune ninja qui rêve de devenir le chef de son village, se lance dans une quête d\'acceptation et de reconnaissance.',
                'category_id' => $animeCat?->id,
                'duration' => 1440, // 24min par épisode
                'views_count' => rand(50000, 200000),
                'is_featured' => true,
                'release_year' => 2002,
                'seasons' => 9,
                'thumbnail_path' => 'https://cdn.myanimelist.net/images/anime/13/17405.jpg',
                'cover_path' => 'https://cdn.myanimelist.net/images/anime/13/17405.jpg',
                'banner_path' => 'https://cdn.myanimelist.net/images/anime/1565/111305.jpg',
            ],
            [
                'title' => 'My Hero Academia',
                'type' => 'series',
                'description' => 'Dans un monde où presque tout le monde possède des super-pouvoirs, un garçon sans pouvoir rêve de devenir le plus grand héros.',
                'category_id' => $animeCat?->id,
                'duration' => 1440, // 24min par épisode
                'views_count' => rand(50000, 200000),
                'is_featured' => true,
                'release_year' => 2016,
                'seasons' => 6,
                'thumbnail_path' => 'https://cdn.myanimelist.net/images/anime/10/78745.jpg',
                'cover_path' => 'https://cdn.myanimelist.net/images/anime/10/78745.jpg',
                'banner_path' => 'https://cdn.myanimelist.net/images/anime/1546/122599.jpg',
            ],
            [
                'title' => 'Death Note',
                'type' => 'series',
                'description' => 'Un lycéen trouve un cahier surnaturel qui lui permet de tuer quiconque en écrivant son nom dedans, et décide de créer un monde sans crime.',
                'category_id' => $animeCat?->id,
                'duration' => 1440, // 24min par épisode
                'views_count' => rand(50000, 200000),
                'is_featured' => true,
                'release_year' => 2006,
                'seasons' => 1,
                'thumbnail_path' => 'https://cdn.myanimelist.net/images/anime/9/9453.jpg',
                'cover_path' => 'https://cdn.myanimelist.net/images/anime/9/9453.jpg',
                'banner_path' => 'https://cdn.myanimelist.net/images/anime/1079/138100.jpg',
            ],
            [
                'title' => 'Jujutsu Kaisen',
                'type' => 'series',
                'description' => 'Un lycéen rejoint une organisation secrète de sorciers pour tuer une malédiction puissante.',
                'category_id' => $animeCat?->id,
                'duration' => 1440, // 24min par épisode
                'views_count' => rand(50000, 200000),
                'is_featured' => true,
                'release_year' => 2020,
                'seasons' => 2,
                'thumbnail_path' => 'https://cdn.myanimelist.net/images/anime/1171/109222.jpg',
                'cover_path' => 'https://cdn.myanimelist.net/images/anime/1171/109222.jpg',
                'banner_path' => 'https://cdn.myanimelist.net/images/anime/1792/138653.jpg',
            ],
            [
                'title' => 'Fullmetal Alchemist: Brotherhood',
                'type' => 'series',
                'description' => 'Deux frères utilisent l\'alchimie interdite dans une tentative de ramener leur mère à la vie, avec des conséquences dévastatrices.',
                'category_id' => $animeCat?->id,
                'duration' => 1440, // 24min par épisode
                'views_count' => rand(50000, 200000),
                'is_featured' => true,
                'release_year' => 2009,
                'seasons' => 1,
                'thumbnail_path' => 'https://cdn.myanimelist.net/images/anime/1223/96541.jpg',
                'cover_path' => 'https://cdn.myanimelist.net/images/anime/1223/96541.jpg',
                'banner_path' => 'https://cdn.myanimelist.net/images/anime/1208/94745.jpg',
            ],
        ];

        // Insert films
        foreach ($films as $film) {
            $film['slug'] = Str::slug($film['title']);
            Media::create($film);
        }

        // Insert series
        foreach ($series as $serie) {
            $serie['slug'] = Str::slug($serie['title']);
            Media::create($serie);
        }

        // Insert anime
        foreach ($anime as $item) {
            $item['slug'] = Str::slug($item['title']);
            Media::create($item);
        }
    }
}
