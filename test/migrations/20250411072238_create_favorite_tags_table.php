<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateFavoriteTagsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('user_favorite_tags');
        $table
            ->addColumn('user_id', 'integer', ['signed' => false])
            ->addColumn('tag_id', 'integer', ['signed' => false])
            ->addColumn('mood', 'string', ['limit' => 50])
            ->addTimestamps()
            ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE'])
            ->addForeignKey('tag_id', 'tags', 'id', ['delete'=> 'CASCADE'])
            ->create();
    }
}
