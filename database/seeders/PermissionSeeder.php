<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Permission List as array
        $permissions = [

            [
                'group_name' => 'Посты',
                'group_slug' => 'post',
                'permissions' => [
                    'post.create',
                    'post.view',
                    'post.edit',
                    'post.delete',
                ]
            ],
            [
                'group_name' => 'Категории',
                'group_slug' => 'category',
                'permissions' => [
                    // Blog Permissions
                    'category.create',
                    'category.view',
                    'category.edit',
                    'category.delete',
                ]
            ],
            [
                'group_name' => 'Теги',
                'group_slug' => 'tag',
                'permissions' => [
                    // Blog Permissions
                    'tag.create',
                    'tag.view',
                    'tag.edit',
                    'tag.delete',
                ]
            ],
            [
                'group_name' => 'Пользователи',
                'group_slug' => 'user',
                'permissions' => [
                    // admin Permissions
                    'user.create',
                    'user.view',
                    'user.edit',
                    'user.delete',
                ]
            ],
            [
                'group_name' => 'Роли',
                'group_slug' => 'role',
                'permissions' => [
                    // role Permissions
                    'role.create',
                    'role.view',
                    'role.edit',
                    'role.delete',
                ]
            ],
        ];

        // Create and Assign Permissions
        for ($i = 0; $i < count($permissions); $i++) {
            $permissionGroup = $permissions[$i]['group_name'];
            $permissionGroupSlug = $permissions[$i]['group_slug'];
            for ($j = 0; $j < count($permissions[$i]['permissions']); $j++) {
                // Create Permission
                Permission::create(['name' => $permissions[$i]['permissions'][$j], 'group_name' => $permissionGroup,'group_slug' => $permissionGroupSlug]);
            }
        }

//        $createPosr = new Permission();
//        $createPosr->name = 'Создание поста';
//        $createPosr->slug = 'create-post';
//        $createPosr->save();
//
//        $editPost = new Permission();
//        $editPost->name = 'Редактирование поста';
//        $editPost->slug = 'edit-post';
//        $editPost->save();
//
//        $accessAdmin = new Permission();
//        $accessAdmin->name = 'Доступ к админ панели';
//        $accessAdmin->slug = 'access-admin-panel';
//        $accessAdmin->save();
    }
}
