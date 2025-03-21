<?php

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
            $table->timestamps();
        });

        Schema::create('operational_objectives', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(StrategicObjective::class)->constrained('strategic_objectives')->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(OperationalObjective::class)->constrained('operational_objectives')->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->text('budget_estimate')->nullable();
            $table->text('financing_mode')->nullable();
            $table->enum('progress_indicator', ActionStateEnum::toArray())->default(ActionStateEnum::NEW->value);
            $table->text('work_plan')->nullable();
            $table->text('evaluation_indicator')->nullable();
            $table->timestamps();
        });

        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(false);
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
            $table->string('initials')->nullable();
            $table->enum('synergy', SynergyEnum::toArray())->default(SynergyEnum::COMMON->value);
            $table->timestamps();
        });

        Schema::create('odds', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(Action::class)->constrained('actions')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('medias', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Action::class)->constrained()->cascadeOnDelete();
            $table->morphs('model');
            $table->uuid()->nullable()->unique();
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->string('disk');
            $table->unsignedBigInteger('size');

            $table->nullableTimestamps();
        });

        Schema::create('action_users', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Action::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->unique(['action_id', 'user_id']);
        });

        Schema::create('action_partners', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Action::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Partner::class)->constrained()->cascadeOnDelete();
            $table->unique(['action_id', 'partner_id']);
        });

        Schema::create('action_services', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Action::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Service::class)->constrained()->cascadeOnDelete();
            $table->unique(['action_id', 'service_id']);
        });

        Schema::create('service_users', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Service::class)->constrained()->cascadeOnDelete();
            $table->unique(['user_id', 'service_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('medias');
        Schema::dropIfExists('service_users');
        Schema::dropIfExists('odds');
        Schema::dropIfExists('services');
        Schema::dropIfExists('politicians');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('partners');
        Schema::dropIfExists('actions');
        Schema::dropIfExists('action_services');
        Schema::dropIfExists('action_partners');
        Schema::dropIfExists('action_users');
        Schema::dropIfExists('operational_objectives');
        Schema::dropIfExists('strategic_objectives');
    }
};
