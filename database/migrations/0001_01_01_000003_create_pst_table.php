<?php

use App\Constant\ActionStateEnum;
use App\Constant\SynergyEnum;
use App\Models\Action;
use App\Models\Odd;
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
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Action::class)->constrained('actions')->cascadeOnDelete();
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
            $table->timestamps();
        });

        Schema::create('action_user', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Action::class)->constrained('actions')->cascadeOnDelete();
            $table->foreignIdFor(User::class)->constrained('users')->cascadeOnDelete();
            $table->unique(['action_id', 'user_id']);
        });

        Schema::create('action_partner', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Action::class)->constrained('actions')->cascadeOnDelete();
            $table->foreignIdFor(Partner::class)->constrained('partners')->cascadeOnDelete();
            $table->unique(['action_id', 'partner_id']);
        });

        Schema::create('action_service', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Action::class)->constrained('actions')->cascadeOnDelete();
            $table->foreignIdFor(Service::class)->constrained('services')->cascadeOnDelete();
            $table->unique(['action_id', 'service_id']);
        });

        Schema::create('action_odd', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Action::class)->constrained('actions')->cascadeOnDelete();
            $table->foreignIdFor(Odd::class)->constrained('odds')->cascadeOnDelete();
            $table->unique(['action_id', 'odd_id']);
        });

        Schema::create('service_users', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(Service::class)->constrained('services')->cascadeOnDelete();
            $table->unique(['user_id', 'service_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('action_odd');
        Schema::dropIfExists('action_service');
        Schema::dropIfExists('action_partner');
        Schema::dropIfExists('action_user');
        Schema::dropIfExists('service_user');
        Schema::dropIfExists('odds');
        Schema::dropIfExists('services');
        Schema::dropIfExists('politicians');
        Schema::dropIfExists('comments');
        Schema::dropIfExists('partners');
        Schema::dropIfExists('actions');
        Schema::dropIfExists('operational_objectives');
        Schema::dropIfExists('strategic_objectives');
    }
};
