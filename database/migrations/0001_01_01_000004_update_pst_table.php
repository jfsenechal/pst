<?php

use App\Constant\DepartmentEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('strategic_objectives', function (Blueprint $table) {
            $table->enum('department', DepartmentEnum::toArray())
                ->nullable(false)
                ->change();
        });

        Schema::table('operational_objectives', function (Blueprint $table) {
            $table->enum('department', DepartmentEnum::toArray())
                ->nullable(false)
                ->change();
        });

        Schema::table('actions', function (Blueprint $table) {
            $table->enum('department', DepartmentEnum::toArray())
                ->nullable(false)
                ->change();
        });

    }

    public function down()
    {

    }
};
