<?php

return [

    'models' => [

        /*
         * When using the "HasPermissions" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your permissions. Of course, it
         * is often just the "Permission" model but you may use whatever you like.
         *
         * The model you want to use as a Permission model needs to implement the
         * `Spatie\Permission\Contracts\Permission` contract.
         */

        'permission' => Spatie\Permission\Models\Permission::class,

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your roles. Of course, it
         * is often just the "Role" model but you may use whatever you like.
         *
         * The model you want to use as a Role model needs to implement the
         * `Spatie\Permission\Contracts\Role` contract.
         */

        'role' => Spatie\Permission\Models\Role::class,

    ],

    'table_names' => [

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your roles. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */

        'roles' => 'roles',

        /*
         * When using the "HasPermissions" trait from this package, we need to know which
         * table should be used to retrieve your permissions. We have chosen a basic
         * default value but you may easily change it to any table you like.
         */

        'permissions' => 'permissions',

        /*
         * When using the "HasPermissions" trait from this package, we need to know which
         * table should be used to retrieve your models permissions. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        'model_has_permissions' => 'model_has_permissions',

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * table should be used to retrieve your models roles. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        'model_has_roles' => 'model_has_roles',

        /*
         * When using the "HasPermissions" trait from this package, we need to know which
         * table should be used to retrieve your roles permissions. We have chosen a
         * basic default value but you may easily change it to any table you like.
         */

        'role_has_permissions' => 'role_has_permissions',
    ],

    'column_names' => [
        /*
         * Change this if you want to name the related model primary key differently.
         *
         * For example, if you've changed the primary key name of your User model
         * to `uuid`, you can set it here to `uuid`.
         */

        'model_morph_key' => 'model_id',

        /*
         * Change this if you want to name the team primary key differently.
         *
         * For example, if you've changed the primary key name of your Team model
         * to `uuid`, you can set it here to `uuid`.
         *
         * Note: `team_foreign_key` is used by the HasPermissions and HasRoles traits
         * in the context of teams. If you are not using teams, this setting
         * is irrelevant.
         */

        'team_foreign_key' => 'team_id',
    ],

    /*
     * When set to true, the package will register routes for the health check.
     */
    'register_health_check_routes' => false,

    /*
     * When set to true, the package will register permissions via the gate.
     * If you want to use Laravels policy checks, you should set this to false
     * and define your own policies for the permission and role model.
     */

    'register_permission_check_method' => true,

    /*
     * When set to true, the package will dynamically check the relation between
     * the model and the permission or role model. This will result in an
     * additional query for each check. You can disable this if you
     * manually maintain the relationship between the model and the
     * permission or role model. In production, you should set this to false
     * if you do not need this check.
     */
    'check_database_consistency' => true,


    'display_permission_in_exception' => false,

    /*
     * By default, wildcard permissions are evaluated using the `Str::is()` method.
     * This allows for quite powerful wildcard definitions.
     *
     * However, evaluating regular expressions ('/some-regex/') is more powerful.
     *
     * You can disable this feature by setting this value to `false`.
     */

    'enable_wildcard_permission' => false,

    'cache' => [

        /*
         * By default all permissions are cached for 24 hours to speed up performance.
         * When permissions or roles are updated the cache is flushed automatically.
         */

        'expiration_time' => \DateInterval::createFromDateString('24 hours'),

        /*
         * The cache key used to store all permissions.
         */

        'key' => 'spatie.permission.cache',

        /*
         * When checking for a permission against a model by passing a Permission
         * instance to the check, this key determines what attribute on the
         * Permissions model is used to cache against.
         *
         * Ideally, this should match your database's primary key type.
         */
        'model_key' => 'name',

        /*
         * You may optionally indicate a specific cache driver to use for permission and
         * role caching using any of the `store` drivers listed in the cache.php
         * configuration file. Using 'default' here means to use the `default`
         * cache driver in `config/cache.php`.
         */

        'store' => 'default',
    ],

    /*
     * Defining a permission_removed_from_role_event will allow you to listen for
     * when a permission is removed from a role.
     *
     * Example:
     * 'permission_removed_from_role_event' => App\Events\PermissionRemovedFromRole::class,
     */
    'permission_removed_from_role_event' => null,

    /*
     * Defining a permission_added_to_role_event will allow you to listen for
     * when a permission is added to a role.
     *
     * Example:
     * 'permission_added_to_role_event' => App\Events\PermissionAddedToRole::class,
     */
    'permission_added_to_role_event' => null,

];
