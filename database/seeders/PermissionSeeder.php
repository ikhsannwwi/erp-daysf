<?php

namespace Database\Seeders;

use App\Models\admin\Module;
use Illuminate\Database\Seeder;
use App\Models\admin\ModuleAccess;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Module::truncate();
        ModuleAccess::truncate();
        $modules = [
            [
                "identifiers"   => "log_system",
                "name"          => "Log System",
                "access"        => [
                    [
                        "identifiers" => "view",
                        "name"        => "View",
                    ]
                ]
            ],
            [
                "identifiers"   => "user_group",
                "name"          => "User Group",
                "access"        => [
                    [
                        "identifiers" => "view",
                        "name"        => "View",
                    ],
                    [
                        "identifiers" => "add",
                        "name"        => "Add",
                    ],
                    [
                        "identifiers" => "edit",
                        "name"        => "Edit",
                    ],
                    [
                        "identifiers" => "status",
                        "name"        => "Status",
                    ],
                    [
                        "identifiers" => "detail",
                        "name"        => "Detail",
                    ],
                    [
                        "identifiers" => "delete",
                        "name"        => "Delete",
                    ],
                    [
                        "identifiers" => "status",
                        "name"        => "Status",
                    ]
                ]
            ],
            [
                "identifiers"   => "user",
                "name"          => "User",
                "access"        => [
                    [
                        "identifiers" => "view",
                        "name"        => "View",
                    ],
                    [
                        "identifiers" => "add",
                        "name"        => "Add",
                    ],
                    [
                        "identifiers" => "edit",
                        "name"        => "Edit",
                    ],
                    [
                        "identifiers" => "status",
                        "name"        => "Status",
                    ],
                    [
                        "identifiers" => "detail",
                        "name"        => "Detail",
                    ],
                    [
                        "identifiers" => "delete",
                        "name"        => "Delete",
                    ],
                    [
                        "identifiers" => "status",
                        "name"        => "Status",
                    ],
                    [
                        "identifiers" => "arsip",
                        "name"        => "Arsip",
                    ],
                    [
                        "identifiers" => "restore",
                        "name"        => "Restore",
                    ],
                ]
            ],
        ];


        foreach ($modules as $data) {
            $data_access = $data['access'];
            $data_module = [
                "identifiers"   => $data["identifiers"],
                "name"          => $data["name"]
            ];
            $module = Module::create($data_module);
            foreach ($data_access as $row) {
                $module->access()->create([
                    "identifiers" => $row["identifiers"],
                    "name"        => $row["name"]
                ]);
            }
        }
    }
}
