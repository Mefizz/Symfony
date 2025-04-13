<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Profile;
use App\Entity\Tag;
use App\Entity\Post;
use App\Entity\UserFavoriteTag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DateTimeImmutable;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Users
        $user1 = new User();
        $user1->setName('Alice');
        $user1->setEmail('alice@example.com');
        $manager->persist($user1);

        $user2 = new User();
        $user2->setName('Bob');
        $user2->setEmail('bob@example.com');
        $manager->persist($user2);

        // Flush to get generated IDs
        $manager->flush();

        // Profiles
        $profile1 = new Profile();
        $profile1->setBio('Alice’s bio');
        $profile1->setUser($user1); // передаємо сам об'єкт, а не ID
        $manager->persist($profile1);

        $profile2 = new Profile();
        $profile2->setBio('Bob’s bio');
        $profile2->setUser($user2);
        $manager->persist($profile2);

        // Tags
        $tag1 = new Tag();
        $tag1->setName('Symfony');
        $manager->persist($tag1);

        $tag2 = new Tag();
        $tag2->setName('Doctrine');
        $manager->persist($tag2);

        // Posts
        $post1 = new Post();
        $post1->setTitle('Post One');
        $post1->setContent('First post content');
        $post1->setUser($user1);
        $post1->setCreatedAt(new DateTimeImmutable());
        $post1->setUpdatedAt(new DateTimeImmutable());
        $post1->addTag($tag1); // використовуємо метод додавання
        $manager->persist($post1);

        $post2 = new Post();
        $post2->setTitle('Post Two');
        $post2->setContent('Second post content');
        $post2->setUser($user2);
        $post2->setCreatedAt(new DateTimeImmutable());
        $post2->setUpdatedAt(new DateTimeImmutable());
        $post2->addTag($tag2);
        $manager->persist($post2);

        // User Favorite Tags
        $fav1 = new UserFavoriteTag();
        $fav1->setUser($user1);
        $fav1->setTag($tag1);
        $fav1->setMood('love');
        $fav1->setCreatedAt(new DateTimeImmutable());
        $fav1->setUpdatedAt(new DateTimeImmutable());
        $manager->persist($fav1);

        $fav2 = new UserFavoriteTag();
        $fav2->setUser($user2);
        $fav2->setTag($tag2);
        $fav2->setMood('like');
        $fav2->setCreatedAt(new DateTimeImmutable());
        $fav2->setUpdatedAt(new DateTimeImmutable());
        $manager->persist($fav2);

        $manager->flush();
    }
}
