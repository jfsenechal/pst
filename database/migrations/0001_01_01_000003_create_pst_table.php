<?php

use App\Constant\ActionPriorityEnum;
use App\Constant\ActionStateEnum;
use App\Constant\SynergyEnum;
use App\Models\Action;
use App\Models\OperationalObjective;
use App\Models\Partner;
use App\Models\Service;
use App\Models\StrategicObjective;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('strategic_objectives', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('position')->default(0);
            $table->string('idImport')->nullable();
            $table->timestamps();
        });

        Schema::create('operational_objectives', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(StrategicObjective::class)->constrained('strategic_objectives')->cascadeOnDelete();
            $table->string('name');
            $table->integer('position')->default(0);
            $table->string('idImport')->nullable();
            $table->timestamps();
        });

        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(OperationalObjective::class)->constrained('operational_objectives')->cascadeOnDelete();
            $table->string('name');
            $table->string('idImport')->nullable();
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->text('budget_estimate')->nullable();
            $table->text('financing_mode')->nullable();
            $table->enum('state', ActionStateEnum::toArray())->default(ActionStateEnum::NEW->value);
            $table->enum('priority', ActionPriorityEnum::toArray())->default(ActionPriorityEnum::UNDETERMINED->value);
            $table->text('work_plan')->nullable();
            $table->text('evaluation_indicator')->nullable();
            $table->timestamps();
        });

        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
            $table->string('idImport')->nullable();
            $table->string('initials')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Action::class)->constrained()->cascadeOnDelete();
            $table->text('content');
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('idImport')->nullable();
            $table->string('initials')->nullable();
            $table->enum('synergy', SynergyEnum::toArray())->default(SynergyEnum::COMMON->value);
            $table->timestamps();
        });

        Schema::create('odds', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('position')->default(0);
            $table->text('description')->nullable();
            $table->text('justification')->nullable();
            $table->string('idImport')->nullable();
            $table->string('action_id')->nullable();
           // $table->foreignIdFor(Action::class)->constrained('actions')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Action::class)->constrained()->cascadeOnDelete();
            $table->uuid()->nullable()->unique();
            $table->string('name')->nullable();
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->string('disk')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->nullableTimestamps();
        });

        Schema::create('action_user', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Action::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->unique(['action_id', 'user_id']);
        });

        Schema::create('action_partner', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Action::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Partner::class)->constrained()->cascadeOnDelete();
            $table->unique(['action_id', 'partner_id']);
        });

        Schema::create('action_service', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Action::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Service::class)->constrained()->cascadeOnDelete();
            $table->unique(['action_id', 'service_id']);
        });

        Schema::create('service_user', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Service::class)->constrained()->cascadeOnDelete();
            $table->unique(['user_id', 'service_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('media');
        Schema::dropIfExists('odds');
        Schema::dropIfExists('services');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('partners');
        Schema::dropIfExists('actions');
        Schema::dropIfExists('action_service');
        Schema::dropIfExists('action_partner');
        Schema::dropIfExists('action_user');
        Schema::dropIfExists('service_user');
        Schema::dropIfExists('operational_objectives');
        Schema::dropIfExists('strategic_objectives');
    }
};
