<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Tag;
use App\Entity\UserFavoriteTag;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class FavoriteTagController extends AbstractController
{
    #[Route('/favorite-tag/add/{userId}/{tagId}/{mood}', name: 'add_favorite_tag', methods: ['POST'])]
    public function addFavoriteTag(
        int $userId,
        int $tagId,
        string $mood,
        EntityManagerInterface $em
    ): JsonResponse {
        $user = $em->getRepository(User::class)->find($userId);
        $tag = $em->getRepository(Tag::class)->find($tagId);

        if (!$user || !$tag) {
            return new JsonResponse(['error' => 'User or tag not found'], 404);
        }

        $existing = $em->getRepository(UserFavoriteTag::class)->findOneBy(['user' => $user, 'tag' => $tag]);
        if ($existing) {
            return new JsonResponse(['error' => 'Already exists'], 400);
        }

        $link = new UserFavoriteTag();
        $link->setUser($user);
        $link->setTag($tag);
        $link->setMood($mood);
        $now = new \DateTimeImmutable();
        $link->setCreatedAt($now);
        $link->setUpdatedAt($now);

        $em->persist($link);
        $em->flush();

        return new JsonResponse(['status' => 'favorite added']);
    }

    #[Route('/favorite-tag/user/{id}', name: 'user_favorite_tags', methods: ['GET'])]
    public function getUserFavoriteTags(User $user): JsonResponse
    {
        $tags = $user->getFavoriteTags()->map(fn(UserFavoriteTag $uft) => [
            'tag' => $uft->getTag()->getName(),
            'mood' => $uft->getMood(),
            'tag_id' => $uft->getTag()->getId(),
        ]);

        return new JsonResponse($tags->toArray());
    }

    #[Route('/favorite-tag/tag/{id}', name: 'tag_favorited_by_users', methods: ['GET'])]
    public function getUsersWhoFavoritedTag(Tag $tag): JsonResponse
    {
        $users = $tag->getUsersWhoFavorited()->map(fn(UserFavoriteTag $uft) => [
            'user' => $uft->getUser()->getName(),
            'user_id' => $uft->getUser()->getId(),
            'mood' => $uft->getMood(),
        ]);

        return new JsonResponse($users->toArray());
    }

    #[Route('/favorite-tag/update-mood/{userId}/{tagId}', name: 'update_favorite_tag_mood', methods: ['PUT', 'PATCH'])]
    public function updateFavoriteTagMood(
        int $userId,
        int $tagId,
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        $mood = $request->get('mood');

        $link = $em->getRepository(UserFavoriteTag::class)->findOneBy([
            'user' => $userId,
            'tag' => $tagId
        ]);

        if (!$link) {
            return new JsonResponse(['error' => 'Favorite tag not found'], 404);
        }

        $link->setMood($mood);
        $link->setUpdatedAt(new \DateTimeImmutable());

        $em->flush();

        return new JsonResponse(['status' => 'mood updated']);
    }

    #[Route('/favorite-tag/remove/{userId}/{tagId}', name: 'remove_favorite_tag', methods: ['DELETE'])]
    public function removeFavoriteTag(
        int $userId,
        int $tagId,
        EntityManagerInterface $em
    ): JsonResponse {
        $link = $em->getRepository(UserFavoriteTag::class)->findOneBy([
            'user' => $userId,
            'tag' => $tagId
        ]);

        if (!$link) {
            return new JsonResponse(['error' => 'Favorite tag not found'], 404);
        }

        $em->remove($link);
        $em->flush();

        return new JsonResponse(['status' => 'favorite removed']);
    }
}
